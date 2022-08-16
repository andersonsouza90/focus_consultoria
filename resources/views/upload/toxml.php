<?php

    function parseXml($rray){

	//echo '<pre>';
	//print_r($rray);
	//echo '</pre>';

    $QuantidadeRps = count($rray);	

    $chaveLoteRpsId = v4();

    $dateNumeroLote = date('Ymd');

	$dom = new DOMDocument();

		$dom->encoding = 'utf-8';
		$dom->xmlVersion = '1.0';
		$dom->formatOutput = true;

	    $xml_file_name = 'uploads/'.$dateNumeroLote.'.xml';

		$root = $dom->createElement('EnviarLoteRpsEnvio');
		$root = $dom->appendChild($root);
		$root->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
		$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'http://www.abrasf.org.br/nfse.xsd');

		$nota_node = $dom->createElement('LoteRps');
		$attr_nota_id = new DOMAttr('Id', $chaveLoteRpsId);
		$attr_nota_version = new DOMAttr('versao', '2.01');
		$nota_node->setAttributeNode($attr_nota_id);
		$nota_node->setAttributeNode($attr_nota_version);


	    $child_node_nroLote= $dom->createElement('NumeroLote', $dateNumeroLote);
	    $nota_node->appendChild($child_node_nroLote);
		
	    $child_node_cpfCnpj = $dom->createElement('CpfCnpj');
	    $nota_node->appendChild($child_node_cpfCnpj);
	    $child_node_cpfCnpjnumero = $dom->createElement('Cnpj',$rray[0]['PrestadorCNPJ']);
	    $child_node_cpfCnpj->appendChild($child_node_cpfCnpjnumero);
        $nota_node->appendChild($child_node_cpfCnpj);

		$child_node_insc = $dom->createElement('InscricaoMunicipal', $rray[0]['InscricaoMunicipal']);
		$nota_node->appendChild($child_node_insc);

	    $child_node_qtdRps = $dom->createElement('QuantidadeRps', $QuantidadeRps);
		$nota_node->appendChild($child_node_qtdRps);

		$child_node_ListaRps = $dom->createElement('ListaRps');
		$nota_node->appendChild($child_node_ListaRps);


       foreach ($rray as $value) {
		         
		    $InscricaoMunicipal = $value['InscricaoMunicipal'];
			
			$PrestadorCNPJ = str_pad($value['PrestadorCNPJ'] , 14 , '0' , STR_PAD_LEFT);

         	$CpfCnpjTomador = str_pad($value['CpfCnpjTomador'] , 11 , '0' , STR_PAD_LEFT);
            $Aliquota = $value['Aliquota'];
         	if($value['Aliquota']==''){
         		$Aliquota='0.00';
         	}

         	$ValorIss = $value['ValorIss'];
         	if($value['ValorIss']==''){
         		$ValorIss='0.00';
         	}

       	    $chaveInfId     = v4();
            $chaveTagRpsId  = v4();

	        $child_node_Rps = $dom->createElement('Rps');
	        $child_node_ListaRps->appendChild($child_node_Rps);

	        $child_node_nfDeclaracao = $dom->createElement('InfDeclaracaoPrestacaoServico');
	        $attr_nota_infId = new DOMAttr('Id', $chaveInfId);
	        $child_node_nfDeclaracao->setAttributeNode($attr_nota_infId);
	        $child_node_Rps->appendChild($child_node_nfDeclaracao);

	        /***** TAG segunda Rps *********/
	        $child_node_tagRps = $dom->createElement('Rps');
	        $attr_nota_rpsId = new DOMAttr('Id', $chaveTagRpsId);
	        $child_node_tagRps->setAttributeNode($attr_nota_rpsId);
	        $child_node_nfDeclaracao->appendChild($child_node_tagRps);

	        $child_node_IdentificacaoRps = $dom->createElement('IdentificacaoRps');
	        $child_node_tagRps->appendChild($child_node_IdentificacaoRps);

	        $child_node_Numero = $dom->createElement('Numero', $value['NumeroRPS'] );  //NUMERO DA RPS 
	        $child_node_IdentificacaoRps->appendChild($child_node_Numero);

	        $child_node_NFE = $dom->createElement('Serie','NFE');
	        $child_node_IdentificacaoRps->appendChild($child_node_NFE);

	        $child_node_Tipo = $dom->createElement('Tipo','1');
	        $child_node_IdentificacaoRps->appendChild($child_node_Tipo);

	        $child_node_DataEmissao = $dom->createElement('DataEmissao', converte_data($value['DataEmissao']));
	        $child_node_tagRps->appendChild($child_node_DataEmissao);

	        $child_node_Status = $dom->createElement('Status','1');
	        $child_node_tagRps->appendChild($child_node_Status);
	        /***** TAG segunda Rps *********/

	        $child_node_Competencia = $dom->createElement('Competencia', converte_data($value['Competencia']));
	        //$child_node_Competencia = $dom->createElement('Competencia', $value['Competencia']);
	        $child_node_nfDeclaracao->appendChild($child_node_Competencia);

	        /***** TAG Servico *********/
	        $child_node_Servico = $dom->createElement('Servico');
	        $child_node_nfDeclaracao->appendChild($child_node_Servico);

	        $child_node_Valores = $dom->createElement('Valores');
	        $child_node_Servico->appendChild($child_node_Valores);

	        $child_node_ValorServicos = $dom->createElement('ValorServicos', $value['ValorServicos']);
	        $child_node_Valores->appendChild($child_node_ValorServicos);

	        $child_node_ValorDeducoes = $dom->createElement('ValorDeducoes', '0.00');
	        $child_node_Valores->appendChild($child_node_ValorDeducoes);

	        $child_node_ValorPis = $dom->createElement('ValorPis', '0.00');
	        $child_node_Valores->appendChild($child_node_ValorPis);

	        $child_node_ValorCofins = $dom->createElement('ValorCofins', '0.00');
	        $child_node_Valores->appendChild($child_node_ValorCofins);

	        $child_node_ValorInss = $dom->createElement('ValorInss', '0.00');
	        $child_node_Valores->appendChild($child_node_ValorInss);

	        $child_node_ValorIr = $dom->createElement('ValorIr', '0.00');
	        $child_node_Valores->appendChild($child_node_ValorIr);

	        $child_node_ValorCsll = $dom->createElement('ValorCsll', '0.00');
	        $child_node_Valores->appendChild($child_node_ValorCsll);

	        $child_node_OutrasRetencoes= $dom->createElement('OutrasRetencoes', '0.00');
	        $child_node_Valores->appendChild($child_node_OutrasRetencoes);

	        $child_node_ValorIss= $dom->createElement('ValorIss', $ValorIss);
	        $child_node_Valores->appendChild($child_node_ValorIss);

	        $child_node_Aliquota= $dom->createElement('Aliquota', $Aliquota);
	        $child_node_Valores->appendChild($child_node_Aliquota);

	        $child_node_DescontoIncondicionado= $dom->createElement('DescontoIncondicionado', '0.00');
	        $child_node_Valores->appendChild($child_node_DescontoIncondicionado);

	        $child_node_DescontoCondicionado= $dom->createElement('DescontoCondicionado', '0.00');
	        $child_node_Valores->appendChild($child_node_DescontoCondicionado);

	        $child_node_IssRetido= $dom->createElement('IssRetido', '2');
	        $child_node_Servico->appendChild($child_node_IssRetido);

	        $child_node_ItemListaServico= $dom->createElement('ItemListaServico', '8.02');
	        $child_node_Servico->appendChild($child_node_ItemListaServico);

	        $child_node_CodigoCnae= $dom->createElement('CodigoCnae', '8593700');
	        $child_node_Servico->appendChild($child_node_CodigoCnae);

	        $child_node_Discriminacao= $dom->createElement('Discriminacao', concatenaDiscriminacao(
	        																		$value['Descricao'],
	        																		$value['Descricao1'],
	        																		$value['NumeroDoContrato'],
	        																		$value['CategoriaFinanceiro'],
	        																		$value['NumParcela'],
	        																		$value['StatusDoContrato']
	        																	));
	        $child_node_Servico->appendChild($child_node_Discriminacao);


	        $child_node_CodigoMunicipio= $dom->createElement('CodigoMunicipio', '3205309');
	        $child_node_Servico->appendChild($child_node_CodigoMunicipio);

	        $child_node_ExigibilidadeISS= $dom->createElement('ExigibilidadeISS', '1');
	        $child_node_Servico->appendChild($child_node_ExigibilidadeISS);

	        $child_node_MunicipioIncidencia= $dom->createElement('MunicipioIncidencia', '3205309');
	        $child_node_Servico->appendChild($child_node_MunicipioIncidencia);
	        /***** TAG Servico *********/

	        /***** TAG Prestador *********/
	        $child_node_Prestador= $dom->createElement('Prestador');
	        $child_node_nfDeclaracao->appendChild($child_node_Prestador);

	        $child_node_PrestadorCpfCnpj= $dom->createElement('CpfCnpj');
	        $child_node_Prestador->appendChild($child_node_PrestadorCpfCnpj);

	        $child_node_PrestadorCnpj= $dom->createElement('Cnpj',$PrestadorCNPJ);
	        $child_node_PrestadorCpfCnpj->appendChild($child_node_PrestadorCnpj);

	        $child_node_InscricaoMunicipal= $dom->createElement('InscricaoMunicipal',$InscricaoMunicipal);
	        $child_node_Prestador->appendChild($child_node_InscricaoMunicipal);
	        /***** TAG Prestador *********/

	        /***** TAG Prestador *********/



	        $child_node_Tomador= $dom->createElement('Tomador');
	        $child_node_nfDeclaracao->appendChild($child_node_Tomador);

	        $child_node_IdentificacaoTomador= $dom->createElement('IdentificacaoTomador');
	        $child_node_Tomador->appendChild($child_node_IdentificacaoTomador);

	        $child_node_CpfCnpjTomador= $dom->createElement('CpfCnpj');
	        $child_node_IdentificacaoTomador->appendChild($child_node_CpfCnpjTomador);

	        $child_node_CpfTomador= $dom->createElement('Cpf',$CpfCnpjTomador);
	        $child_node_CpfCnpjTomador->appendChild($child_node_CpfTomador);


	        $child_node_RazaoSocial= $dom->createElement('RazaoSocial',$value['RazaoSocialTomador']);
	        $child_node_Tomador->appendChild($child_node_RazaoSocial);


	        $child_node_EnderecoTomadorTag= $dom->createElement('Endereco');
	        $child_node_Tomador->appendChild($child_node_EnderecoTomadorTag);
	        $child_node_EnderecoTomador= $dom->createElement('Endereco',$value['EnderecoTomador']);
	        $child_node_EnderecoTomadorTag->appendChild($child_node_EnderecoTomador);

	        $child_node_NumeroEnderecoTomador= $dom->createElement('Numero',$value['NumeroEnderecoTomador']);
	        $child_node_EnderecoTomadorTag->appendChild($child_node_NumeroEnderecoTomador);

	        $child_node_ComplementoEnderecoTomador= $dom->createElement('Complemento',$value['ComplementoEnderecoTomador'] . 'A');
	        $child_node_EnderecoTomadorTag->appendChild($child_node_ComplementoEnderecoTomador);

	        $child_node_BairroTomador= $dom->createElement('Bairro',$value['BairroTomador']);
	        $child_node_EnderecoTomadorTag->appendChild($child_node_BairroTomador);

	        //$child_node_CidadeTomador= $dom->createElement('CodigoMunicipio',$value['CidadeTomador']);
	        $child_node_CidadeTomador= $dom->createElement('CodigoMunicipio', $value['CodigoMunicipioTomador']);
	        $child_node_EnderecoTomadorTag->appendChild($child_node_CidadeTomador);

	        

	        $child_node_UFTomador= $dom->createElement('Uf',$value['UFSiglaTomador']);
	        $child_node_EnderecoTomadorTag->appendChild($child_node_UFTomador);

	        $child_node_CEPTomador= $dom->createElement('Cep', str_replace('-', '', $value['CEPTomador']));
	        $child_node_EnderecoTomadorTag->appendChild($child_node_CEPTomador);




	        $child_node_ContatoTomador= $dom->createElement('Contato');
	        $child_node_Tomador->appendChild($child_node_ContatoTomador);

	        $child_node_EmailTomador= $dom->createElement('Email',$value['EmailTomador']);
	        $child_node_ContatoTomador->appendChild($child_node_EmailTomador);



	        
	        

	        /***** TAG Prestador *********/

	        $child_node_RegimeEspecialTributacao= $dom->createElement('RegimeEspecialTributacao','6');
	        $child_node_nfDeclaracao->appendChild($child_node_RegimeEspecialTributacao);

	        $child_node_OptanteSimplesNacional= $dom->createElement('OptanteSimplesNacional','1');
	        $child_node_nfDeclaracao->appendChild($child_node_OptanteSimplesNacional);

	        $child_node_IncentivoFiscal= $dom->createElement('IncentivoFiscal','2');
	        $child_node_nfDeclaracao->appendChild($child_node_IncentivoFiscal);

        }

		$root->appendChild($nota_node);
		$dom->appendChild($root);
	    $dom->save($xml_file_name);


	    return $xml_file_name;

	}

	function concatenaDiscriminacao($descricao, $descricao1, $numeroDoContrato, $categoriaFinanceiro, $numParcelas, $statusContrato){

		return $descricao .' '.
			   $descricao1 . ' ' .
			   $numeroDoContrato . ' ' .
			   $categoriaFinanceiro . ' ' .
			   $numParcelas . ' ' .
			   $statusContrato;	


	}


	
?>
