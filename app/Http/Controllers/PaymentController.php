<?php

namespace App\Http\Controllers;

use App\Models\BankReturnData;
use App\Models\Payment;
use App\Models\PaymentConfirmEstudent;
use App\Models\Promotion;
use App\Models\StudentParentInfo;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Cnab\Factory;
use Illuminate\Http\Request;
use App\Traits\SchoolSession;
use App\Interfaces\UserInterface;
use App\Interfaces\SectionInterface;
use App\Interfaces\SchoolClassInterface;
use App\Interfaces\SchoolSessionInterface;
use Illuminate\Support\Facades\Storage;

class PaymentController extends Controller
{
    use SchoolSession;

    protected $schoolSessionRepository;
    protected $userRepository;
    protected $schoolClassRepository;
    protected $schoolSectionRepository;

    public function __construct(
        SchoolSessionInterface $schoolSessionRepository,
        UserInterface $userRepository,
        SchoolClassInterface $schoolClassRepository,
        SectionInterface $schoolSectionRepository
    )
    {
        $this->schoolSessionRepository = $schoolSessionRepository;
        $this->userRepository = $userRepository;
        $this->schoolClassRepository = $schoolClassRepository;
        $this->schoolSectionRepository = $schoolSectionRepository;
    }

    public function index(Request $request)
    {

        $result = Payment::where('payment.id', '!=', 0)
            ->select(
                'users.first_name',
                'users.last_name',
                'users.id',
                'payment.id',
                'payment.student_id',
                'payment.due_date',
                'payment.tuition',
                'payment.sdf',
                'payment.hot_lunch',
                'payment.enrollment',
                'payment.type_of_payment',
                'payment.status_payment',
                'payment.percentage_discount',
                'student_parent_infos.father_name',
                'student_parent_infos.mother_name'
            )
            ->join('users', 'payment.student_id', '=', 'users.id')
            ->join('student_parent_infos', 'student_parent_infos.student_id', '=', 'users.id')
            ->orderBy('users.id')
            ->orderBy('payment.due_date')
            ->get();

        return view('payment.index', compact('result'));
    }

    public function findStudent(Request $request)
    {
        $data = $request->all();
        $student = User::whereRaw('LOWER(first_name) LIKE ? ', ['%' . trim(strtolower($data['student'])) . '%'])
            ->orwhereRaw('LOWER(last_name) LIKE ? ', ['%' . trim(strtolower($data['student'])) . '%'])
            ->get();

        return response()->json($student);
    }

    public function paymentConfirm(Request $request)
    {
        $payment = [];

        return response()->json($payment);
    }

    public function create(Request $request)
    {
        $students = User::where('role', '=', 'student')->get();
        if ($request->get('id')) {
            $payment = Payment::where('id', '=', $request->get('id'))->first();
        } else {
            $payment = [];
        }

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

        return view('payment.payment', compact('students', 'payment', 'months'));
    }


    public function store(Request $request)
    {
        $storeData['student_id'] = $request->get('student_id');
        $storeData['sdf'] = $request->get('sdf');
        $storeData['hot_lunch'] = $request->get('hot_lunch');
        $storeData['enrollment'] = $request->get('enrollment');
        $storeData['percentage_discount'] = $request->get('percentage_discount');
        $storeData['type_of_payment'] = $request->get('type_of_payment');
        $storeData['status_payment'] = $request->get('status_payment');

        try {

            for ($i = 1; $i <= $request->get('contract_duration'); $i++):
                $explodeDate = explode('-', $request->get('due_date'));
                $date = Carbon::create($explodeDate[0], $explodeDate[1], $explodeDate[2]);
                $storeData['due_date'] = $date->addMonths($i);

                Payment::create($storeData);
            endfor;


            return redirect()->route('payment.index')
                ->withInput()
                ->with(['success' => 'success']);

        } catch (\Exception $e) {
            return redirect()->route('payment.index')
                ->withInput()
                ->with(['error' => 'Error' . $e->getMessage()]);
        }

    }

    public function edit($id)
    {
        $students = User::where('role', '=', 'student')->get();
        $payment = Payment::where('id', '=', $id)->first();
        $studentsParents = StudentParentInfo::where('student_id', '=', $payment->student_id)->first();
        $bankReturnData = BankReturnData::all();

        return view('payment.edit', compact('students', 'payment', 'bankReturnData', 'studentsParents'));
    }


    public function update(Request $request, $id)
    {
        $updateData['student_id'] = $request->get('student_id');
        $updateData['due_date'] = $request->get('due_date');
        $updateData['tuition'] = $request->get('tuition');
        $updateData['sdf'] = $request->get('sdf');
        $updateData['hot_lunch'] = $request->get('hot_lunch');
        $updateData['enrollment'] = $request->get('enrollment');
        $updateData['percentage_discount'] = $request->get('percentage_discount');
        $updateData['type_of_payment'] = $request->get('type_of_payment');
        $updateData['status_payment'] = $request->get('status_payment');


        try {
            if ($request->get('bank_return_data_id')) {
                $countRegister = PaymentConfirmEstudent::where('bank_return_data_id', '=', $request->get('bank_return_data_id'))
                    ->where('student_id', '=', $request->get('student_id'))
                    ->count();
                if ($countRegister === 0) {
                    $paymentConfirmEstudent['bank_return_data_id'] = $request->get('bank_return_data_id');
                    $paymentConfirmEstudent['student_id'] = $request->get('student_id');

                    PaymentConfirmEstudent::create($paymentConfirmEstudent);

                    $updateData['type_of_payment'] = 'Automatic payment';
                    $updateData['status_payment'] = 'Confirmado';
                }

                $updateData['updated_at'] = Carbon::now();

            }

            Payment::whereId($id)->update($updateData);

            return redirect()->route('payment.index')
                ->withInput()
                ->with(['success' => 'success']);
        } catch (\Exception $e) {
            return redirect()->route('payment.index')
                ->withInput()
                ->with(['error' => 'error: ' . $e->getMessage()]);
        }

    }

    public function createPDF($data)
    {
        $page_html = false;
        $result = User::where('users.id', '=', $data['id'])
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
                'student_parent_infos.mother_name'
            )
            ->join('promotions', 'promotions.student_id', '=', 'users.id')
            ->join('school_sessions', 'promotions.session_id', '=', 'school_sessions.id')
            ->join('student_parent_infos', 'users.id', '=', 'student_parent_infos.student_id')
            ->first();

        $payment = Payment::where('student_id', '=', $data['id'])->get();
        $totalGeral = 0;
        foreach ($payment as $value):
            $value->total_geral_linha = number_format($value->tuition + $value->sdf + $value->hot_lunch + $value->enrollment, 2);
            $totalGeral += $value->tuition + $value->sdf + $value->hot_lunch + $value->enrollment;
        endforeach;

        $pdf = PDF::loadView('students.pdf_view', compact('result', 'page_html', 'payment', 'totalGeral'));

        Storage::put('student' . $data['id'] . '/pdf/contract.pdf', $pdf->output());

        return true;

    }

    public function viewHtml()
    {
        $page_html = true;
        $result = User::where('users.id', '=', 17)
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
                'student_parent_infos.cpf_or_passport',
                'student_parent_infos.father_phone',
                'student_parent_infos.mother_phone',
                'student_parent_infos.father_name',
                'student_parent_infos.mother_name'
            )
            ->join('promotions', 'promotions.student_id', '=', 'users.id')
            ->join('school_sessions', 'promotions.session_id', '=', 'school_sessions.id')
            ->join('student_parent_infos', 'users.id', '=', 'student_parent_infos.student_id')
            ->first();

        $payment = Payment::where('student_id', '=', 17)->get();
        $totalGeral = 0;
        foreach ($payment as $value):
            $value->total_geral_linha = number_format($value->tuition + $value->sdf + $value->hot_lunch + $value->enrollment, 2);
            $totalGeral += $value->tuition + $value->sdf + $value->hot_lunch + $value->enrollment;
        endforeach;

        return view('payment.pdf_view', compact('result', 'page_html', 'payment', 'totalGeral'));
    }

    public function prepareData(Request $request)
    {
        $storeData['tuition'] = $request->get('tuition');

        return true;
    }

    public function show(Promotion $promotion)
    {
        //
    }

    public function destroy(Promotion $promotion)
    {
        //
    }
}
