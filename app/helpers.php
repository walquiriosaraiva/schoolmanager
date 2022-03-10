<?php

use App\MyApp;
use Carbon\Carbon;

if (!function_exists('cleanInput')) {

    /**
     * @param $data
     * Função para retirar caracteres especiais do input. Ex: CPF ou CNPJ
     * @return mixed
     */
    function cleanInput($data)
    {
        $data = trim($data);
        $remover = array(".", "-", "/", "(", ")", " ");
        return str_replace($remover, "", $data);
    }
}

if (!function_exists('mesExtenso')) {

    /**
     * @param $mes
     * Função para retornar o mês por extenso exemplo: Janeiro
     * @return bool|mixed|string
     */
    function mesExtenso($mes)
    {

        $meses = array(
            '01' => 'Janeiro',
            '02' => 'Fevereiro',
            '03' => 'Março',
            '04' => 'Abril',
            '05' => 'Maio',
            '06' => 'Junho',
            '07' => 'Julho',
            '08' => 'Agosto',
            '09' => 'Setembro',
            '10' => 'Outubro',
            '11' => 'Novembro',
            '12' => 'Dezembro'
        );

        return $meses[$mes];
    }
}

if (!function_exists('semanaMes')) {

    /**
     * @param $data
     * Função para retornar a semana do mês, exemplo: 27/12/1981 -> 4ª_semana do mês de Dezembro
     * @return null|string
     */
    function semanaMes($data)
    {

        $ano = substr($data, 6, 4);
        $mes = substr($data, 3, 2);
        $dia = substr($data, 0, 2);

        $NumDia = date("w", mktime(0, 0, 0, $mes, 1, $ano));
        $nDia = fmod($NumDia + 1, 7);
        $soma = 0;

        if (!$nDia == "1") {
            $soma = 2;
        }

        switch ($nDia) {
            case 2:
                $dia = $dia + 1 / 7;
                break;
            case 3:
                $dia = $dia + 1 / 7 * 2;
                break;
            case 4:
                $dia = $dia + 1 / 7 * 3;
                break;
            case 5:
                $dia = $dia + 1 / 7 * 4;
                break;
            case 6:
                $dia = $dia + 1 / 7 * 5;
                break;
            case 0:
                $dia = $dia + 1 / 7 * 6;
                break;
            default:
                $dia = 0;
                break;
        }

        $dia = ceil($dia + $soma);
        $semana = ceil($dia / 7);
        $descricao = null;

        switch ($semana) {
            case 1:
                $descricao = '1ª_semana do mês de ' . mesExtenso($mes);
                break;
            case 2:
                $descricao = '2ª_semana do mês de ' . mesExtenso($mes);
                break;
            case 3:
                $descricao = '3ª_semana do mês de ' . mesExtenso($mes);
                break;
            case 4:
                $descricao = '4ª_semana do mês de ' . mesExtenso($mes);
                break;
            case 5:
                $descricao = '5ª_semana do mês de ' . mesExtenso($mes);
                break;
            default:
                $descricao = '6ª_semana do mês de ' . mesExtenso($mes);
                break;
        }

        return $descricao;
    }
}

if (!function_exists('nomeDia')) :

    /**
     * @param $variavel
     * Função para retornar a descrição do dia, exemplo: 27/12/1981 -> Domingo
     * @return bool|mixed|string
     */
    function nomeDia($variavel)
    {
        $diasdasemana = array(
            1 => "Segunda-Feira",
            2 => "Terça-Feira",
            3 => "Quarta-Feira",
            4 => "Quinta-Feira",
            5 => "Sexta-Feira",
            6 => "Sábado",
            0 => "Domingo"
        );

        $variavel = str_replace('/', '-', $variavel);
        $hoje = getdate(strtotime($variavel));
        $diadasemana = $hoje["wday"];

        return $diasdasemana[$diadasemana];
    }

endif;

if (!function_exists('conversionR')) :

    function conversionR($number)
    {
        $unidades = array("um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove", "dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezessete", "dezoito", "dezenove");
        $dezenas = array("dez", "vinte", "trinta", "quarenta", "cinqüenta", "sessenta", "setenta", "oitenta", "noventa");
        $centenas = array("cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $milhares = array(
            array("text" => "mil", MyApp::START => 1000, MyApp::END => 999999, MyApp::DIV => 1000),
            array("text" => "milhão", MyApp::START => 1000000, MyApp::END => 1999999, MyApp::DIV => 1000000),
            array("text" => "milhões", MyApp::START => 2000000, MyApp::END => 999999999, MyApp::DIV => 1000000),
            array("text" => "bilhão", MyApp::START => 1000000000, MyApp::END => 1999999999, MyApp::DIV => 1000000000),
            array("text" => "bilhões", MyApp::START => 2000000000, MyApp::END => 2147483647, MyApp::DIV => 1000000000)
        );

        $value = calcValue($number, $unidades, $dezenas, $centenas);
        if (!$value) :
            foreach ($milhares as $item) :
                if ($number >= $item[MyApp::START] && $number <= $item['end']) :
                    $value = conversionR(floor($number / $item['div'])) . " " . $item['text'] . " " . conversionR($number % $item['div']);
                    break;
                endif;
            endforeach;
        endif;

        return $value;
    }
endif;

if (!function_exists('calcValue')) :
    /**
     * calcValue
     *
     * @param mixed $number
     * @param mixed $unidades
     * @param mixed $dezenas
     * @param mixed $centenas
     * @return void
     */
    function calcValue($number, $unidades, $dezenas, $centenas)
    {

        if (in_array($number, range(1, 19))) :
            $value = $unidades[$number - 1];
        elseif (in_array($number, range(20, 90, 10))) :
            $value = $dezenas[floor($number / 10) - 1];
        elseif (in_array($number, range(21, 99))) :
            $value = $dezenas[floor($number / 10) - 1] . " e " . conversionR($number % 10);
        elseif (in_array($number, range(100, 900, 100))) :
            $value = $centenas[floor($number / 100) - 1];
        elseif (in_array($number, range(101, 199))) :
            $value = 'cento e ' . conversionR($number % 100);
        elseif (in_array($number, range(201, 999))) :
            $value = $centenas[floor($number / 100) - 1] . " e " . conversionR($number % 100);
        else :
            $value = 0;
        endif;

        return $value;
    }
endif;

if (!function_exists('extractDecimals')) :

    /**
     * extractDecimals
     *
     * @param mixed $number
     * @return void
     */
    function extractDecimals($number)
    {
        return $number - floor($number);
    }

endif;

if (!function_exists('numberToExt')) :

    /**
     * numberToExt
     *
     * @param mixed $number
     * @return void
     */
    function numberToExt($number)
    {
        $min = 0.01;
        $max = 2147483647.99;
        $moeda = " real ";
        $moedas = " reais ";
        $centavo = " centavo ";
        $centavos = " centavos ";

        if ($number >= $min && $number <= $max) :
            $value = calcNumberExt($number, $moeda, $moedas);
            $decimals = extractDecimals($number);
            if ($decimals > 0.00) :
                $value = decimals($decimals, $value, $centavo, $centavos, $moeda);
            endif;
        endif;

        return trim($value);
    }

endif;

if (!function_exists('decimals')) :
    /**
     * decimals
     *
     * @param mixed $decimals
     * @param mixed $value
     * @param mixed $centavo
     * @param mixed $centavos
     * @param mixed $moeda
     * @return void
     */
    function decimals($decimals, $value, $centavo, $centavos, $moeda)
    {
        $decimals = round($decimals * 100);
        $value .= " e " . conversionR($decimals);
        if ($moeda) :
            if ($decimals == 1) :
                $value .= $centavo;
            elseif ($decimals > 1) :
                $value .= $centavos;
            endif;
        endif;

        return $value;
    }

endif;

if (!function_exists('calcNumberExt')) :
    /**
     * calcNumberExt
     *
     * @param mixed $number
     * @param mixed $moeda
     * @param mixed $moedas
     * @return void
     */
    function calcNumberExt($number, $moeda, $moedas)
    {
        $value = conversionR((int)$number);
        if ($moeda) :
            if (floor($number) == 1) :
                $value .= $moeda;
            elseif (floor($number) > 1) :
                $value .= $moedas;
            endif;
        endif;

        return $value;
    }

endif;


if (!function_exists('justNumber')) :

    /**
     * Retorna apenas os números informados na string.
     *
     * @param String $string
     * @author Bruno Cordeiro
     */
    function justNumber($string)
    {
        return preg_replace(MyApp::APENAS_NUMEROS, "", $string);
    }
endif;

if (!function_exists('getSaltToken')) :

    /**
     * getSaltToken
     *
     * @param mixed $length
     * @return void
     */
    function getSaltToken($length)
    {
        $salt = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789";
        $len = strlen($salt);
        $pass = '';
        mt_srand(10000000 * (float)microtime());

        for ($i = 0; $i < $length; $i++) :
            $pass .= $salt[random_int(0, $len - 1)];
        endfor;

        return $pass;
    }
endif;


if (!function_exists('getNumber')) :

    /**
     * gera número dinamico conforme o tamanho passado
     * Exemplo: $length = 10 vai gerar número aleatório até 10 posições, 5202914630
     */
    function getNumber($length)
    {
        $salt = "0123456789";
        $len = strlen($salt);
        $pass = '';
        mt_srand(10000000 * (float)microtime());

        for ($i = 0; $i < $length; $i++) :
            $pass .= $salt[random_int(0, $len - 1)];
        endfor;

        return (int)$pass;
    }
endif;

if (!function_exists('verificaCPFCNPJ')) :
    function verificaCPFCNPJ($valor)
    {
        $valor = preg_replace(MyApp::APENAS_NUMEROS, '', $valor);
        // Garante que o valor é uma string
        $valor = (string)$valor;
        // Verifica CPF
        if (strlen($valor) === 11) :
            return 'CPF';
        // Verifica CNPJ
        elseif (strlen($valor) === 14) :
            return 'CNPJ';
        // Não retorna nada
        else :
            return false;
        endif;
    }
endif;

if (!function_exists('validaCPF')) :
    function validaCPF($valor)
    {
        $valor = preg_replace(MyApp::APENAS_NUMEROS, '', $valor);
        // Garante que o valor é uma string
        $valor = (string)$valor;
        // Captura os 9 primeiros dígitos do CPF
        // Ex.: 02546288423 = 025462884
        $digitos = substr($valor, 0, 9);
        // Faz o cálculo dos 9 primeiros dígitos do CPF para obter o primeiro dígito
        $novo_cpf = calcDigitosPosicoes($digitos);
        // Faz o cálculo dos 10 dígitos do CPF para obter o último dígito
        $novo_cpf = calcDigitosPosicoes($novo_cpf, 11);
        // Verifica se o novo CPF gerado é idêntico ao CPF enviado
        if ($novo_cpf === $valor) :
            return true;
        endif;

        return false;
    }
endif;

if (!function_exists('validaCNPJ')) :
    function validaCNPJ($valor)
    {
        $valor = preg_replace(MyApp::APENAS_NUMEROS, '', $valor);
        // Garante que o valor é uma string
        $valor = (string)$valor;
        // O valor original
        $cnpj_original = $valor;
        // Captura os primeiros 12 números do CNPJ
        $primeiros_numeros_cnpj = substr($valor, 0, 12);
        // Faz o primeiro cálculo
        $primeiro_calculo = calcDigitosPosicoes($primeiros_numeros_cnpj, 5);
        // O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
        $segundo_calculo = calcDigitosPosicoes($primeiro_calculo, 6);
        // Concatena o segundo dígito ao CNPJ
        $cnpj = $segundo_calculo;
        // Verifica se o CNPJ gerado é idêntico ao enviado
        if ($cnpj === $cnpj_original) :
            return true;
        endif;
    }
endif;

if (!function_exists('calc_digitos_posicoes')) :
    function calcDigitosPosicoes($digitos, $posicoes = 10, $soma_digitos = 0)
    {
        // Faz a soma dos dígitos com a posição
        // Ex. para 10 posições:
        //   0    2    5    4    6    2    8    8   4
        // x10   x9   x8   x7   x6   x5   x4   x3  x2
        //   0 + 18 + 40 + 28 + 36 + 10 + 32 + 24 + 8 = 196
        for ($i = 0; $i < strlen($digitos); $i++) :
            // Preenche a soma com o dígito vezes a posição
            $soma_digitos = $soma_digitos + ($digitos[$i] * $posicoes);
            // Subtrai 1 da posição
            $posicoes--;
            // Parte específica para CNPJ
            // Ex.: 5-4-3-2-9-8-7-6-5-4-3-2
            if ($posicoes < 2) :
                // Retorno a posição para 9
                $posicoes = 9;
            endif;
        endfor;
        // Captura o resto da divisão entre $soma_digitos dividido por 11
        // Ex.: 196 % 11 = 9
        $soma_digitos = $soma_digitos % 11;
        // Verifica se $soma_digitos é menor que 2
        if ($soma_digitos < 2) :
            // $soma_digitos agora será zero
            $soma_digitos = 0;
        else :
            // Se for maior que 2, o resultado é 11 menos $soma_digitos
            // Ex.: 11 - 9 = 2
            // Nosso dígito procurado é 2
            $soma_digitos = 11 - $soma_digitos;
        endif;
        // Concatena mais um dígito aos primeiro nove dígitos
        // Ex.: 025462884 + 2 = 0254628842
        return $digitos . $soma_digitos;
    }
endif;

if (!function_exists('verificaSequencia')) :
    function verificaSequencia($multiplos, $valor)
    {
        $valor = preg_replace(MyApp::APENAS_NUMEROS, '', $valor);
        // Garante que o valor é uma string
        $valor = (string)$valor;
        // cpf
        for ($i = 0; $i < 10; $i++) :
            if (str_repeat($i, $multiplos) == $valor) :
                return false;
            endif;
        endfor;
        return true;
    }
endif;

if (!function_exists('valida')) :
    function valida($valor)
    {
        $valor = preg_replace(MyApp::APENAS_NUMEROS, '', $valor);
        // Garante que o valor é uma string
        $valor = (string)$valor;
        // Valida CPF
        if (verificaCPFCNPJ($valor) === 'CPF') :
            // Retorna true para cpf válido
            return validaCPF($valor) && verificaSequencia(11, $valor);
        // Valida CNPJ
        elseif (verificaCPFCNPJ($valor) === 'CNPJ') :
            // Retorna true para CNPJ válido
            return validaCNPJ($valor) && verificaSequencia(14, $valor);
        // Não retorna nada
        else :
            return false;
        endif;
    }
endif;

if (!function_exists('formata')) :
    function formata($valor)
    {
        $valor = preg_replace(MyApp::APENAS_NUMEROS, '', $valor);
        // Garante que o valor é uma string
        $valor = (string)$valor;

        // O valor formatado
        $formatado = false;
        // Valida CPF
        if (verificaCPFCNPJ($valor) === 'CPF') :
            // Verifica se o CPF é válido
            if (validaCPF($valor)) :
                // Formata o CPF ###.###.###-##
                $formatado = substr($valor, 0, 3) . '.';
                $formatado .= substr($valor, 3, 3) . '.';
                $formatado .= substr($valor, 6, 3) . '-';
                $formatado .= substr($valor, 9, 2) . '';
            endif;
        // Valida CNPJ
        elseif (verificaCPFCNPJ($valor) === 'CNPJ') :
            // Verifica se o CPF é válido
            if (validaCNPJ($valor)) :
                // Formata o CNPJ ##.###.###/####-##
                $formatado = substr($valor, 0, 2) . '.';
                $formatado .= substr($valor, 2, 3) . '.';
                $formatado .= substr($valor, 5, 3) . '/';
                $formatado .= substr($valor, 8, 4) . '-';
                $formatado .= substr($valor, 12, 14) . '';
            endif;
        endif;
        // Retorna o valor
        return $formatado;
    }
endif;

if (!function_exists('formatPhone')) :
    function formatPhone($phone)
    {
        if (!$phone):
            return '';
        endif;

        $formatedPhone = preg_replace(MyApp::APENAS_NUMEROS, '', $phone);
        $tam = strlen(preg_replace("/[^0-9]/", "", $formatedPhone));

        if ($tam == 13) : //COM CÓDIGO DE ÁREA NACIONAL E DO PAIS e 9 dígitos
            $phone = "+" . substr($formatedPhone, 0, $tam - 11) . "(" . substr($formatedPhone, $tam - 11, 2) . ")" . substr($formatedPhone, $tam - 9, 5) . "-" . substr($formatedPhone, -4);
        elseif ($tam == 12) : //COM CÓDIGO DE ÁREA NACIONAL E DO PAIS
            $phone = "+" . substr($formatedPhone, 0, $tam - 10) . "(" . substr($formatedPhone, $tam - 10, 2) . ")" . substr($formatedPhone, $tam - 8, 4) . "-" . substr($formatedPhone, -4);
        elseif ($tam == 11) : //COM CÓDIGO DE ÁREA NACIONAL e 9 dígitos
            $phone = "(" . substr($formatedPhone, 0, 2) . ")" . substr($formatedPhone, 2, 5) . "-" . substr($formatedPhone, 7, 11);
        elseif ($tam == 10) : //COM CÓDIGO DE ÁREA NACIONAL
            $phone = "(" . substr($formatedPhone, 0, 2) . ")" . substr($formatedPhone, 2, 4) . "-" . substr($formatedPhone, 6, 10);
        elseif ($tam <= 9) : //SEM CÓDIGO DE ÁREA
            $phone = substr($formatedPhone, 0, $tam - 4) . "-" . substr($formatedPhone, -4);
        endif;

        return $phone;
    }

endif;

if (!function_exists('mask')) :
    function mask($mask, $string)
    {
        $string = str_replace(" ", "", $string);

        for ($i = 0; $i < strlen($string); $i++) :
            $mask[strpos($mask, "#")] = $string[$i];
        endfor;

        return $mask;
    }

endif;

if (!function_exists('date2Db')) :
    function date2Db($data)
    {
        $dataFormat = null;
        if (isset($data) && $data) :
            $data = Carbon::createFromFormat('d/m/Y', $data);
            $dataFormat = $data->format('Y-m-d');
        endif;

        return $dataFormat;
    }

endif;

if (!function_exists('extensionFile')) :
    function extensionFile($arquivo)
    {
        return explode('/', explode(':', substr($arquivo, 0, strpos($arquivo, ';')))[1])[1];
    }
endif;

if (!function_exists('compara')) :
    function compara($a, $b)
    {
        return $b[MyApp::UNREAD] - $a[MyApp::UNREAD];
    }
endif;

if (!function_exists('slug')) :
    function slug($texto)
    {
        return cleanInput(utf8_strtr($texto));
    }
endif;

if (!function_exists('utf8_strtr')) :
    function utf8_strtr($str)
    {
        $from = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
        $to = "aaaaeeiooouucAAAAEEIOOOUUC";

        $keys = array();
        $values = array();
        preg_match_all('/./u', $from, $keys);
        preg_match_all('/./u', $to, $values);
        $mapping = array_combine($keys[0], $values[0]);
        return preg_replace('/[^a-zA-Z0-9_ -]/s', '', strtr($str, $mapping));
    }
endif;

if (!function_exists('treatValue')) :
    function treatValue($value, $type = '')
    {
        return $value ? $value : $type;
    }
endif;

if (!function_exists('treatValueBoolean')) :
    function treatValueBoolean($value)
    {
        return $value ? true : false;
    }
endif;
