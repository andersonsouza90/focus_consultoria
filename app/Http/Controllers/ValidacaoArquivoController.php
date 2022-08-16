<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ValidacaoArquivoController extends Controller
{
    public function validacao($assoc_array){

		$msg = [];
		$countErro = 0;
		$erro = array();

		if(count($assoc_array) > 1){

			foreach ($assoc_array as $key => $value) {
				$renomer = "Renomear conforme texto";

                // echo '<pre>';
				// print_r($value);
                // dd(in_array("NumeroRPS", $value));
                // die;

				if(!in_array("NumeroRPS", $value)){
					//$msg .= "Não existe a coluna {NumeroRPS} - ". $renomer ." 'NumeroRPS';</li>";
					array_push($msg, "Não existe a coluna {NumeroRPS} - ". $renomer ." 'NumeroRPS';");
                    $countErro++;
				}

				if(!in_array("DataEmissao", $value)){
					//$msg .= "Não existe a coluna {DataEmissao} - ". $renomer ." 'DataEmissao';";
                    array_push($msg, "Não existe a coluna {DataEmissao} - ". $renomer ." 'DataEmissao';");
					$countErro++;
				}

				if(!in_array("Competencia", $value)){
					array_push($msg, "Não existe a coluna {Competencia} - ". $renomer ." 'Competencia';");
					$countErro++;
				}

				if(!in_array("ValorServicos", $value)){
					array_push($msg, "Não existe a coluna {ValorServicos} - ". $renomer ." 'ValorServicos';");
					$countErro++;
				}

				if(!in_array("ValorIss", $value)){
					array_push($msg, "Não existe a coluna {ValorIss} - ". $renomer ." 'ValorIss';");
					$countErro++;
				}

				if(!in_array("Aliquota", $value)){
					array_push($msg, "Não existe a coluna {Aliquota} - ". $renomer ." 'Aliquota';");
					$countErro++;
				}

				if(!in_array("RazaoSocialTomador", $value)){
					array_push($msg, "Não existe a coluna {RazaoSocialTomador} - ". $renomer ." 'RazaoSocialTomador';");
					$countErro++;
				}

				if(!in_array("CpfCnpjTomador", $value)){
					array_push($msg, "Não existe a coluna {CpfCnpjTomador} - ". $renomer ." 'CpfCnpjTomador';");
					$countErro++;
				}

				//if(!array_key_exists("Discriminacao", $value)){
				//	$msg .= "<h5>Não existe a coluna [08]{Discriminacao} - ". $renomer ." 'Discriminacao';</h5>";
				//	$countErro++;
				//}

	            $erro['msg']=$msg;
	            $erro['countErro']=$countErro;

	            return $erro;
			}

	    }

	    $erro['msg']=$msg;
	    $erro['countErro']=$countErro;

	    return $erro;

	}

    function validaCNPJ($cnpj){

		$cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);

		// Valida tamanho
		if (strlen($cnpj) != 14)
			return false;

		// Verifica se todos os digitos são iguais
		if (preg_match('/(\d)\1{13}/', $cnpj))
			return false;

		// Valida primeiro dígito verificador
		for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++)
		{
			$soma += $cnpj{$i} * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}

		$resto = $soma % 11;

		if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
			return false;

		// Valida segundo dígito verificador
		for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++)
		{
			$soma += $cnpj{$i} * $j;
			$j = ($j == 2) ? 9 : $j - 1;
		}

		$resto = $soma % 11;

		return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);

	}

	function validaCPF($cpf) {

	    // Extrai somente os números
	    $cpf = preg_replace( '/[^0-9]/is', '', $cpf );

	    // Verifica se foi informado todos os digitos corretamente
	    if (strlen($cpf) != 11) {
	        return false;
	    }
	    // Verifica se foi informada uma sequência de digitos repetidos. Ex: 111.111.111-11
	    if (preg_match('/(\d)\1{10}/', $cpf)) {
	        return false;
	    }
	    // Faz o calculo para validar o CPF
	    for ($t = 9; $t < 11; $t++) {
	        for ($d = 0, $c = 0; $c < $t; $c++) {
	            $d += $cpf{$c} * (($t + 1) - $c);
	        }
	        $d = ((10 * $d) % 11) % 10;
	        if ($cpf{$c} != $d) {
	            return false;
	        }
	    }
	    return true;
	}


	function valida_data($data)
	{
		if( strstr($data,"-")){
		  $data = explode('-', $data);
		}else{
		  $data = explode('/', $data);
		}

		$data0 = (int) $data[0];
		$data1 = (int) $data[1];
		$data2 = (int) $data[2];

		if(!checkdate($data1, $data0, $data2) and !checkdate($data1, $data2, $data0)) {
		  return false;
		}

		//if(!checkdate($data[1], $data[0], $data[2]) and !checkdate($data[1], $data[2], $data[0])) {
		  //return false;
		//}

		return true;
	}

	function converte_data($data){
	  if(valida_data($data)) {
	    return implode(!strstr($data, '/') ? "/" : "-", array_reverse(explode(!strstr($data, '/') ? "-" : "/", $data)));
	  }
	}

	function dados($dado){
	  $r = false;
	  if($dado==''){
         $r = false;
	  }else{
         $r = true;
	  }
	  return $r;
	}
}
