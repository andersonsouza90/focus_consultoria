  <?php

	function validacao($assoc_array){

		$msg = "";
		$countErro = 0;	
		$erro = array();
	
		if(count($assoc_array)>0){

			foreach ($assoc_array as $key => $value) {
				$renomer = "Renomear conforme texto";

                //echo '<pre>';
				//print_r($value);

				if(!array_key_exists("NumeroRPS", $value)){
					$msg .= "<h5>Não existe a coluna [01]{NumeroRPS} - ". $renomer ." 'NumeroRPS';</h5>";
					$countErro++;
				}

				if(!array_key_exists("DataEmissao", $value)){
					$msg .= "<h5>Não existe a coluna [02]{DataEmissao} - ". $renomer ." 'DataEmissao';</h5>";
					$countErro++;
				}

				if(!array_key_exists("Competencia", $value)){
					$msg .= "<h5>Não existe a coluna [03]{Competencia} - ". $renomer ." 'Competencia';</h5>";
					$countErro++;
				}

				if(!array_key_exists("ValorServicos", $value)){
					$msg .= "<h5>Não existe a coluna [04]{ValorServicos} - ". $renomer ." 'ValorServicos';</h5>";
					$countErro++;
				}

				if(!array_key_exists("ValorIss", $value)){	
					$msg .= "<h5>Não existe a coluna [05]{ValorIss} - ". $renomer ." 'ValorIss';</h5>";
					$countErro++;
				}	

				if(!array_key_exists("Aliquota", $value)){	
					$msg .= "<h5>Não existe a coluna [05]{Aliquota} - ". $renomer ." 'Aliquota';</h5>";
					$countErro++;
				}

				if(!array_key_exists("RazaoSocialTomador", $value)){	
					$msg .= "<h5>Não existe a coluna [06]{RazaoSocialTomador} - ". $renomer ." 'RazaoSocialTomador';</h5>";
					$countErro++;
				}

				if(!array_key_exists("CpfCnpjTomador", $value)){	
					$msg .= "<h5>Não existe a coluna [07]{CpfCnpjTomador} - ". $renomer ." 'CpfCnpjTomador';</h5>";
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


	?>