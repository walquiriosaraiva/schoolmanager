<?php

namespace App\Repositories;

use App\Models\Payment;
use App\Models\User;
use App\Traits\Base64ToFile;
use App\Interfaces\UserInterface;
use App\Models\SchoolClass;
use App\Models\Section;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use App\Repositories\PromotionRepository;
use App\Repositories\StudentParentInfoRepository;
use App\Repositories\StudentAcademicInfoRepository;
use Illuminate\Support\Facades\Storage;

class UserRepository implements UserInterface
{
    use Base64ToFile;

    /**
     * @param mixed $request
     * @return string
     */
    public function createTeacher($request)
    {
        try {
            DB::transaction(function () use ($request) {
                $user = User::create([
                    'first_name' => $request['first_name'],
                    'last_name' => $request['last_name'],
                    'email' => $request['email'],
                    'gender' => $request['gender'],
                    'nationality' => $request['nationality'],
                    'phone' => $request['phone'],
                    'address' => $request['address'],
                    'address2' => $request['address2'],
                    'city' => $request['city'],
                    'zip' => $request['zip'],
                    'photo' => (!empty($request['photo'])) ? $this->convert($request['photo']) : null,
                    'role' => 'teacher',
                    'password' => Hash::make($request['password']),
                ]);
                $user->givePermissionTo(
                    'create exams',
                    'view exams',
                    'create exams rule',
                    'view exams rule',
                    'edit exams rule',
                    'delete exams rule',
                    'take attendances',
                    'view attendances',
                    'create assignments',
                    'view assignments',
                    'save marks',
                    'view users',
                    'view routines',
                    'view syllabi',
                    'view events',
                    'view notices',
                );
            });
        } catch (\Exception $e) {
            throw new \Exception('Failed to create Teacher. ' . $e->getMessage());
        }
    }

    /**
     * @param mixed $request
     * @return string
     */
    public function createStudent($request)
    {
        try {
            DB::transaction(function () use ($request) {
                $student = User::create([
                    'first_name' => $request['first_name'],
                    'last_name' => $request['last_name'],
                    'email' => $request['email'],
                    'gender' => $request['gender'],
                    'nationality' => $request['nationality'],
                    'phone' => $request['phone'],
                    'address' => $request['address'],
                    'address2' => $request['address2'],
                    'city' => $request['city'],
                    'zip' => $request['zip'],
                    'photo' => (!empty($request['photo'])) ? $this->convert($request['photo']) : null,
                    'birthday' => $request['birthday'],
                    'religion' => $request['religion'],
                    'blood_type' => $request['blood_type'],
                    'role' => 'student',
                    'password' => Hash::make($request['password']),
                ]);

                // Store Parents' information
                $studentParentInfoRepository = new StudentParentInfoRepository();
                $studentParentInfoRepository->store($request, $student->id);

                // Store payment
                $paymentRepository = new PaymentRepository();
                $paymentRepository->store($request, $student->id);

                // Store Academic information
                $studentAcademicInfoRepository = new StudentAcademicInfoRepository();
                $studentAcademicInfoRepository->store($request, $student->id);

                // Assign student to a Class and a Section
                $promotionRepository = new PromotionRepository();
                $promotionRepository->assignClassSection($request, $student->id);

                $student->givePermissionTo(
                    'view attendances',
                    'view assignments',
                    'submit assignments',
                    'view exams',
                    'view marks',
                    'view users',
                    'view routines',
                    'view syllabi',
                    'view events',
                    'view notices'
                );

                $this->createPDF($student->id);

            });
        } catch (\Exception $e) {
            throw new \Exception('Failed to create Student. ' . $e->getMessage());
        }
    }

    public function updateStudent($request)
    {
        try {
            DB::transaction(function () use ($request) {
                User::where('id', $request['student_id'])->update([
                    'first_name' => $request['first_name'],
                    'last_name' => $request['last_name'],
                    'email' => $request['email'],
                    'gender' => $request['gender'],
                    'nationality' => $request['nationality'],
                    'phone' => $request['phone'],
                    'address' => $request['address'],
                    'address2' => $request['address2'],
                    'city' => $request['city'],
                    'zip' => $request['zip'],
                    'birthday' => $request['birthday'],
                    'religion' => $request['religion'],
                    'blood_type' => $request['blood_type'],
                    'photo' => (!empty($request['photo'])) ? $this->convert($request['photo']) : null,
                ]);

                // Update Parents' information
                $studentParentInfoRepository = new StudentParentInfoRepository();
                $studentParentInfoRepository->update($request, $request['student_id']);

                // Update Student's ID card number
                $promotionRepository = new PromotionRepository();
                $promotionRepository->update($request, $request['student_id']);

                $this->createPDF($request['student_id']);
            });
        } catch (\Exception $e) {
            throw new \Exception('Failed to update Student. ' . $e->getMessage());
        }
    }

    public function createPDF($id)
    {
        $page_html = false;

        $result = User::where('users.id', '=', (int)$id)
            ->select(
                'users.id',
                'users.first_name',
                'users.last_name',
                'users.email',
                'users.password',
                'users.gender',
                'users.nationality',
                'users.phone',
                'users.address',
                'users.address2',
                'users.city',
                'users.zip',
                'users.photo',
                'users.birthday',
                'users.religion',
                'users.blood_type',
                'users.role',
                'promotions.student_id',
                'promotions.class_id',
                'promotions.section_id',
                'promotions.session_id',
                'promotions.id_card_number',
                'school_sessions.session_name',
                'student_parent_infos.father_name',
                'student_parent_infos.father_phone',
                'student_parent_infos.mother_name',
                'student_parent_infos.mother_phone',
                'student_parent_infos.cpf',
                'student_parent_infos.passport'
            )
            ->join('promotions', 'promotions.student_id', '=', 'users.id')
            ->join('school_sessions', 'promotions.session_id', '=', 'school_sessions.id')
            ->join('student_parent_infos', 'users.id', '=', 'student_parent_infos.student_id')
            ->first();

        $payment = Payment::where('student_id', '=', (int)$id)->get();
        $totalGeral = 0;
        foreach ($payment as $value):
            $total = $value->tuition + $value->sdf + $value->hot_lunch + $value->enrollment;
            $desconto = $total - ($total / 100 * $value->percentage_discount);
            $value->total_geral_linha = number_format($total - $desconto, 2);
            $totalGeral += $value->total_geral_linha;
        endforeach;

        $pdf = PDF::loadView('students.pdf_view', compact('result', 'page_html', 'payment', 'totalGeral'));

        Storage::put('students/' . (int)$id . '/pdf/contract.pdf', $pdf->output());

        return true;

    }

    public function updateTeacher($request)
    {
        try {
            DB::transaction(function () use ($request) {
                User::where('id', $request['teacher_id'])->update([
                    'first_name' => $request['first_name'],
                    'last_name' => $request['last_name'],
                    'email' => $request['email'],
                    'gender' => $request['gender'],
                    'nationality' => $request['nationality'],
                    'phone' => $request['phone'],
                    'address' => $request['address'],
                    'address2' => $request['address2'],
                    'city' => $request['city'],
                    'zip' => $request['zip'],
                ]);
            });
        } catch (\Exception $e) {
            throw new \Exception('Failed to update Teacher. ' . $e->getMessage());
        }
    }

    public function getAllStudents($session_id, $class_id, $section_id)
    {
        if ($class_id == 0 || $section_id == 0) {
            $schoolClass = SchoolClass::where('session_id', $session_id)
                ->first();
            $section = Section::where('session_id', $session_id)
                ->first();
            if ($schoolClass == null || $section == null) {
                throw new \Exception('There is no class and section');
            } else {
                $class_id = $schoolClass->id;
                $section_id = $section->id;
            }

        }
        try {
            $promotionRepository = new PromotionRepository();
            return $promotionRepository->getAll($session_id, $class_id, $section_id);
        } catch (\Exception $e) {
            throw new \Exception('Failed to get all Students. ' . $e->getMessage());
        }
    }

    public function getAllStudentsBySession($session_id)
    {
        $promotionRepository = new PromotionRepository();
        return $promotionRepository->getAllStudentsBySession($session_id);
    }

    public function getAllStudentsBySessionCount($session_id)
    {
        $promotionRepository = new PromotionRepository();
        return $promotionRepository->getAllStudentsBySessionCount($session_id);
    }

    public function findStudent($id)
    {
        try {
            return User::with('parent_info', 'academic_info')->where('id', $id)->first();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get Student. ' . $e->getMessage());
        }
    }

    public function findTeacher($id)
    {
        try {
            return User::where('id', $id)->where('role', 'teacher')->first();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get Teacher. ' . $e->getMessage());
        }
    }

    public function getAllTeachers()
    {
        try {
            return User::where('role', 'teacher')->get();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get all Teachers. ' . $e->getMessage());
        }
    }

    public function changePassword($new_password)
    {
        try {
            return User::where('id', auth()->user()->id)->update([
                'password' => Hash::make($new_password)
            ]);
        } catch (\Exception $e) {
            throw new \Exception('Failed to change password. ' . $e->getMessage());
        }
    }
}
