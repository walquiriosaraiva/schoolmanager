<?php

if (!function_exists('valida')) :
    function valida($valor)
    {
        $valor = preg_replace('/[^0-9]/', '', $valor);
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

if (!function_exists('verifica_cpf_cnpj')) :
    function verificaCPFCNPJ($valor)
    {
        $valor = preg_replace('/[^0-9]/', '', $valor);
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

if (!function_exists('formata')) :
    function formata($valor)
    {
        $valor = preg_replace('/[^0-9]/', '', $valor);
        // Garante que o valor é uma string
        $valor = (string)$valor;

        // O valor formatado
        $formatado = false;
        // Valida CPF
        if (verificaCPFCNPJ($valor) === 'CPF') {
            // Verifica se o CPF é válido
            if (validaCPF($valor)) {
                // Formata o CPF ###.###.###-##
                $formatado = substr($valor, 0, 3) . '.';
                $formatado .= substr($valor, 3, 3) . '.';
                $formatado .= substr($valor, 6, 3) . '-';
                $formatado .= substr($valor, 9, 2) . '';
            }
        } // Valida CNPJ
        elseif (verificaCPFCNPJ($valor) === 'CNPJ') {
            // Verifica se o CPF é válido
            if (validaCNPJ($valor)) {
                // Formata o CNPJ ##.###.###/####-##
                $formatado = substr($valor, 0, 2) . '.';
                $formatado .= substr($valor, 2, 3) . '.';
                $formatado .= substr($valor, 5, 3) . '/';
                $formatado .= substr($valor, 8, 4) . '-';
                $formatado .= substr($valor, 12, 14) . '';
            }
        }
        // Retorna o valor
        return $formatado;
    }
endif;

if (!function_exists('verifica_sequencia')) :
    function verificaSequencia($multiplos, $valor)
    {
        $valor = preg_replace('/[^0-9]/', '', $valor);
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

if (!function_exists('valida_cnpj')) :
    function validaCNPJ($valor)
    {
        $valor = preg_replace('/[^0-9]/', '', $valor);
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

if (!function_exists('valida_cpf')) :
    function validaCPF($valor)
    {
        $valor = preg_replace('/[^0-9]/', '', $valor);
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
            // CPF válido
            return true;
        else :
            // CPF inválido
            return false;
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
        $cpf = $digitos . $soma_digitos;
        // Retorna
        return $cpf;
    }
endif;
