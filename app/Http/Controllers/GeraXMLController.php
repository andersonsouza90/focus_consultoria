<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class GeraXMLController extends Controller
{
    public function parseXML($rray){

        //dd($rray);

        $diretorio = "uploads_arquivos/diretorio-".session('userAuth')['id_usuario']."/xml/";
        $retorno = [];

        $QuantidadeRps = count($rray) - 1;
        $chaveLoteRpsId = $this->v4();
        $dateNumeroLote = date('Ymd');
        $dom = new \DOMDocument();
		$dom->encoding = 'utf-8';
		$dom->xmlVersion = '1.0';
		$dom->formatOutput = true;

	    $xml_file_name = $diretorio.$dateNumeroLote.'.xml';

		$root = $dom->createElement('EnviarLoteRpsEnvio');
		$root = $dom->appendChild($root);
		$root->setAttributeNS('http://www.w3.org/2000/xmlns/' ,'xmlns:xsi', 'http://www.w3.org/2001/XMLSchema-instance');
		$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns:xsd', 'http://www.w3.org/2001/XMLSchema');
		$root->setAttributeNS('http://www.w3.org/2000/xmlns/', 'xmlns', 'http://www.abrasf.org.br/nfse.xsd');

		$nota_node = $dom->createElement('LoteRps');
		$attr_nota_id = new \DOMAttr('Id', $chaveLoteRpsId);
		$attr_nota_version = new \DOMAttr('versao', '2.01');
		$nota_node->setAttributeNode($attr_nota_id);
		$nota_node->setAttributeNode($attr_nota_version);

        $child_node_nroLote= $dom->createElement('NumeroLote', $dateNumeroLote);
	    $nota_node->appendChild($child_node_nroLote);

        $child_node_cpfCnpj = $dom->createElement('CpfCnpj');
	    $nota_node->appendChild($child_node_cpfCnpj);
	    $child_node_cpfCnpjnumero = $dom->createElement('Cnpj',$rray[1][array_search('PrestadorCNPJ', $rray[0])]);
	    $child_node_cpfCnpj->appendChild($child_node_cpfCnpjnumero);
        $nota_node->appendChild($child_node_cpfCnpj);

        $child_node_insc = $dom->createElement('InscricaoMunicipal', $rray[1][array_search('InscricaoMunicipal', $rray[0])]);
		$nota_node->appendChild($child_node_insc);

	    $child_node_qtdRps = $dom->createElement('QuantidadeRps', $QuantidadeRps);
		$nota_node->appendChild($child_node_qtdRps);

		$child_node_ListaRps = $dom->createElement('ListaRps');
		$nota_node->appendChild($child_node_ListaRps);

        $root->appendChild($nota_node);
        $dom->appendChild($root);

        foreach ($rray as $key => $value) {

            if($rray[$key][array_search('PrestadorCNPJ', $rray[0])] != "PrestadorCNPJ"){

                $InscricaoMunicipal = $value[$this->getIndiceArray($rray[0])['InscricaoMunicipal']];
                $PrestadorCNPJ = str_pad($value[$this->getIndiceArray($rray[0])['PrestadorCNPJ']] , 14 , '0' , STR_PAD_LEFT);

                $CpfCnpjTomador = str_pad($value[$this->getIndiceArray($rray[0])['CpfCnpjTomador']] , 11 , '0' , STR_PAD_LEFT);
                $Aliquota = $value[$this->getIndiceArray($rray[0])['Aliquota']];
                if($value[$this->getIndiceArray($rray[0])['Aliquota']] == ''){
                    $Aliquota='0.00';
                }

                $ValorIss = $value[$this->getIndiceArray($rray[0])['ValorIss']];
                if($value[$this->getIndiceArray($rray[0])['ValorIss']] ==''){
                    $ValorIss='0.00';
                }

                $chaveInfId     = $this->v4();
                $chaveTagRpsId  = $this->v4();

                $child_node_Rps = $dom->createElement('Rps');
                $child_node_ListaRps->appendChild($child_node_Rps);

                $child_node_nfDeclaracao = $dom->createElement('InfDeclaracaoPrestacaoServico');
                $attr_nota_infId = new \DOMAttr('Id', $chaveInfId);
                $child_node_nfDeclaracao->setAttributeNode($attr_nota_infId);
                $child_node_Rps->appendChild($child_node_nfDeclaracao);


                /***** TAG segunda Rps *********/
                $child_node_tagRps = $dom->createElement('Rps');
                $attr_nota_rpsId = new \DOMAttr('Id', $chaveTagRpsId);
                $child_node_tagRps->setAttributeNode($attr_nota_rpsId);
                $child_node_nfDeclaracao->appendChild($child_node_tagRps);

                $child_node_IdentificacaoRps = $dom->createElement('IdentificacaoRps');
                $child_node_tagRps->appendChild($child_node_IdentificacaoRps);

                $child_node_Numero = $dom->createElement('Numero', $value[$this->getIndiceArray($rray[0])['NumeroRPS']]);  //NUMERO DA RPS
                $child_node_IdentificacaoRps->appendChild($child_node_Numero);

                $child_node_NFE = $dom->createElement('Serie','NFE');
                $child_node_IdentificacaoRps->appendChild($child_node_NFE);

                $child_node_Tipo = $dom->createElement('Tipo','1');
                $child_node_IdentificacaoRps->appendChild($child_node_Tipo);

                $child_node_DataEmissao = $dom->createElement('DataEmissao', $this->converte_data($value[$this->getIndiceArray($rray[0])['DataEmissao']]));
                $child_node_tagRps->appendChild($child_node_DataEmissao);

                $child_node_Status = $dom->createElement('Status','1');
                $child_node_tagRps->appendChild($child_node_Status);
                /***** TAG segunda Rps *********/

                $child_node_Competencia = $dom->createElement('Competencia', $this->converte_data($value[$this->getIndiceArray($rray[0])['Competencia']]));
                $child_node_nfDeclaracao->appendChild($child_node_Competencia);

                /***** TAG Servico *********/
                $child_node_Servico = $dom->createElement('Servico');
                $child_node_nfDeclaracao->appendChild($child_node_Servico);

                $child_node_Valores = $dom->createElement('Valores');
                $child_node_Servico->appendChild($child_node_Valores);

                $child_node_ValorServicos = $dom->createElement('ValorServicos', $value[$this->getIndiceArray($rray[0])['ValorServicos']]);
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

                $child_node_Discriminacao= $dom->createElement('Discriminacao', $this->concatenaDiscriminacao(
                                                                        $value[$this->getIndiceArray($rray[0])['Descricao']],
                                                                        $value[$this->getIndiceArray($rray[0])['Descricao1']],
                                                                        $value[$this->getIndiceArray($rray[0])['NumeroDoContrato']],
                                                                        $value[$this->getIndiceArray($rray[0])['CategoriaFinanceiro']],
                                                                        $value[$this->getIndiceArray($rray[0])['NumParcela']],
                                                                        $value[$this->getIndiceArray($rray[0])['StatusDoContrato']]
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

                $child_node_Tomador= $dom->createElement('Tomador');
                $child_node_nfDeclaracao->appendChild($child_node_Tomador);

                $child_node_IdentificacaoTomador= $dom->createElement('IdentificacaoTomador');
                $child_node_Tomador->appendChild($child_node_IdentificacaoTomador);

                $child_node_CpfCnpjTomador= $dom->createElement('CpfCnpj');
                $child_node_IdentificacaoTomador->appendChild($child_node_CpfCnpjTomador);

                $child_node_CpfTomador= $dom->createElement('Cpf',$CpfCnpjTomador);
                $child_node_CpfCnpjTomador->appendChild($child_node_CpfTomador);


                $child_node_RazaoSocial= $dom->createElement('RazaoSocial',$value[$this->getIndiceArray($rray[0])['RazaoSocialTomador']]);
                $child_node_Tomador->appendChild($child_node_RazaoSocial);


                $child_node_EnderecoTomadorTag= $dom->createElement('Endereco');
                $child_node_Tomador->appendChild($child_node_EnderecoTomadorTag);
                $child_node_EnderecoTomador= $dom->createElement('Endereco',$value[$this->getIndiceArray($rray[0])['EnderecoTomador']]);
                $child_node_EnderecoTomadorTag->appendChild($child_node_EnderecoTomador);

                $child_node_NumeroEnderecoTomador= $dom->createElement('Numero',$value[$this->getIndiceArray($rray[0])['NumeroEnderecoTomador']]);
                $child_node_EnderecoTomadorTag->appendChild($child_node_NumeroEnderecoTomador);

                $child_node_ComplementoEnderecoTomador= $dom->createElement('Complemento',$value[$this->getIndiceArray($rray[0])['ComplementoEnderecoTomador']] . 'A');
                $child_node_EnderecoTomadorTag->appendChild($child_node_ComplementoEnderecoTomador);

                $child_node_BairroTomador= $dom->createElement('Bairro',$value[$this->getIndiceArray($rray[0])['BairroTomador']]);
                $child_node_EnderecoTomadorTag->appendChild($child_node_BairroTomador);

                //$child_node_CidadeTomador= $dom->createElement('CodigoMunicipio',$value['CidadeTomador']);
                $child_node_CidadeTomador= $dom->createElement('CodigoMunicipio', $value[$this->getIndiceArray($rray[0])['CodigoMunicipioTomador']]);
                $child_node_EnderecoTomadorTag->appendChild($child_node_CidadeTomador);



                $child_node_UFTomador= $dom->createElement('Uf',$value[$this->getIndiceArray($rray[0])['UFSiglaTomador']]);
                $child_node_EnderecoTomadorTag->appendChild($child_node_UFTomador);

                $child_node_CEPTomador= $dom->createElement('Cep', str_replace('-', '', $value[$this->getIndiceArray($rray[0])['CEPTomador']]));
                $child_node_EnderecoTomadorTag->appendChild($child_node_CEPTomador);




                $child_node_ContatoTomador= $dom->createElement('Contato');
                $child_node_Tomador->appendChild($child_node_ContatoTomador);

                $child_node_EmailTomador= $dom->createElement('Email',$value[$this->getIndiceArray($rray[0])['EmailTomador']]);
                $child_node_ContatoTomador->appendChild($child_node_EmailTomador);

                /***** TAG Prestador *********/

                $child_node_RegimeEspecialTributacao= $dom->createElement('RegimeEspecialTributacao','6');
                $child_node_nfDeclaracao->appendChild($child_node_RegimeEspecialTributacao);

                $child_node_OptanteSimplesNacional= $dom->createElement('OptanteSimplesNacional','1');
                $child_node_nfDeclaracao->appendChild($child_node_OptanteSimplesNacional);

                $child_node_IncentivoFiscal= $dom->createElement('IncentivoFiscal','2');
                $child_node_nfDeclaracao->appendChild($child_node_IncentivoFiscal);



            }

        }

        //dd($dom-saveXML());

        Storage::disk('local')->put($xml_file_name, $dom->saveXML());

        $retorno['xml_file_name'] = $dateNumeroLote.'.xml';
        $retorno['QuantidadeRps'] = $QuantidadeRps;

        return $retorno;
    }

    function getIndiceArray($array){

        $i = [];

        $i['InscricaoMunicipal']    = array_search('InscricaoMunicipal', $array);
        $i['PrestadorCNPJ']         = array_search('PrestadorCNPJ', $array);
        $i['CpfCnpjTomador']        = array_search('CpfCnpjTomador', $array);
        $i['Aliquota']              = array_search('Aliquota', $array);
        $i['ValorIss']              = array_search('ValorIss', $array);
        $i['NumeroRPS']             = array_search('NumeroRPS', $array);
        $i['DataEmissao']           = array_search('DataEmissao', $array);
        $i['Competencia']           = array_search('Competencia', $array);
        $i['ValorServicos']         = array_search('ValorServicos', $array);
        $i['Descricao']             = array_search('Descricao', $array);
        $i['Descricao1']            = array_search('Descricao1', $array);
        $i['NumeroDoContrato']      = array_search('NumeroDoContrato', $array);
        $i['CategoriaFinanceiro']   = array_search('CategoriaFinanceiro', $array);
        $i['NumParcela']            = array_search('NumParcela', $array);
        $i['StatusDoContrato']      = array_search('StatusDoContrato', $array);

        $i['RazaoSocialTomador']      = array_search('RazaoSocialTomador', $array);
        $i['EnderecoTomador']      = array_search('EnderecoTomador', $array);
        $i['NumeroEnderecoTomador']      = array_search('NumeroEnderecoTomador', $array);
        $i['ComplementoEnderecoTomador']      = array_search('ComplementoEnderecoTomador', $array);
        $i['BairroTomador']      = array_search('BairroTomador', $array);
        $i['CodigoMunicipioTomador']      = array_search('CodigoMunicipioTomador', $array);
        $i['UFSiglaTomador']      = array_search('UFSiglaTomador', $array);
        $i['CEPTomador']      = array_search('CEPTomador', $array);
        $i['EmailTomador']      = array_search('EmailTomador', $array);

        return $i;

    }

    function v4() {
        return sprintf('%04x%04x-%04x-%04x-%04x-%04x%04x%04x',

          // 32 bits for "time_low"
          mt_rand(0, 0xffff), mt_rand(0, 0xffff),

          // 16 bits for "time_mid"
          mt_rand(0, 0xffff),

          // 16 bits for "time_hi_and_version",
          // four most significant bits holds version number 4
          mt_rand(0, 0x0fff) | 0x4000,

          // 16 bits, 8 bits for "clk_seq_hi_res",
          // 8 bits for "clk_seq_low",
          // two most significant bits holds zero and one for variant DCE1.1
          mt_rand(0, 0x3fff) | 0x8000,

          // 48 bits for "node"
          mt_rand(0, 0xffff), mt_rand(0, 0xffff), mt_rand(0, 0xffff)
        );
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
	  if($this->valida_data($data)) {
	    return implode(!strstr($data, '/') ? "/" : "-", array_reverse(explode(!strstr($data, '/') ? "-" : "/", $data)));
	  }
	}

    function concatenaDiscriminacao($descricao, $descricao1, $numeroDoContrato, $categoriaFinanceiro, $numParcelas, $statusContrato){

		return $descricao .' '.
			   $descricao1 . ' ' .
			   $numeroDoContrato . ' ' .
			   $categoriaFinanceiro . ' ' .
			   $numParcelas . ' ' .
			   $statusContrato;


	}




}
