<?php
include "function.php";
include "toxml.php";
include "validacao.php";
include "exceltoarray.php";
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Import Excel To Mysql Database Using PHP </title>
		<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<meta name="description" content="XML">

		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/bootstrap-responsive.min.css">
		<link rel="stylesheet" href="css/bootstrap-custom.css">


	</head>
	<body>    

	<!-- Navbar
    ================================================== -->

	<div class="navbar navbar-inverse navbar-fixed-top">
		<div class="navbar-inner">
			<div class="container"> 
				<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
					<span class="icon-bar"></span>
				</a>
				<a class="brand" href="index.php">VOLTAR</a>
				
			</div>
		</div>
	</div>

	<div id="wrap">
	<div class="container">
		<div class="row">
			<div class="span3 hidden-phone"></div>
			<?php

				if(isset($_POST["Import"])){

				    //echo $_FILES["file"]['type'];

					if ($_FILES['file']['error'] != 0) {
						echo "<h4>Não foi possível fazer o upload, erro: " . $_UP['erros'][$_FILES['file']['error']]."</h4>";
						exit;
					}
                    
                    /*
					if($_FILES["file"]['type'] != 'text/csv'){
						echo "<h4>Não foi possível fazer o upload, erro: " . $_UP['erros'][5]."</h4>";
						exit; 
					}*/

					if ($_UP['tamanho'] < $_FILES['file']['size']) {
					    echo "<h4>O arquivo enviado é muito grande, envie arquivos de até 2Mb.<h4/>";
					    exit;
					}

					if ($_UP['renomeia'] == true) {
					  	$nome_final = md5(time()).'-'.$_UP['date'].'.xlsx';
					} else {
					  	$nome_final = $_FILES['file']['name'];
					}

					if (move_uploaded_file($_FILES['file']['tmp_name'], $_UP['pasta'] . $nome_final)) {
					  	echo "<h4>Upload efetuado com sucesso!<br/></h4>";
					  	echo '<h4><a href="' . $_UP['pasta'] . $nome_final . '" target="_blank">Clique aqui para acessar o arquivo importado .xlsx</a></h4>';
					} else {
					  	echo "<h4>Não foi possível enviar o arquivo, tente novamente</h4>";
					}


					if($_FILES["file"]["size"] > 0)
					{
	                    //echo '<pre>';

	                    /*
						$assoc_array = [];
						if (($handle = fopen($_UP['pasta'] . $nome_final, "r")) !== false) {           
							if (($data = fgetcsv($handle, 1000, ",")) !== false) { 						    	
							    $keys = $data;  						                                                  
							}
							while (($data = fgetcsv($handle, 1000, ",")) !== false) {     
							    $assoc_array[] = array_combine($keys, $data);             
							}
						    fclose($handle);                                               
						}
						*/

                        $filePath = $_UP['pasta'] . $nome_final;
						$assoc_array = excelToArray($filePath, $header=true);
		
						if(count($assoc_array)==0){
							echo '<h4 style="color:red">Arquivo Vazio</h4>';
							exit;
						}

                        //validacao
                        $return = validacao($assoc_array);
                        //print_r($return);
                        if($return['countErro']>0){
					       echo $return['msg'];
					       exit;
					    }

			            $l = 0;
			            $m ="";
					    foreach ($assoc_array as $key => $dadoValidar) {
							$PrestadorCNPJ = str_pad($dadoValidar['PrestadorCNPJ'] , 14 , '0' , STR_PAD_LEFT);
                            if(!validaCNPJ($PrestadorCNPJ)){
                                $m .= "<h5>CNPJ inválido ".$PrestadorCNPJ." {PrestadorCNPJ} linha ".($key+2)."</h5>";
                                $l++;
                            } 
							
							$InscricaoMunicipal = ($dadoValidar['InscricaoMunicipal']); 
                            $InscricaoMunicipal = dados($InscricaoMunicipal);
                            if(!dados($InscricaoMunicipal)){
                                $m .= "<h5>Dados inválido ".$InscricaoMunicipal." {InscricaoMunicipal} linha ".($key+2)."</h5>";
                                $l++;
                            }
							
					    	$CpfCnpjTomador = str_pad($dadoValidar['CpfCnpjTomador'] , 11 , '0' , STR_PAD_LEFT);
                            if(!validaCPF($CpfCnpjTomador)){
                                $m .= "<h5>CPF inválido ".$CpfCnpjTomador." {CpfCnpjTomador} linha ".($key+2)."</h5>";
                                $l++;
                            }  

                            $DataEmissao = ($dadoValidar['DataEmissao']);                             
                            $DataEmissao = converte_data($DataEmissao);

                            if(!valida_data($DataEmissao)){
                                $m .= "<h5>Data inválida ".$DataEmissao." {DataEmissao} linha ".($key+2)." Padrão (Ano)-(Mẽs)-(Dia)</h5>";
                                $l++;
                            } 

                            $ValorIss = ($dadoValidar['ValorIss']); 
                            $ValorIss = dados($ValorIss);
                            if(!dados($ValorIss)){
                                $m .= "<h5>Dados inválido ".$ValorIss." {ValorIss} linha ".($key+2)." Padrão 0.00</h5>";
                                $l++;
                            } 

                            $Aliquota = ($dadoValidar['Aliquota']); 
                            $Aliquota = dados($Aliquota);
                            if(!dados($Aliquota)){
                                $m .= "<h5>Dados inválido ".$Aliquota." {Aliquota} linha ".($key+2)." Padrão 0.00</h5>";
                                $l++;
                            } 

                            
                            $Competencia = ($dadoValidar['Competencia']);                        
                            $Competencia = converte_data($Competencia);
                            
                            if(!valida_data($Competencia)){
                                $m .= "<h5>Data inválida ".$Competencia." {Competencia} linha ".($key+1)." Padrão (Ano)-(Mẽs)-(Dia)</h5>";
                                $l++;
                            }
                                                                       
					    }

					    if($l>0){
					    	echo '<h4 style="color:red">Corrigir os erros no Documento</h4>';
					    	echo $m;
					    	exit;
					    }
	                    
	                    $qtdRPS = count($assoc_array);
					    if($return['countErro']==0){
							$xml = parseXml($assoc_array);
							echo '<h4><a href="' . $xml  . '" target="_blank">Documento XML gerado, Total de RPS '.$qtdRPS.'</a></h4>';

                            echo '<br/>';

							echo '<h4><a href="http://nfse.vitoria.es.gov.br/" target="_blank">Validador de arquivos RPS (no formato XML)</a></h4>';

					    }

					}
				}	 
			?>	
		
			<div class="span3 hidden-phone"></div>
		</div>

	</div>
	</div>
	</body>
</html>	 