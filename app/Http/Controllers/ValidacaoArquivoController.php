<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Arr;

class ValidacaoArquivoController extends Controller
{
    public function validaColunas($assoc_array){

        //dd($assoc_array[0]);

		$msg = [];
		$countErro = 0;
		$erro = array();

		if(count($assoc_array[0]) > 1){

			//foreach ($assoc_array[0] as $key => $value) {
                $value = $assoc_array[0];
				$renomer = "Renomear conforme texto";

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

			//}

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

    public function validarDados($assoc_array){

        $l = 0;
        $m = [];
        $retorno = [];

        foreach ($assoc_array as $key => $dadoValidar) {

            if($assoc_array[$key][array_search('PrestadorCNPJ', $assoc_array[0])] != "PrestadorCNPJ"){

                $PrestadorCNPJ = str_pad($dadoValidar[array_search('PrestadorCNPJ', $assoc_array[0])] , 14 , '0' , STR_PAD_LEFT);
                if(!$this->validaCNPJ($PrestadorCNPJ)){
                    array_push($m, "CNPJ inválido ".$PrestadorCNPJ." {PrestadorCNPJ} linha ". ($key+2) );
                    $l++;

                    // var_dump(array_search('PrestadorCNPJ', $assoc_array[0]));
                    // dd($dadoValidar);
                    // dd($dadoValidar[array_search('PrestadorCNPJ', $assoc_array[0])]);
                }

                $InscricaoMunicipal = $dadoValidar[array_search('InscricaoMunicipal', $assoc_array[0])];

                if(!$this->dados($dadoValidar[array_search('InscricaoMunicipal', $assoc_array[0])])){
                    array_push($m, "Dados inválido ".$InscricaoMunicipal." {InscricaoMunicipal} linha ".($key+2) );
                    $l++;
                }

                $CpfCnpjTomador = str_pad($dadoValidar[array_search('CpfCnpjTomador', $assoc_array[0])] , 11 , '0' , STR_PAD_LEFT);
                if(!$this->validaCPF($CpfCnpjTomador)){
                    array_push($m, "CPF inválido ".$CpfCnpjTomador." {CpfCnpjTomador} linha ".($key+2) );
                    $l++;
                }

                $DataEmissao = $dadoValidar[array_search('DataEmissao', $assoc_array[0])];
                //$DataEmissao = $this->converte_data($DataEmissao);

                if(!$this->valida_data($DataEmissao)){
                    array_push($m, "Data inválida ".$DataEmissao." {DataEmissao} linha ".($key+2)." Padrão (Ano)-(Mẽs)-(Dia)");
                    $l++;
                }

                $ValorIss = $dadoValidar[array_search('ValorIss', $assoc_array[0])];
                //$ValorIss = $this->dados($ValorIss);
                if(!$this->dados($ValorIss)){
                    array_push($m, "Dados inválido ".$ValorIss." {ValorIss} linha ".($key+2)." Padrão 0.00");
                    $l++;
                }

                $Aliquota = $dadoValidar[array_search('Aliquota', $assoc_array[0])];
                //$Aliquota = $this->dados($Aliquota);
                if(!$this->dados($Aliquota)){
                    array_push($m, "Dados inválido ".$Aliquota." {Aliquota} linha ".($key+2)." Padrão 0.00");
                    $l++;
                }


                $Competencia = $dadoValidar[array_search('Competencia', $assoc_array[0])];
                //$Competencia = $this->converte_data($Competencia);

                if(!$this->valida_data($Competencia)){
                    array_push($m, "Data inválida ".$Competencia." {Competencia} linha ".($key+1)." Padrão (Ano)-(Mẽs)-(Dia)");
                    $l++;
                }

            }

        }

        $retorno['msg'] = $m;
        $retorno['countErro'] = $l;

        return $retorno;

    }
}
