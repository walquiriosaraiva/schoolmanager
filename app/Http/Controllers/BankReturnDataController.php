<?php

namespace App\Http\Controllers;

use App\Models\BankReturnData;
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
use Illuminate\Support\Facades\Storage;

class BankReturnDataController extends Controller
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

        $result = BankReturnData::all();

        return view('bank-return-data.index', compact('result'));
    }

    public function create(Request $request)
    {
        return view('bank-return-data.create');
    }


    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required'
        ]);


        try {

            if ($request->file()) {
                $fileName = Carbon::now()->format('YmdHis') . '_' . $request->file->getClientOriginalName();

                Storage::put('cnab/new/' . $fileName, $request->file('file')->getContent());

                $cnabFactory = new Factory();
                $arquivo = $cnabFactory->createRetorno(base_path() . '/storage/app/cnab/new/' . $fileName);
                $detalhes = $arquivo->listDetalhes();

                $dataCreate = [];
                foreach ($detalhes as $detalhe) {
                    if ($detalhe->nosso_numero) {
                        $countNossoNumero = BankReturnData::where('nosso_numero', '=', $detalhe->nosso_numero)->count();
                        if ($countNossoNumero === 0) {
                            $date = Carbon::createFromFormat('dmy', $detalhe->data_de_ocorrencia)->format('Y-m-d');
                            $data = Carbon::parse($date);
                            $dataCreate['nosso_numero'] = $detalhe->nosso_numero;
                            $dataCreate['valor_principal'] = $detalhe->valor_principal;
                            $dataCreate['data_de_ocorrencia'] = $data;
                            $dataCreate['carteira'] = $detalhe->carteira;
                            $dataCreate['nome_do_sacado'] = $detalhe->nome_do_sacado;
                            BankReturnData::create($dataCreate);
                        }
                    }
                }

                Storage::move('cnab/new/' . $fileName, 'cnab/old/' . $fileName);

            }


        } catch (\Exception $e) {
            return redirect()->route('bank-return-data.index')
                ->withInput()
                ->with(['error' => 'Error' . $e->getMessage()]);
        }

        return redirect()->route('bank-return-data.index')
            ->withInput()
            ->with(['success' => 'success']);

    }

}
