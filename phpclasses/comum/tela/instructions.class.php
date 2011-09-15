<?php

class instructions{
	
	var $_code;
	var $msgInstructions=array();
	var $styleTable="border: 1px dotted #333366;";
	var $tableAlign="center";
	var $tableWidth="500px";
	var $classTD="cadastro";
	var $styleCell="text-align:justify;";
	var $styleTitle="font-weight:600";
	var $styleInstructions;
	var $title = "Instruções";
	var $mostrarBorda=TRUE;
	var $instrucaoUnica = false;
	
	function generate(){
		if ( count($this->msgInstructions)>0){
			$tablewidth="";
			$styleTable = "";
			if ($this->mostrarBorda==TRUE)
			{
				$styleTable = $this->styleTable;
			}
			
			if ($this->tableWidth!="")
			{
				$tableWidth = "WIDTH=".$this->tableWidth;
				$styleTable .= "width:".$this->tableWidth.";";
			}
			//<table border=0 width=500><tr><td>
			$msgInfo = "<table ".$tableWidth." style='".$styleTable."' align=".$this->tableAlign." >".
								"<tr>".
									"<td ".$tablewidth." class=".$this->classTD." style='".$this->styleCell."'><font style='".$this->styleTitle."'>".$this->title."</font>".($this->title==""?"":":").
									"<BR><BR>";
			$cont = 1;
			$fontIni = "";
			$fontFim = "";
			if ($this->styleInstructions!=""){
				$fontIni = "<font style='".$this->styleInstructions."'>";
				$fontFim = "</font>";
			}
            if($this->instrucaoUnica == false) {
                foreach( $this->msgInstructions as $i =>$msg ){                
                    $msgInfo .= $cont.". ".$fontIni.$msg.$fontFim." <br>";
                    $cont++;
                }
            } else {
                    $msgInfo .= $fontIni.$this->msgInstructions.$fontFim."<br>";
            }

			$msgInfo .= "<br></td>".
						"</tr>".
					"</table>";
					//"</table></td></tr></table>";
			return $msgInfo;
		}
	}
		
}

?>