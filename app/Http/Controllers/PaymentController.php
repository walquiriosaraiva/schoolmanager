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
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Traits\SchoolSession;
use App\Interfaces\UserInterface;
use App\Interfaces\SectionInterface;
use App\Interfaces\SchoolClassInterface;
use App\Interfaces\SchoolSessionInterface;
use Illuminate\Support\Facades\Storage;
use PHPMailer\PHPMailer\PHPMailer;

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
        $data = $request->all();
        $student_id = 0;
        $students = User::where('role', '=', 'student')->get();
        if (auth()->user()->role === 'student'):
            $result = Payment::where('users.id', '=', auth()->user()->id)
                ->select(
                    'users.first_name',
                    'users.last_name',
                    'payment.id',
                    'payment.student_id',
                    'payment.due_date',
                    'payment.tuition',
                    'payment.sdf',
                    'payment.hot_lunch',
                    'payment.enrollment',
                    'payment.type_of_payment',
                    'payment.status_payment',
                    'payment.upload_ticket',
                    'payment.percentage_discount',
                    'student_parent_infos.father_name',
                    'student_parent_infos.mother_name'
                )
                ->join('users', 'payment.student_id', '=', 'users.id')
                ->join('student_parent_infos', 'student_parent_infos.student_id', '=', 'users.id')
                ->orderBy('users.id')
                ->orderBy('payment.due_date')
                ->get();

        else:
            if (isset($data['student_id']) && $data['student_id']):
                $student_id = $data['student_id'];
                $result = Payment::where('users.id', '=', $data['student_id'])
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
                        'payment.upload_ticket',
                        'payment.percentage_discount',
                        'student_parent_infos.father_name',
                        'student_parent_infos.mother_name'
                    )
                    ->join('users', 'payment.student_id', '=', 'users.id')
                    ->join('student_parent_infos', 'student_parent_infos.student_id', '=', 'users.id')
                    ->orderBy('users.id')
                    ->orderBy('payment.due_date')
                    ->get();
            else:
                $result = Payment::where('users.id', '!=', 0)
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
                        'payment.upload_ticket',
                        'payment.percentage_discount',
                        'student_parent_infos.father_name',
                        'student_parent_infos.mother_name'
                    )
                    ->join('users', 'payment.student_id', '=', 'users.id')
                    ->join('student_parent_infos', 'student_parent_infos.student_id', '=', 'users.id')
                    ->orderBy('users.id')
                    ->orderBy('payment.due_date')
                    ->get();
            endif;
        endif;

        foreach ($result as $key => $value):
            $total = $value->tuition + $value->sdf + $value->hot_lunch + $value->enrollment;
            $totaLinha = $total - ($total / 100 * $value->percentage_discount);
            $value->totalLinha = number_format($totaLinha, 2, ',', '.');
        endforeach;

        return view('payment.index', compact('result', 'students', 'student_id'));
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
        $storeData['tuition'] = $request->get('tuition');
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
        $bankReturnData = BankReturnData::whereNotExists(function ($query) {
            $query->select(DB::raw(1))
                ->from('payment_confirm_estudent')
                ->whereRaw('payment_confirm_estudent.bank_return_data_id = bank_return_data.id');
        })->get();

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
                    $paymentConfirmEstudent['payment_confirm_estudent_id'] = $id;

                    PaymentConfirmEstudent::create($paymentConfirmEstudent);

                    $updateData['type_of_payment'] = 'Automatic payment';
                    $updateData['status_payment'] = 'Confirmado';
                }

                $updateData['updated_at'] = Carbon::now();

            }

            if (isset($request['ticket']) && $request['ticket']) {
                $updateData['upload_ticket'] = '1';
                $ticket = $request['ticket']->getClientOriginalName();
                Storage::put('payment/payment_' . $id . '/student_' . $request->get('student_id') . '/pdf-ticket/' . 'ticket.' . pathinfo($ticket, PATHINFO_EXTENSION), $request->file('ticket')->getContent());
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
            $value->total_geral_linha = number_format($total - ($total / 100 * $value->percentage_discount), 2);
            $totalGeral += $value->total_geral_linha;
        endforeach;

        $pdf = PDF::loadView('students.pdf_view', compact('result', 'page_html', 'payment', 'totalGeral'));

        Storage::put('students/' . (int)$id . '/pdf/contract.pdf', $pdf->output());

        return true;

    }

    public function viewHtml($id)
    {
        $page_html = true;
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

        $payment = Payment::where('student_id', '=', $id)->get();
        $totalGeral = 0;
        foreach ($payment as $value):
            $value->total_geral_linha = number_format($value->tuition + $value->sdf + $value->hot_lunch + $value->enrollment, 2);
            $totalGeral += $value->tuition + $value->sdf + $value->hot_lunch + $value->enrollment;
        endforeach;

        return view('students.pdf_view', compact('result', 'page_html', 'payment', 'totalGeral'));
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

    public function destroy($id)
    {
        if (PaymentConfirmEstudent::where('payment_confirm_estudent_id', '=', $id)->count() === 0):
            $destroyData = Payment::findOrFail($id);

            if ($destroyData->delete()):
                return redirect()->route('payment.index')
                    ->withInput()
                    ->with(['success' => 'Pagamento excluido com sucesso']);
            else:
                return redirect()->route('payment.index')
                    ->withInput()
                    ->with(['error' => 'Erro ao tentar excluir o pagamento']);
            endif;
        else:
            return redirect()->route('payment.index')
                ->withInput()
                ->with(['error' => 'Erro ao tentar excluir o pagamento, pois já está vinculado e não pode ser excluído']);
        endif;
    }

    public function sendEmail($id)
    {
        $mail = new PHPMailer(true);

        $user = User::where('id', '=', $id)->first();

        try {

            $mail->isSMTP();
            $mail->CharSet = 'UTF-8';
            $mail->SMTPAuth = true;
            $mail->SMTPSecure = env('MAIL_ENCRYPTION');
            $mail->Host = env('MAIL_HOST');
            $mail->Port = env('MAIL_PORT');
            $mail->Username = env('MAIL_USERNAME');
            $mail->Password = env('MAIL_PASSWORD');
            $mail->setFrom(env('MAIL_FROM_ADDRESS'), env('MAIL_FROM_NAME'));
            $mail->Subject = 'Assunto a ser definido';
            $mail->isHTML(true);

            $mensagemConteudo = "Corpo do e-mail a ser definido";

            $mail->MsgHTML($mensagemConteudo);
            $mail->addAddress($user->email, $user->first_name . ' ' . $user->last_name);

            /*
            $path = Storage::get("student/{$id}/pdf-ticket/ticket.pdf");
            $response = Response::make($path, 200);
            $response->header('content-type', 'application/pdf');
            $mail->addAttachment($response, 'Boleto.pdf');
            */

            if ($mail->send()):
                $result = array('result' => 'success', 'title' => 'Contato enviado com sucesso!');
            else:
                $result = array('result' => 'error', 'title' => 'Ouve erro ao tentar enviar e-mail!');
            endif;
        } catch (\Exception $e) {
            $result = array('result' => 'error', 'title' => $e->getMessage());
            return $e->getMessage();
        }

        return true;

    }
}
