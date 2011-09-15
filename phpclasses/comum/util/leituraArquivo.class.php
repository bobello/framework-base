<?php
//require_once _PATH_SIS . "comum/tela/package_message.class.php";
//require_once _PATH_SIS . "comum/sessao/configsis.inc.php";
//require_once _PATH_SIS . "comum/util/package_utils.class.php";
require_once "comum/util/geracaoArquivo.class.php";

//classe que possui metodos de manipulacao de arquivos
class leituraArquivo extends geracaoArquivo
{

	var $fileName; //Nome do arquivo importado
	var $filePath; //Path do arquivo importado
	var $dataFile; //Dados do arquivo importado
	var $defaultName;
	var $pagina_link;
	var $_POSTED;
	var $conn;
	
	var $erro;


	//metodo construtor da classe 
	function leituraArquivo($requests = array())
	{
		GLOBAL $SISCONF;
		$this->clearProperties();
		$this->filePath = $SISCONF['SIS']['PATH_ARQ_LEITORA_GRD_RESPOSTA'];
		$this->_POSTED = $requests;
	}
	
	//metodo que seta a conexao
	function setConnection($DB)
	{
		$this->conn = $DB;
	}
	
	//metodo limpa as propriedades
	function clearProperties()
	{
		$this->fileName = "";
		$this->filePath = "";
		$this->dataFile = array();
	}
	
	//metodo que abre o arquivo
	function open($nomeArquivo)
	{
		$this->fileName = $nomeArquivo;
		$this->dataFile = file($this->fileName);
	}
	
	//metodo que faz um mid 
	function mid($lineString, $posIni = 0, $posFin = 0)
	{
		$part = substr($lineString, $posIni, ($posFin - $posIni + 1));
		return $part;
	}
	
	//metodo que lista os arquivos
	function listFilesToImport()
	{
		$diret = $this->filePath;
		$trab = 0;
		$font = "<font color=\"#C1DEF7\" size=\"2\" face=\"Arial, Helvetica, sans-serif\">";
		$font1 = "<font color=\"#C1DEF7\" size=\"4\" face=\"Arial, Helvetica, sans-serif\">";
		$font2 = "<font size=\"2\" face=\"Arial, Helvetica, sans-serif\">";
		$font3 = "<font color=\"BLUE\" size=\"2\" face=\"Arial, Helvetica, sans-serif\">";
		$cell = "bgColor=#C1DEF7";
		echo "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=1 width=400 bgcolor=\"#97A49D\"><TR><TD>";
		echo "<TABLE BORDER=0 CELLPADDING=0 CELLSPACING=0 width=400 bgcolor=\"#97A49D\">";
		echo "<TR><TD COLSPAN=3 Align=CENTER>$font1<B><font size=4 face=\"Arial,Tahoma\">Lista de Arquivos</B>
	 	<A HREF=\"" . $this->pagina_link . "\"><BR>Atualizar (" . $this->filePath . ")</A></FONT>
		 </TD></TR>";
		$semtrab = "<TR><TD COLSPAN=3 $cell ALIGN=CENTER><b>$font2 Nenhum arquivo no diretório de importação. </font></b></TD></TR>";
		$file = $this->defaultName . ".txt";
		$fl = $diret . $file;
		
		if (file_exists($fl)){
			echo "<TR><TD VALIGN=TOP ALIGN=CENTER COLSPAN=3 $cell>";
			echo "<BR>";
			echo "$font3 Importar: <A HREF=\"" . $this->pagina_link . "&import=$file\">$file</A></font>";
			echo "<BR>";
			echo "<BR>";
			echo "</TD></TR>";
		}else{
			echo $semtrab;
		}
		echo "</TABLE>";
		echo "</TD></TR></TABLE>";
	}
	
	
	//metodo que busca os valores
	function getValue($valorString, $decimalPlaces = 2, $decimalSeparator = ".")
	{
		$l = strlen($valorString);
		$cent = $this->mid($valorString, 0, ($l - $decimalPlaces-1));
		$dec = $this->mid($valorString, ($l - $decimalPlaces), ($l-1));
		$value = $cent . $decimalSeparator . $dec;
		$nvalue = (float) $value;
	 return $nvalue;
	}
	
	//metodo que limpa uma string
	function clearString($stringValue)
	{
		$stringValue = str_replace("'", "´", $stringValue);
		$stringValue = str_replace("\\", " ", $stringValue);
		$stringValue = str_replace("\"", " ", $stringValue);
		return $stringValue;
	}
	
	//metodo que retorna a data
	function getDate(&$strDateAAAAMMDD, &$srtError, $format = 'IPOSTGRES')
	{
		$strDateAAAAMMDD = trim($strDateAAAAMMDD);
		$les = strlen($strDateAAAAMMDD);
		$y = "0000";
		$m = "00";
		$d = "00";
		switch($les){
		case 8:
		 	$y = $this->mid($strDateAAAAMMDD, 0, 3);
			$m = $this->mid($strDateAAAAMMDD, 4, 5);
			$d = $this->mid($strDateAAAAMMDD, 6, 7);
			break;
		case 6:
			$y = $this->mid($strDateAAAAMMDD, 0, 1);
			$m = $this->mid($strDateAAAAMMDD, 2, 3);
			$d = $this->mid($strDateAAAAMMDD, 4, 5);
			break;
		default:
			break;
		} // switch
	
		if ($format == 'IPOSTGRES'){
			$strDateAAAAMMDD = $m . "/" . $d . "/" . $y;
		}else{
			$strDateAAAAMMDD = $d . "/" . $m . "/" . $y;
		}
		
		if (checkdate($m, $d, $y) == true){
			$srtError = "";
			return true;
		}else{
			$srtError = "Data Inválida: $strDateAAAAMMDD";
			return false;
		}
	}

	//metodo que seta a msg
	function mensagem($mensagem){
		$msg = new message($mensagem, INFORMATION);
		echo "$msg";
	}
 
}

class layoutArquivoRetorno extends leituraArquivo
{

	//objeto layout
	var $layout; 
	
	//metodo constructo da classe
	function layoutArquivoRetorno()
	{
		$this->leituraArquivo();
		$this->pagina_link = "content.php?acao=impc";
		$this->defaultName = "Cl1";
	}
	
	//metodo que valida o layout do arquivo
	function validaLayout()
	{
		$layout = new layoutST_040($this->dataFile);
		return $layout->isLayout();	
	}
	
	//metodo que retorna os dados do arquivo
	function retornaDadosBanco()
	{
		$dados = array();
		$layout = new layoutST_040($this->dataFile);
		$dados = $layout->retornaDadosCamposLayout();		
		return $dados;
	}	
	
	//metodo que retorna o banco
	function retornaBanco(){
		$layout = new layoutST_040($this->dataFile);
		return $layout->retornaBanco();		
	}
	
	//metodo que retorna o layout do arquivo
	function retornaLayout(){
		$layout = new layoutST_040($this->dataFile);
		return $layout->retornaVersaoLayout();
	}
}
