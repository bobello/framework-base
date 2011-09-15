<?php

//OBSOLETO
class debug{function debug($type = '', $obj = null){}} //fim da classe debug

//OBSOLETO
class debug_class{function debug_class($type = '', $print = true){}
}

//class ext_debug_class extends debug{
class ext_debug_class{
// tipo de variaveis 
    // $_GLOBALS
    // Contém uma referência para todas as variáveis disponíveis dentro do escopo global do script.
    // $_SERVER
    // Variáveis criadas pelo servidor web ou diretamente relacionadas ao ambiente de execução do script atual.
    // $_GET
    // Variáveis postadas para o script via método HTTP GET.
    // $_POST
    // Variáveis postadas para o script via método HTTP POST.
    // $_COOKIE
    // Variáveis postadas para o script via cookies HTTP.
    // $_FILES
    // Variáveis postadas para o script via transferência de arquivos HTTP.
    // $_ENV
    // Variáveis disponíveis no script do ambiente de execução.
    // $_REQUEST
    // Variáveis postadas para o script por todas os mecanismos de input,
    // e que não podem ter seu conteúdo garantido de qualquer forma.
    // $_SESSION
    // Variáveis que estão atualmente registradas na sessão do script.
    var $type;
    var $result_debug;
    var $nomeClasse;
    var $level;
    var $color;
	var $border;
	var $elements;

// metodo constructor
	function ext_debug_class($level = 0){
		$this->elements = array();
		$this->border = 1;
		$this->level = $level;
		$this->color = array(" BGCOLOR=#F5FBD9 "," BGCOLOR=#FAF9A0 "," BGCOLOR=#3AA8D0");

		switch($level){
			case 1: 
				$this->color = array(" BGCOLOR=#CDF8D0 "," BGCOLOR=#C4FA92 "," BGCOLOR=#3AA8D0");
				break;
			case 2: 
				$this->color = array(" BGCOLOR=#ACE4F9 "," BGCOLOR=#ACE9B8 "," BGCOLOR=#AEB57D");
				break;
			case 3: 
				$this->color = array(" BGCOLOR=#CDF8D0 "," BGCOLOR=#C4FA92 "," BGCOLOR=#3AA8D0");
				break;
			case 4: 
				$this->color = array(" BGCOLOR=#CDF8D0 "," BGCOLOR=#C4FA92 "," BGCOLOR=#3AA8D0");
				break;
		}
	}
	
	function debug($type, $print = true){
	
        //ATENÇÃO -- DEBUG FOI DESABILITADO POR DEFAULT NO SERVIDOR DE PRODUÇÃO
        //Em 07/12/2004 - Poupar recursos do servidor durante a matrícula
        //Utilizado retorno da classe para otimização do uso de memória do servidor de produção
		//return null;  //   Retorno sem nadas fazer

		GLOBAL $SISCONF;
		
		if (is_object($type)){
			if (@is_a($type->conn,'genericdb')){
				if (class_exists('genericquery')){
					if (($type->conn->nomeBD == 'ORACLE') &&
						($type->conn->conOK)){
						$g = new genericQuery($type->conn);
						$sql = "SELECT FNDATABASE() AS DB, USER AS USR FROM DUAL";
						$g->TQuery($sql, false);
						if ($r = $g->fetchrow()){
							$this->addToDebug($r['DB'],'Banco em Acesso');
							$this->addToDebug($r['USR'],'Usuário em Acesso');
						}
					}
				}
			}
		}

		$debuguser = false;

		for ($i=0; $i < count($SISCONF['SIS']['DEBUG_IP']); $i++){
			if ($SISCONF['SIS']['DEBUG_IP'][$i]==$SISCONF['SESSAO']['USUARIO']['IP']) {
				$debuguser = true;
			}
		}
		
		if (($debuguser == true)&&($SISCONF['SIS']['DEBUG'] == true)){
		
			$this->type = get_object_vars($type);
			$this->nomeClasse = get_class($type);

			$this->result_debug = "<TABLE BORDER=".$this->border." ".$this->color[2]." CELLSPACING=0 CELLPADDING=3 class='naoVisivelImpressao'>";

			$this->result_debug .= "<TR><TD class=noclass VALIGN=TOP COLSPAN=2><B>OBJECT : " . $this->nomeClasse . "<B></TD></TR>
				<TR><TD class=noclass align=LEFT  VALIGN=TOP><B>ATRIBUTO</B></TD><TD class=noclass align=LEFT  VALIGN=TOP><B>VALOR</B></TD></TR>";
				
			$i = 0;

			if (is_array($this->type)) {
				foreach ($this->type as $chave => $valor) {
					if (is_array($valor)) {
						$this->debugArray($chave,$valor);	
					}elseif (is_object($valor)){
						$this->result_debug .= "<TR><TD class=noclass align=LEFT  VALIGN=TOP WIDTH=100  ".$this->color[0].">$chave (obj)</TD>";
						$nd = new ext_debug_class(($this->level + 1));
						$tmp = $nd->debug($valor, false);
						$this->result_debug .= "<TD class=noclass align=LEFT  VALIGN=TOP WIDTH=80%  ".$this->color[1].">$tmp</TD></TR>";
						$nd = null;
					}else{
						$this->listElement($chave, $valor);
					}
					$i++;
				}
			}
			
			if ($print) {
				if (is_array($SISCONF['SIS']['QUERIES'])){
					foreach (array_keys($SISCONF['SIS']['QUERIES']) as $v){
						$qy = "<PRE>".$SISCONF['SIS']['QUERIES'][$v]."</PRE>";
						$this->listElement("Query-".$v, $qy);
						$i++;
					}
				}
			}


			if (count($this->elements)>0) {
				$this->result_debug .= "</TABLE><BR>";
				
				$this->result_debug .= "<TABLE BORDER=".$this->border." ".$this->color[2]." CELLSPACING=0 CELLPADDING=3 class='naoVisivelImpressao'>";
				$this->result_debug .= "<TR><TD class=noclass VALIGN=TOP COLSPAN=2><B>Elementos adicionais<B></TD></TR>
					<TR><TD class=noclass align=LEFT  VALIGN=TOP><B>ATRIBUTO</B></TD><TD class=noclass align=LEFT  VALIGN=TOP><B>VALOR</B></TD></TR>";

				foreach ($this->elements as $chave => $valor) {
					if (is_array($valor)) {
						$this->debugArray($chave,$valor);	
					}elseif (is_object($valor)){
						$this->result_debug .= "<TR><TD class=noclass align=LEFT  VALIGN=TOP WIDTH='100'  ".$this->color[0].">$chave (obj)</TD>";
						$nd = new ext_debug_class();
						$tmp = $nd->debug($valor, false);
						$this->result_debug .= "<TD class=noclass align=LEFT  VALIGN=TOP WIDTH=80%  ".$this->color[1]."> $tmp</TD></TR>";
						$nd = null;
					}else{
						$this->listElement($chave, $valor);
					}
					$i++;
				}
			}
			
			$this->result_debug .= "</TABLE>";
			if ($print) {
			 	echo "<BR><BR>".$this->result_debug;   
			}else{
				return $this->result_debug; 
			}
		}
	}
	
	
	function debugArray($key, $array){
		if (is_array($array)) {
			$this->result_debug .= "\n<TR><TD class=noclass align=LEFT  VALIGN=TOP ".$this->color[0].">$key (array)</TD>";
			$this->result_debug .= "<TD class=noclass align=LEFT  VALIGN=TOP ".$this->color[2].">";
			$this->result_debug .= "\n<TABLE BORDER=".$this->border." CELLSPACING=0 CELLPADDING=3>";
			foreach ($array as $key => $value) {
				if (is_array($value)) {
					$this->debugArray($key,$value);	
				}elseif (is_object($value)){
					$this->result_debug .= "<TR><TD class=noclass align=LEFT VALIGN=TOP WIDTH=100  ".$this->color[0].">$key (obj)</TD>";
					$nd = new ext_debug_class(($this->level + 1));
					$tmp = $nd->debug($value, false);
					$this->result_debug .= "<TD class=noclass align=LEFT VALIGN=TOP WIDTH=80%  ".$this->color[1].">$tmp</TD></TR>";
					$nd = null;
				}else{
					$this->listElement($key, $value);
				}
			}
			$this->result_debug .= "\n</TABLE>";
			$this->result_debug .= "\n</TD></TR>";
		}
	}
	
	function addToDebug($element,$element_name){
		$this->elements[$element_name]=$element;
	}

	function listElement($key, $value){
		$this->result_debug .= "<TR><TD class=noclass VALIGN=TOP align=LEFT WIDTH=100 ".$this->color[0].">$key</TD>";
		if ((strtoupper(trim($key))=="SENHA") or (strtoupper(trim($key))=="PASSWORD")) {
			$this->result_debug .= "<TD class=noclass VALIGN=TOP align=LEFT  WIDTH=80%  ".$this->color[1].">********</TD></TR>";
		}elseif (strtoupper(trim($key))=="DESCRIPTOR") {
			$this->result_debug .= "<TD class=noclass VALIGN=TOP align=LEFT  WIDTH=80%  ".$this->color[1].">".$this->makeDescriptor($value)."</TD></TR>";
		}else{
			$this->result_debug .= "<TD class=noclass VALIGN=TOP align=LEFT  WIDTH=80%  ".$this->color[1].">$value</TD></TR>";
		}
	}
    
  function makeDescriptor($descriptor){
	  $descriptor = str_replace("=", "<FONT COLOR='RED'>=</FONT>", $descriptor);
	  $descriptor = str_replace("function", "<FONT COLOR='BLUE'>function</FONT>", $descriptor);
	  $descriptor = str_replace("returns", "<FONT COLOR='BLUE'>returns</FONT>", $descriptor);
	  $descriptor = str_replace("(", "<FONT COLOR='RED'>(</FONT>", $descriptor);
	  $descriptor = str_replace(")", "<FONT COLOR='RED'>)</FONT>", $descriptor);
	  $descriptor = str_replace("\$", "<FONT COLOR='BLUE'>\$</FONT>", $descriptor);
	  $descriptor = str_replace("&", "<FONT COLOR='RED'>&</FONT>", $descriptor);
	  return "<B><FONT FACE='Courier New' COLOR='GREEN' size=3>$descriptor</FONT></B>";
  }
}

// classe que retorna o tempo de execucao de um script
class time_exec{

	var $start;
	var $stop;
	var $total;

    
	// metodo que retorna o time do start
    function time_exec(){
         $micro = explode(" ", microtime()); // busca a data
         $this->start = $micro[1] + $micro[0]; // calcula o valor 
         return $this->start;
	}

    
	// metodo que retorna o time do stop
    function stop(){
         $micro = explode(" ", microtime()); // busca a data
         $this->stop = $micro[1] + $micro[0]; // calcula o valor 
         $this->total = $this->stop - $this->start;
         return $this->total;
	}
}
?>