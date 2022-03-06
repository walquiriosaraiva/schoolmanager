<!DOCTYPE html>
<html lang="pt-br">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>CONTRATO FINANCEIRO</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">

</head>

<body>
<div style="width: 100%;">
    <h2 class="text-center mb-3">
        @if($page_html)
            <img src="/logo1.PNG">
        @else
            <img src="{{ public_path() }}/logo1.PNG">
        @endif
    </h2>

    <div style="width: 100%;">
        <div class="col-lg-12 p-4 text-center">
            <h4>CONTRATO FINANCEIRO {{ $result->session_name }}</h4>
        </div>

        <div class="p-1">
            <h5>REPONSABILIDADE FINANCEIRA</h5>
            <p style="text-align: justify; font-size: 10px;">
                Eu/nós somos financeiramente responsáveis por todas as mensalidades, taxas e encargos de acordo com a
                política estabelecida neste contrato, que se aplicam à matrícula do(s) estudante(s) listado(s) abaixo.
                Eu/nós entendemos que todas as referências neste contrato de mensalidade, Taxa de Desenvolvimento da
                Escola (SDF – School Development Fund fee) e todas as outras taxas aplicáveis ​​(inclusive multas por
                atraso) se referem aos encargos estabelecidos pela Brasilia International School (BIS), a administração
                e os órgãos de governo referente ao ano letivo de {{ $result->session_name }}. Eu/nós entendemos que é
                minha/nossa a responsabilidade final de garantir que todas as obrigações financeiras sejam cumpridas
                dentro dos parâmetros deste contrato. Isso é independentemente de qualquer financiamento educacional
                fornecido por fontes familiares independentes, de embaixadas, corporativas ou pessoais. Eu/nós
                concordamos que qualquer inadimplemento em qualquer forma relacionada a este contrato resultará em que o
                aluno não se torne elegível para rematrícula, e constituirá a utilização da cobrança de dívidas via
                assessoria jurídica. Ao aceitar e assinar este contrato, eu/nós concordamos com os termos estabelecidos
                no contrato e concordamos em pagar qualquer taxa associada à cobrança, custos legais, encargos atrasados
                ​​ou outros custos que possam resultar do não pagamento da mensalidade e taxas.
                Eu/nós entendemos que a BIS tem plenos direitos para descontinuar ou modificar os parâmetros deste
                contrato em qualquer momento no melhor interesse da instituição ou para assegurar a viabilidade
                financeira da escola.
            </p>
        </div>

        <div class="p-1">
            <p style="text-align: right;"
               class="text-uppercase p-1">
                ASSINATURA: _______________________________________
            </p>
        </div>

        <div class="p-1">

            <table class="table-bordered table-sm" style="margin-bottom: 1%;">
                <tr>
                    <td>
                        Termos Financeiros do Acordo ({{ $result->session_name }})
                    </td>
                </tr>
                <tr>
                    <td>
                        Nome dos Pais/Responsáveis: Pai: {{ $result->father_name }} - Mãe: {{ $result->mother_name }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Nome(s) do Estudante(s): {{ $result->first_name }} {{ $result->last_name }}
                    </td>
                </tr>
            </table>

            <table class="table-bordered table-sm">
                <thead>
                <tr>
                    <th>Due date</th>
                    <th>Tuition</th>
                    <th>SDF</th>
                    <th>Hot lunch</th>
                    <th>Enrollment</th>
                    <th>Total</th>
                </tr>
                </thead>
                <tbody>
                @isset($result)
                    @foreach ($payment as $object)
                        <tr>
                            <td>{{$object->due_date->format('d/m/Y')}}</td>
                            <td>{{$object->tuition}}</td>
                            <td>{{$object->sdf}}</td>
                            <td>{{$object->hot_lunch}}</td>
                            <td>{{$object->enrollment}}</td>
                            <td>{{$object->total_geral_linha}}</td>
                        </tr>
                    @endforeach
                @endisset
                </tbody>
            </table>

            <table class="table-bordered table-sm" style="margin-top: 10px;">
                <tr>
                    <td>
                        {{ $result->session_name }}
                    </td>
                    <td>
                        Valor total anual devido: {{ number_format($totalGeral, 2) }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="p-1" style="">
            <h5>Matrícula / Taxa de Matrícula</h5>
            <p style="text-align: justify; font-size: 10px;">
                Essa taxa destina-se a todas as novas aplicações de matrícula enviadas para aprovação. A taxa cobre
                todos os testes, avaliações e consultas acadêmicas necessárias. As matrículas só serão consideradas após
                o recebimento desta taxa. A taxa reserva a(s) vaga(s) do(s) estudante(s) para o próximo ano letivo, se
                aprovado. Um boleto referente a esta taxa será emitido após o recebimento da aplicação de matrícula.
                Esta taxa é proporcionalmente reembolsável se o(s) estudante(s) se retirar da BIS dentro de 60 dias
                desde o início das aulas. Após este período de 60 dias desde o início das aulas, esta taxa não será
                reembolsável.
            </p>
        </div>

        <div class="p-1" style="">
            <h5>Taxa de Rematrícula</h5>
            <p style="text-align: justify; font-size: 10px;">
                Esta taxa anual se aplica a todos os alunos desde o ELC 3 a 12a série. A taxa destina-se a todos as
                aplicações de rematrícula enviadas para aprovação. A taxa reserva a(s) vaga(s) do(s) estudante(s) para o
                ano letivo seguinte, se aprovado. Um boleto para esta taxa será emitido após o recebimento aplicação de
                matrícula. Esta taxa é proporcionalmente reembolsável se o estudante(s) se retirar da BIS no prazo de 60
                dias a contar do primeiro dia do ano letivo em que o pagamento se refere. Após este período de 60 dias,
                esta taxa não é reembolsável.
            </p>
        </div>

        <div class="p-1" style="">
            <h5>Taxas de Mensalidade</h5>
            <p style="text-align: justify; font-size: 10px;">
                Os valores anuais de matrícula para o ano letivo atual começam em agosto e terminam em julho. Todas as
                taxas são devidas no momento da matrícula; no entanto, existem várias opções de pagamento disponíveis.
            </p>
        </div>

        <div class="p-1" style="">
            <h5>Métodos de Pagamento</h5>
            <p style="text-align: justify; font-size: 10px;">
                A BIS tem parceria com o serviço do Banco Itaú para coordenar as cobranças e os pagamentos de todas as
                mensalidades e taxas. No início de cada ano letivo (ou no momento da matrícula), um carnê de pagamentos
                do Banco Itaú será emitido para cada família/corporação. Este carnê contém boletos de pagamento
                aplicáveis para o ano letivo atual. Você pode realizar os pagamentos em qualquer agência bancária até as
                datas de vencimento. No entanto, quaisquer pagamentos feitos após a data de vencimento devem ser
                efetuados em uma agência bancária do Itaú. Pagamentos não serão aceitos no departamento da escola.
            </p>
        </div>

        <div class="p-1" style="">
            <h5>Taxas por Atraso</h5>
            <p style="text-align: justify; font-size: 10px;">
                As taxas por atraso são cobradas pelo banco no momento do pagamento em referência a qualquer quantia
                vencida. Essas taxas são cobradas no valor de 2% do valor devido após a data de vencimento original mais
                0,33% ao dia. As taxas por atrasos não serão alteradas ou dispensadas pela escola por nenhum motivo.
                Isso inclui quaisquer atrasos para aqueles que estiverem esperando o financiamento educacional ser
                processado ou concedido.
            </p>
        </div>

        <div class="p-1" style="">
            <p style="text-align: justify; font-size: 10px;">
                Quaisquer pagamentos que excedam 60 dias de atraso serão enviados à agência de cobrança local com o
                número de CPF/CNPJ da parte responsável.
            </p>
        </div>

        <div class="p-1" style="">
            <h5>Fundo de Desenvolvimento Escolar (SDF – School Development Fund)</h5>
            <p style="text-align: justify; font-size: 10px;">
                O SDF não reembolsável é uma contribuição obrigatória implementada para garantir a futura expansão e
                crescimento dos programas escolares. O SDF é uma taxa anual devida para todos os alunos (desde o Jardim
                de Infância até a 12a série). As crianças matriculadas nos programas ELC (Early Learning Center – Centro
                de Aprendizagem Infantil) ou Pré-jardim de infância não precisam pagar essa taxa até que a criança
                comece estudar no jardim de infância.
            </p>
        </div>

        <div class="p-1" style="">
            <p style="text-align: justify; font-size: 10px;">
                O valor anual do SDF será devido integralmente se um estudante se retirar, transferir ou inadimplir suas
                obrigações financeiras designadas de qualquer forma. Todas as famílias que estiverem fazendo a matrícula
                devem se preparar para essa despesa.
            </p>
        </div>

        <div class="p-1" style="">
            <h5>Termos de Retirada Antecipada (Cancelamento de Contrato)</h5>
            <p style="text-align: justify; font-size: 10px;">
                Todas as famílias que retirarem seus filhos em qualquer momento durante o ano letivo (obrigatório ou
                voluntariamente) devem se reunir com o Diretor Financeiro da BIS para determinar o saldo restante devido
                e estão sujeitas a uma multa de retirada antecipada. Este saldo será determinado tomando-se o montante
                anual devido, proporcionalizando este montante ao valor do ano letivo frequentado, subtraindo a taxa de
                matrícula e taxas que já foram pagas. Qualquer família que retirar seus filhos após 10 de abril
                de {{ substr($result->session_name, -4) }}
                será obrigada a realizar todos os pagamentos de acordo com este contrato.
            </p>
        </div>

        <div class="p-1" style="text-align: justify; font-size: 10px;">
            <ul>
                <li>Para famílias com um plano de pagamento de 12 meses, essa taxa equivale à mensalidade de um (01) mês
                    após o último mês de matrícula, e é paga no momento da retirada.
                </li>
                <li>Para famílias com um plano de pagamento de 10 meses, o pagamento deve ser recebido no último mês em
                    que o estudante estiver matriculado.
                </li>
                <li>Retiradas após 10 de abril: os pagamentos de maio a julho são necessários conforme estabelecido na
                    política de cobrança.
                </li>
                <li>
                    Nenhum reembolso para pagamentos anuais ou semestrais efetuados com antecedência será concedido após
                    10 de abril.
                </li>
            </ul>
        </div>

        <div class="p-1" style="">
            <table class="table-bordered table-sm" style="margin-top: 10px;">
                <tr>
                    <td>
                        {{ $result->session_name }} - Informações para a Nota Fiscal
                    </td>
                </tr>
                <tr>
                    <td>
                        Nome exato que deve constar na Nota Fiscal: {{ $result->father_name }}
                        - {{ $result->mother_name }}
                    </td>
                </tr>
                <tr>
                    <td>
                        CPF: {{ $result->cpf_or_passport }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="p-1" style="">
            <table class="table-bordered table-sm" style="margin-top: 10px;">
                <tr>
                    <td>
                        Endereço: {{ $result->address }}
                    </td>
                    <td>
                        Cidade: {{ $result->city }}
                    </td>
                    <td>
                        CEP: {{ $result->zip }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="p-1" style="">
            <table class="table-bordered table-sm" style="margin-top: 10px;">
                <tr>
                    <td>
                        Em caso de dúvidas sobre pagamento entrar em contato com:
                    </td>
                </tr>
                <tr>
                    <td>
                        Nome: {{ $result->father_name }} ou {{ $result->mother_name }}
                    </td>
                </tr>
                <tr>
                    <td>
                        E-mail: {{ $result->email }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Telefone: {{ $result->father_phone }} ou {{ $result->mother_phone }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="p-1" style="">
            <p style="text-align: justify; font-size: 10px;">
                Por favor, devolva duas vias originais (em português) deste contrato completo, assinado, para a
                secretaria da escola no prazo de cinco (5) dias após o recebimento deste contrato. Este contrato deve
                ser assinado na presença de uma testemunha. Os estudantes não poderão assistir a nenhuma aula até que
                uma cópia assinada deste contrato tenha sido recebida pela BIS.
                <br/>
                Ambas as partes concordam expressamente com todos os termos deste contrato e elegem a jurisdição de
                Brasília para resolver qualquer disputa possível. Portanto, como tudo é justo e acordado, eles assinam
                este CONTRATO FINANCEIRO privado, em duas cópias de igual teor em português, na presença das duas
                testemunhas abaixo assinadas.
            </p>
        </div>

        <div class="p-1" style="">
            <table class="table-bordered table-sm" style="margin-top: 10px;">
                <tr>
                    <td>
                        da Parte Responsável: {{ $result->father_name }} ou {{ $result->mother_name }}
                    </td>
                </tr>
            </table>
        </div>

        <div class="p-1" style="">
            <table class="table-bordered table-sm" style="margin-top: 10px;">
                <tr>
                    <td>
                        Assinatura da Parte Responsável (Pais/Responsáveis): <br/>______________________________________
                    </td>
                </tr>
                <tr>
                    <td>
                        CPF da Parte Responsável (Pais/Responsáveis): {{ $result->cpf_or_passport }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Data de Aceitação: {{ date('d/m/Y') }}
                    </td>
                </tr>
                <tr>
                    <td>
                        Assinatura do Tesoureiro: _____________________________________
                    </td>
                </tr>
            </table>

            <h6>Testemunhas:</h6>
            <table class="table-bordered table-sm" style="margin-top: 10px;">
                <tr>
                    <td>
                        Nome: ___________________________________
                    </td>
                    <td>
                        Nome: ___________________________________
                    </td>
                </tr>
                <tr>
                    <td>
                        CPF: ______________________________
                    </td>
                    <td>
                        CPF: ______________________________
                    </td>
                </tr>
            </table>
        </div>
    </div>

</div>

</body>

</html>
