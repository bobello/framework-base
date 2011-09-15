<?php
require_once "comum/sessao/configsis.inc.php"; 

/*
* Classe base para todas as outras
* 
* Cria um gerenciador de eventos
* Utilizar em notos os métodos o comando
* $this->addEvent('nomeDaClasse::'. __FUNCTION__);
* 
*/
class classBase{

	function addEvent($event){
		GLOBAL $SISCONF;
		array_push($SISCONF['SIS']['EVENTS'], $event);
	}
	
	function showEvents(){
		GLOBAL $SISCONF;
		$cell = " STYLE=\"{background-color: #f0f0f0}\"";
		$fnt = "<FONT FACE=COURIER SIZE=2>";
		$efnt = "</FONT>";
		$tblEv = "<TABLE ALIGN=CENTER WIDTH=400 ".
		  	" BORDER=0 CELLPADDING=1 CELLSPACING=0>";
  		$tblEv .= "<TR><TD $cell COLSPAN=3 ALIGN=CENTER><B>Sequência de Eventos</B></TD></TR>";
		for($i=0; $i<count($SISCONF['SIS']['EVENTS']); $i++){
			$x= explode("::",$SISCONF['SIS']['EVENTS'][$i]);
			if (count($x)>1) {
				$tblEv .= "<TR><TD $cell ALIGN=RIGHT>$fnt $i $efnt</TD>
							<TD $cell ALIGN=RIGHT>$fnt ".$x[0]."$efnt</TD>
							<TD $cell ALIGN=LEFT>$fnt ::".$x[1]."$efnt</TD></TR>";
			}else{
				$tblEv .= "<TR><TD $cell ALIGN=RIGHT>$fnt $i $efnt</TD>
							<TD $cell ALIGN=RIGHT>$fnt nonIdentified$efnt</TD>
							<TD $cell ALIGN=LEFT>$fnt ::".$x[0]."$efnt</TD></TR>";
			}
		}
		$tblEv .= "</TABLE>";
		echo $tblEv;
	}
	
	function dump($element){
		echo "<PRE>";
		var_dump($element);
		echo "</PRE>";
	}

}

?>
