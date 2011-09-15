<?php
require_once "comum/util/geracaoArquivo.class.php";
require_once "comum/util/package_utils.class.php";

class GeracaoArquivoPDF extends geracaoArquivo {
    var $texto_arq;
    var $extensao;
    var $usuario;
    var $_path_host;
    var $origemRelat;

    function GeracaoArquivoPDF($texto, $origem = "") {
        GLOBAL $SISCONF;
        GLOBAL $SGU_INTERNET;

        if ($origem == "") {
            $origem = "SIS";
        }
        $this->clearProperties();

        $this->origemRelat = $origem;
        $this->texto_arq = $texto;

        //if ($this->origemRelat == "PORTAL_PROF") {
        $this->_default_path = $SISCONF[$origem]['RELATORIO_PDF']['PATH'];
        $this->_path_host = $SISCONF[$origem]['RELATORIO_PDF']['HOST'];

        //}else{
        //  $this->_default_path = $SISCONF['SIS']['RELATORIO_PDF']['PATH'];
        //  $this->_path_host = $SISCONF['SIS']['RELATORIO_PDF']['HOST'];
        //}

        $this->usuario = $SISCONF['SESSAO']['USUARIO']['USUARIO'];
    }

    function clearProperties() {
        $this->texto_arq = "";
        $this->extensao = "pdf";
        $this->_path_host = "";
        $this->usuario = "";
    }

    function makeFileName() {
        GLOBAL $SISCONF;

        $this->_file = DATE("YmdHis").$this->usuario.".".$this->extensao;
        $this->filename = $this->_default_path.$this->_file;
    }

    function geraArquivo($retornaCaminho=false) {

        $this->makeFileName();
        $this->openFile();
        $this->writeFile($this->texto_arq);
        $this->closeFile();

        if ($retornaCaminho==false)
        {
            
			echo "<SCRIPT LANGUAGE='javascript'>
			window.open('".$this->_path_host.$this->_file."','','');
			";
			$browser_cliente = isset($_SERVER['HTTP_USER_AGENT']) ? $_SERVER['HTTP_USER_AGENT'] : '';
			if(strpos($browser_cliente, 'MSIE') !== false)
			{
			echo "document.write('Voce ja pode fechar esta janela');";
			} 
			else {
			echo "window.close();";
			}
			
			echo "</SCRIPT>";
			
        }
        else
        {
            return $this->_path_host.$this->_file;
        }
    }

    function cabecalhoAtestado(&$pdf, &$linha) {
        GLOBAL $SISCONF;

        $coluna = 87;
        $local = $SISCONF['SIS']['PATH_IMG_CAB_PADRAO'];
        $pdf->addJpegFromFile($local."logoOficial_PDF.jpg", $coluna, ($linha-40),420);
    }

    function cabecalhoPadrao(&$pdf, $Titulo1, $titulo1_size, $Titulo2, $titulo2_size, $Titulo3, $titulo3_size, &$linha, $pag, $tipo="", $cabUNI=TRUE, $data="", $hora="", $largQuadro=0, $colInicio=0, $exibeSGLData=true) {
        GLOBAL $SGU_INTERNET;
        GLOBAL $SISCONF;

        if ($this->origemRelat == "PORTAL_PROF") {
            /*if ($SGU_INTERNET == "PORTAL_PROF_INTRA") {
                $local = "/usr/local/apache/htdocs/portalprof/imagens/";
            }else{
                $local = "/netdb/www/internet/sistemas/sgu/portalprof/imagens/";
            }*/
            $local = $SISCONF['PORTAL_PROF']['PATH_IMG'];
        }else{
            #$local = "/netdb/www/intranet/sistemas/sgu/portal/modulos/temas/imagens/";
           // $local = $SISCONF['SIS']['PATH_IMG_CAB_PADRAO'];
			$local = $SISCONF['SIS']['PATH_IMG_CAB_PADRAO'].$_SESSION['bancoDeDados']."/";
        }
        //echo "<BR>$local<BR>";
        //$pdf->addJpegFromFile($local."UNILASALLE.jpg",27,$linha+20,200);
        $coluna = 15;
        if ( (int)$colInicio > 0 ) $coluna = $colInicio;

        if ($cabUNI==true)
        {
            $pdf->addJpegFromFile($local."unilasalle_pb_pq.jpg", $coluna+12, ($linha+20), 200);
        }
        else
        {
            $pdf->addJpegFromFile($local."colegio-pb.jpg", $coluna+12, ($linha+15), 200);
        }
        $pdf->setFontFamily("Times-Roman.afm");
        $pdf->setLineStyle(0.5);
        if($tipo == "landscape"){
            $pdf->line(15,$linha+60,825,$linha+60);//___
            $pdf->line(240,$linha+20,825,$linha+20);//___
            $pdf->line(15,$linha+5,825,$linha+5);//___
            $pdf->line(15,$linha+60,15,$linha+5);
            $pdf->line(240,$linha+60,240,$linha+5);
            $pdf->line(825,$linha+60,825,$linha+5);// |
        } else {
            $tamQuadro = 565;
            if ( $largQuadro > 0 ) $tamQuadro = $largQuadro;

            $coluna = 15;
            if ( (int)$colInicio > 0 ) $coluna = $colInicio;

            #$pdf->line(15, $linha+60, $tamQuadro, $linha+60);
            $pdf->line($coluna, $linha+60, $tamQuadro, $linha+60);
            #$pdf->line(240, $linha+20, $tamQuadro, $linha+20);
            $pdf->line($coluna+225, $linha+20, $tamQuadro, $linha+20);
            #$pdf->line(15, $linha+5, $tamQuadro, $linha+5);
            $pdf->line($coluna, $linha+5, $tamQuadro, $linha+5);
            #$pdf->line(15, $linha+60, 15, $linha+5);
            $pdf->line($coluna, $linha+60, $coluna, $linha+5);
            #$pdf->line(240, $linha+60, 240, $linha+5);
            $pdf->line($coluna+225, $linha+60, $coluna+225, $linha+5);

            $pdf->line($tamQuadro, $linha+60, $tamQuadro, $linha+5);
        }

        $linha -= 10;

        if ($titulo1_size == 0) {$titulo1_size = 11;}
        if ($titulo2_size == 0) {$titulo2_size = 9;}
        if ($titulo3_size == 0) {$titulo3_size = 9;}

        $pdf->setFontFamily("Times-Roman.afm");
        $pdf->addText($coluna+230,$linha+55,$titulo1_size,"<b>".$Titulo1."</b>",0);
        $pdf->addText($coluna+230,$linha+45,$titulo2_size,"<b>".$Titulo2."</b>",0);
        $pdf->addText($coluna+230,$linha+35,$titulo3_size,"<b>".$Titulo3."</b>",0);

		if ($exibeSGLData) {
	        $pdf->addText($coluna+230,$linha+20,9,"SGL",0);
			
	        $dataImp = date('d/m/Y H:i');
	        if ( (trim($data) != "") || (trim($hora) != "") ) {
	            $dataImp = $data." ".$hora;
	        }
			
	        $pdf->addText(355,$linha+20,9,$dataImp,0);
		}
			
        $pdf->addText($tamQuadro-45, $linha+20, 9, "Pág: ".$this->zerosLeft($pag, 4), 0);
    }

    function rodapeRelatorio(&$pdf, $query, $linha = 30, $largPagina = 580, $estId = 2) {
        # busca os dados do estabelecimento para imprimir o rodapé da página
        $endereco = "";
        $cep = "";
        $cidade = "";
        $cnpj = "";
        $fone = "";
        $fax = "";
        $site = "";
        $tamanhoFonte = 8;

        if ( $estId != "" ) {
            $sql = "select initcap(E.EST_ENDERECO) as EST_ENDERECO, E.EST_CEP, C.CID_NOME, C.CID_EST".
                "\n     , E.EST_CNPJ, E.EST_FONE, E.EST_FAX, lower(E.EST_SITE) as EST_SITE".
                "\n from ESTABELECIMENTO E, CIDADES C".
                "\n where E.EST_ID = ".$estId.
                "\n and E.CID_ID = C.CID_ID";
            $query->TQuery($sql);
            if ( $row = $query->fetchrow() ) {
                $endereco = $row["EST_ENDERECO"];
                $cep = substr($row["EST_CEP"], 0, 5)."-".substr($row["EST_CEP"],5,3);
                $cidade = $row["CID_NOME"]."/".$row["CID_EST"];
                $cnpj = $row["EST_CNPJ"];
                $cnpj = substr($cnpj,0,2).".".substr($cnpj,2,3).".".substr($cnpj,5,3)."/".substr($cnpj,8,4)."-".substr($cnpj,12,2);
                $fone = $row["EST_FONE"];
                $fax = $row["EST_FAX"];
                $site = $row["EST_SITE"];
            }
        }

        $texto = $endereco." - ".$cep." - ".$cidade." - CNPJ ".$cnpj." - Fone: ".$fone;
		if (trim($fax) != ""){
			$texto .= " - Fax ".$fax;
		}
		if (trim($site) != ""){
			$texto .= " - ".$site;
		}

        $tamTexto = $pdf->getTextWidth($tamanhoFonte, $texto);
        $posicao = ($largPagina - $tamTexto) / 2;

        $pdf->addText($posicao, $linha, $tamanhoFonte, $texto);
    }

}
?>