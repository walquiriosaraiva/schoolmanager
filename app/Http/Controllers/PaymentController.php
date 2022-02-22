<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use App\Models\Promotion;
use App\Models\User;
use Carbon\Carbon;
use Cnab\Factory;
use Illuminate\Http\Request;
use App\Traits\SchoolSession;
use App\Interfaces\UserInterface;
use App\Interfaces\SectionInterface;
use App\Interfaces\SchoolClassInterface;
use App\Interfaces\SchoolSessionInterface;

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

        /*
        $cnabFactory = new Factory();
        $arquivo = $cnabFactory->createRetorno('cnab/news/itau.ret');
        $detalhes = $arquivo->listDetalhes();

        foreach ($detalhes as $detalhe) {
            if ($detalhe->getValorRecebido() > 0) {
                $nossoNumero = $detalhe->getNossoNumero();
                $valorRecebido = $detalhe->getValorRecebido();
                $dataPagamento = $detalhe->getDataOcorrencia();
                $carteira = $detalhe->getCarteira();
                dd($nossoNumero, $valorRecebido, $dataPagamento, $carteira);
            }
        }
        */

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
                'payment.percentage_discount'
            )
            ->join('users', 'payment.student_id', '=', 'users.id')
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

    public function create(Request $request)
    {
        $students = User::where('role', '=', 'student')->get();
        if ($request->get('id')) {
            $payment = Payment::where('id', '=', $request->get('id'))->first();
        } else {
            $payment = [];
        }

        $months = array(
            1 => '1 mês',
            2 => '2 meses',
            3 => '3 meses',
            4 => '4 meses',
            5 => '5 meses',
            6 => '6 meses',
            7 => '7 meses',
            8 => '8 meses',
            9 => '9 meses',
            10 => '10 meses',
            11 => '11 meses',
            12 => '12 meses'
        );

        return view('payment.payment', compact('students', 'payment', 'months'));
    }


    public function store(Request $request)
    {
        $storeData['student_id'] = $request->get('student_id');
        $storeData['tuition'] = $request->get('tuition');
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

        return view('payment.edit', compact('students', 'payment'));
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

    public function show(Promotion $promotion)
    {
        //
    }

    public function destroy(Promotion $promotion)
    {
        //
    }
}
