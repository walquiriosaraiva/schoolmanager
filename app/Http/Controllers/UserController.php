<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\User;
use App\Repositories\PaymentRepository;
use App\Repositories\StudentAcademicInfoRepository;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;
use App\Traits\SchoolSession;
use App\Interfaces\UserInterface;
use App\Interfaces\SectionInterface;
use App\Interfaces\SchoolClassInterface;
use App\Repositories\PromotionRepository;
use App\Http\Requests\StudentStoreRequest;
use App\Http\Requests\TeacherStoreRequest;
use App\Interfaces\SchoolSessionInterface;
use App\Repositories\StudentParentInfoRepository;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    use SchoolSession;

    protected $userRepository;
    protected $schoolSessionRepository;
    protected $schoolClassRepository;
    protected $schoolSectionRepository;

    public function __construct(UserInterface $userRepository, SchoolSessionInterface $schoolSessionRepository,
                                SchoolClassInterface $schoolClassRepository,
                                SectionInterface $schoolSectionRepository)
    {
        $this->middleware(['can:view users']);

        $this->userRepository = $userRepository;
        $this->schoolSessionRepository = $schoolSessionRepository;
        $this->schoolClassRepository = $schoolClassRepository;
        $this->schoolSectionRepository = $schoolSectionRepository;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param TeacherStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function storeTeacher(TeacherStoreRequest $request)
    {
        try {
            $this->userRepository->createTeacher($request->validated());

            return back()->with('status', 'Teacher creation was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function getStudentList(Request $request)
    {
        $current_school_session_id = $this->getSchoolCurrentSession();

        $class_id = $request->query('class_id', 0);
        $section_id = $request->query('section_id', 0);

        try {

            $school_classes = $this->schoolClassRepository->getAllBySession($current_school_session_id);

            $studentList = $this->userRepository->getAllStudents($current_school_session_id, $class_id, $section_id);

            $data = [
                'studentList' => $studentList,
                'school_classes' => $school_classes,
            ];

            return view('students.list', $data);
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }


    public function showStudentProfile($id)
    {
        $student = $this->userRepository->findStudent($id);

        $current_school_session_id = $this->getSchoolCurrentSession();
        $promotionRepository = new PromotionRepository();
        $promotion_info = $promotionRepository->getPromotionInfoById($current_school_session_id, $id);

        $data = [
            'student' => $student,
            'promotion_info' => $promotion_info,
        ];

        return view('students.profile', $data);
    }

    public function showTeacherProfile($id)
    {
        $teacher = $this->userRepository->findTeacher($id);
        $data = [
            'teacher' => $teacher,
        ];
        return view('teachers.profile', $data);
    }


    public function createStudent()
    {
        $current_school_session_id = $this->getSchoolCurrentSession();

        $school_classes = $this->schoolClassRepository->getAllBySession($current_school_session_id);

        $months = array(
            1 => '1 month',
            2 => '2 months',
            3 => '3 months',
            4 => '4 months',
            5 => '5 months',
            6 => '6 months',
            7 => '7 months',
            8 => '8 months',
            9 => '9 months',
            10 => '10 months',
            11 => '11 months',
            12 => '12 months'
        );

        $data = [
            'current_school_session_id' => $current_school_session_id,
            'school_classes' => $school_classes,
            'months' => $months,
        ];

        return view('students.add', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param StudentStoreRequest $request
     * @return \Illuminate\Http\Response
     */
    public function storeStudent(StudentStoreRequest $request)
    {
        try {
            $this->userRepository->createStudent($request->validated());

            return back()->with('status', 'Student creation was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function editStudent($student_id)
    {
        $student = $this->userRepository->findStudent($student_id);
        $studentParentInfoRepository = new StudentParentInfoRepository();
        $parent_info = $studentParentInfoRepository->getParentInfo($student_id);
        $promotionRepository = new PromotionRepository();
        $current_school_session_id = $this->getSchoolCurrentSession();
        $promotion_info = $promotionRepository->getPromotionInfoById($current_school_session_id, $student_id);
        $paymentRepository = new PaymentRepository();
        $payment_info = $paymentRepository->getPaymentInfo($student_id);
        $paymentCount = $paymentRepository->getPaymentCount($student_id);

        $months = array(
            1 => '1 month',
            2 => '2 months',
            3 => '3 months',
            4 => '4 months',
            5 => '5 months',
            6 => '6 months',
            7 => '7 months',
            8 => '8 months',
            9 => '9 months',
            10 => '10 months',
            11 => '11 months',
            12 => '12 months'
        );

        $data = [
            'student' => $student,
            'parent_info' => $parent_info,
            'promotion_info' => $promotion_info,
            'months' => $months,
            'payment_info' => $payment_info,
            'paymentCount' => $paymentCount,
        ];
        return view('students.edit', $data);
    }

    public function updateStudent(Request $request)
    {
        try {

            if ($request->file()) {
                $dataFile = $request->file();
                if (isset($dataFile['ticket']) && $dataFile['ticket']) {
                    $ticket = $dataFile['ticket']->getClientOriginalName();
                    Storage::put('student/' . $request->get('student_id') . '/pdf-ticket/' . 'ticket.' . pathinfo($ticket, PATHINFO_EXTENSION), $request->file('ticket')->getContent());
                }
            }

            $this->userRepository->updateStudent($request->toArray());
            return back()->with('status', 'Student update was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function editTeacher($teacher_id)
    {
        $teacher = $this->userRepository->findTeacher($teacher_id);

        $data = [
            'teacher' => $teacher,
        ];

        return view('teachers.edit', $data);
    }

    public function updateTeacher(Request $request)
    {
        try {
            $this->userRepository->updateTeacher($request->toArray());

            return back()->with('status', 'Teacher update was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function getTeacherList()
    {
        $teachers = $this->userRepository->getAllTeachers();

        $data = [
            'teachers' => $teachers,
        ];

        return view('teachers.list', $data);
    }

    public function createPDF($id)
    {
        $page_html = false;

        $id = (int)$id;
        $result = User::where('users.id', '=', $id)
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

        $payment = Payment::where('student_id', '=', $id)->get();
        $totalGeral = 0;
        foreach ($payment as $value):
            $total = $value->tuition + $value->sdf + $value->hot_lunch + $value->enrollment;
            $value->total_geral_linha = $total - ($total / 100 * $value->percentage_discount);
            $totalGeral += $total - ($total / 100 * $value->percentage_discount);
        endforeach;

        $pdf = PDF::loadView('students.pdf_view', compact('result', 'page_html', 'payment', 'totalGeral'));

        Storage::put('students/' . $id . '/pdf/contract.pdf', $pdf->output());

        $image = Storage::disk('local')->allFiles("students/{$id}/pdf");
        $path = count($image) ? current($image) : "";
        $response = null;

        if (!empty($path)) {
            $response = Response::make(Storage::get($path), 200);
            $response->header('content-type', Storage::mimeType($path));
        }

        return $response;

    }

    public function index(Request $request)
    {
        $data = $request->all();
        $role = 0;
        if (isset($data['role']) && $data['role']):
            $role = $data['role'];
            $users = User::where('role', '=', $role)->get();
        else:
            $users = User::all();
        endif;

        $roles = ['admin' => 'Admin', 'student' => 'Student', 'teacher' => 'Teacher'];

        return view('users.index', compact('users', 'roles', 'role'));
    }

    public function create()
    {
        return view('users.create');
    }

    public function storeUser(Request $request)
    {

        try {
            $this->userRepository->storeUser($request->toArray());
            return back()->with('status', 'User create was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }

    }

    public function findUser($id)
    {
        $user = $this->userRepository->findUser($id);

        return view('users.edit', compact('user'));
    }

    public function updateUser(Request $request, $id)
    {
        try {
            $this->userRepository->updateUser($request->toArray(), $id);

            return back()->with('status', 'User update was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }
    }

    public function showUser($id)
    {
        $user = $this->userRepository->findUser($id);

        return view('users.profile', compact('user'));
    }

    public function destroyUser($id)
    {
        try {
            $this->userRepository->destroyUser($id);
            return back()->with('status', 'User delete was successful!');
        } catch (\Exception $e) {
            return back()->withError($e->getMessage());
        }

    }

}
