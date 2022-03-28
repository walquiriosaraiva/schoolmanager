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
                    'view notices'
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
                    'application_grade' => $request['application_grade'],
                    'date_to_start_school' => $request['date_to_start_school'],
                    'language_spoken_at_home' => $request['language_spoken_at_home'],
                    'last_school_attended' => $request['last_school_attended'],
                    'last_grade_enrolled' => $request['last_grade_enrolled'],
                    'medicines' => $request['medicines'],
                    'iep' => $request['iep'],
                    'scn' => $request['scn'],
                    'ethnicity' => $request['ethnicity'],
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

                $this->saveDocuments($student->id, $request);

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
                    'application_grade' => $request['application_grade'],
                    'date_to_start_school' => $request['date_to_start_school'],
                    'language_spoken_at_home' => $request['language_spoken_at_home'],
                    'last_school_attended' => $request['last_school_attended'],
                    'last_grade_enrolled' => $request['last_grade_enrolled'],
                    'medicines' => $request['medicines'],
                    'iep' => $request['iep'],
                    'scn' => $request['scn'],
                    'ethnicity' => $request['ethnicity'],
                    'blood_type' => $request['blood_type'],
                    'photo' => (!empty($request['photo'])) ? $this->convert($request['photo']) : null,
                ]);

                // Update Parents' information
                $studentParentInfoRepository = new StudentParentInfoRepository();
                $studentParentInfoRepository->update($request, $request['student_id']);

                // Update Student's ID card number
                $promotionRepository = new PromotionRepository();
                $promotionRepository->update($request, $request['student_id']);

                $this->saveDocuments($request['student_id'], $request);

            });
        } catch (\Exception $e) {
            throw new \Exception('Failed to update Student. ' . $e->getMessage());
        }
    }

    /**
     * @param $id
     * @param $request
     * @return bool
     */
    public function saveDocuments($id, $request)
    {

        if (isset($request['transcript']) && $request['transcript']) {
            $transcript = $request['transcript']->getClientOriginalName();
            Storage::put('students/' . $id . '/documents/' . 'transcript.' . pathinfo($transcript, PATHINFO_EXTENSION), $request['transcript']->getContent());
        }

        if (isset($request['student_identidade']) && $request['student_identidade']) {
            $studentIdentidade = $request['student_identidade']->getClientOriginalName();
            Storage::put('students/' . $id . '/documents/' . 'student_identidade.' . pathinfo($studentIdentidade, PATHINFO_EXTENSION), $request['student_identidade']->getContent());
        }

        if (isset($request['vaccination_record']) && $request['vaccination_record']) {
            $vaccinationRecord = $request['vaccination_record']->getClientOriginalName();
            Storage::put('students/' . $id . '/documents/' . 'vaccination_record.' . pathinfo($vaccinationRecord, PATHINFO_EXTENSION), $request['vaccination_record']->getContent());
        }

        if (isset($request['digital_student_photo']) && $request['digital_student_photo']) {
            $digitalStudentPhoto = $request['digital_student_photo']->getClientOriginalName();
            Storage::put('students/' . $id . '/documents/' . 'digital_student_photo.' . pathinfo($digitalStudentPhoto, PATHINFO_EXTENSION), $request['digital_student_photo']->getContent());
        }

        if (isset($request['primary_parent_passport']) && $request['primary_parent_passport']) {
            $primaryParentPassport = $request['primary_parent_passport']->getClientOriginalName();
            Storage::put('students/' . $id . '/documents/' . 'primary_parent_passport.' . pathinfo($primaryParentPassport, PATHINFO_EXTENSION), $request['primary_parent_passport']->getContent());
        }

        if (isset($request['outros_documentos']) && $request['outros_documentos']) {
            $outrosDocumentos = $request['outros_documentos']->getClientOriginalName();
            Storage::put('students/' . $id . '/documents/' . 'outros_documentos.' . pathinfo($outrosDocumentos, PATHINFO_EXTENSION), $request['outros_documentos']->getContent());
        }

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

    public function storeUser($request)
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
                    'role' => 'admin',
                    'password' => Hash::make($request['password']),
                ]);

                $user->givePermissionTo(
                    'create school sessions',
                    'update browse by session',
                    'create semesters',
                    'edit semesters',
                    'assign teachers',
                    'create courses',
                    'view courses',
                    'edit courses',
                    'create classes',
                    'view classes',
                    'edit classes',
                    'create sections',
                    'view sections',
                    'edit sections',
                    'create exams',
                    'view exams',
                    'create exams rule',
                    'edit exams rule',
                    'delete exams rule',
                    'view exams rule',
                    'create routines',
                    'view routines',
                    'edit routines',
                    'delete routines',
                    'view marks',
                    'view academic settings',
                    'update marks submission window',
                    'create users',
                    'edit users',
                    'view users',
                    'promote students',
                    'update attendances type',
                    'view attendances',
                    'take attendances',
                    'create grading systems',
                    'view grading systems',
                    'edit grading systems',
                    'delete grading systems',
                    'create grading systems rule',
                    'view grading systems rule',
                    'edit grading systems rule',
                    'delete grading systems rule',
                    'create notices',
                    'view notices',
                    'edit notices',
                    'delete notices',
                    'create events',
                    'view events',
                    'edit events',
                    'delete events',
                    'create syllabi',
                    'view syllabi',
                    'edit syllabi',
                    'delete syllabi',
                    'view assignments'
                );
            });
        } catch (\Exception $e) {
            throw new \Exception('Failed to create User. ' . $e->getMessage());
        }
    }

    public function findUser($id)
    {
        try {
            return User::where('id', $id)->where('role', 'admin')->first();
        } catch (\Exception $e) {
            throw new \Exception('Failed to get admin. ' . $e->getMessage());
        }
    }

    public function updateUser($request, $id)
    {
        try {
            DB::transaction(function () use ($request) {
                User::where('id', $request['id'])->update([
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
            throw new \Exception('Failed to update User. ' . $e->getMessage());
        }
    }

    public function destroyUser($id)
    {
        dd($id);
    }
}
