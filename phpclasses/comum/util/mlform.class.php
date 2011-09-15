<?php
/*
* ********************* DISTRIBUITED ON BSD LICENSE *******************************
* 
* Copyright (C) 2004 - RUDINEI PEREIRA DIAS
* All rights reserved.
* 
*    Este programa é gratuito nos termos da Licensa BSD
*    Porém se você gostar deste programa e lhe for útil, envie um cartão 
* postal de sua região para a coleção do autor como forma de agradecimento 
* (Isto não é obrigatório). Mencione também o projeto no qual foi/está 
* sendo utilizado.
* 
*    This program is free software under the terms of the BSD License.
*    However if you to like this program and it it will be useful, 
* send a postal card of your country/region for the collection of the author 
* as gratefulness (This is not mandatory), mentioning the project where he 
* is being used.
* 
* ********************* DISTRIBUITED ON BSD LICENSE *******************************
* 
* Redistribution and use in source and binary forms, with or without modification, 
* are permitted provided that the following conditions are met:
*   1. Redistributions of source code must retain the above copyright notice, this 
*      list of conditions and the following disclaimer. 
*   2. Redistributions in binary form must reproduce the above copyright notice, 
*      this list of conditions and the following disclaimer in the documentation 
*      and/or other materials provided with the distribution. 
*   3. Neither the name of the author nor the names of its contributors 
*      may be used to endorse or promote products derived from this software 
*      without specific prior written permission. 
* 
* ********************** NO WARRANTY ****************************
*
* THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
* AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE IMPLIED 
* WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE ARE DISCLAIMED. 
* IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, 
* INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, 
* BUT NOT LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, 
* DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY 
* OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING 
* NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, 
* EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
* 
** Copyright (C) 2004 - RUDINEI PEREIRA DIAS
* You must contact author by email <rudineidias at pop dot com dot br>
* Você pode entrar em contato com o autor pelo email <rudineidias at pop dot com dot br>
* 
* NOTE: This program entirely was developed in the free time of the author,
*       and did not occupy time of any project where he was inserted.
* 
* NOTA: Este programa foi inteiramente desenvolvido no tempo livre do autor,
*       não tomando parte do tempo de qualquer projeto dos quais ele foi 
*       incluído.
* 
*/

/**
Alterado por Rudinei Dias em  17/09/2004
Adicionado o descritor ($descriptor) de funcoes
*/

/*
* Class mlForm
* MindLight Objects 
* VERSÃO 0.1
* Pacote de Classes MindLight Objects v0.1 (15 Objects)
* ------------------------------------------------------------------------------
* class mlForm
* * new mlForm($title = '', $width='AUTO', $action = 'AUTO', $name = 'AUTO',$enctype='multipart/form-data')
* ------------------------------------------------------------------------------
* class mlToolSet
* * new mlToolSet() 
* ------------------------------------------------------------------------------
* class mlField
* * new mlField($name, $value, $flyHint)
* ------------------------------------------------------------------------------
* class mlRadioField extends mlField
* * new mlRadioField($name, $value, $flyHint = "", $checked = false, $text = "", $readOnly = FALSE)
* ------------------------------------------------------------------------------
* class mlCheckField extends mlField
* * new mlCheckField($name, $value, $flyHint = "", $checked = false, $text = "", $readOnly = FALSE)
* ------------------------------------------------------------------------------
* class mlTextField extends mlField
* * new mlTextField($name, $value, $flyHint, $size = 10, $maxLength = '', $readOnly = FALSE)
* ------------------------------------------------------------------------------
* class mlPasswordField extends mlTextField 
* * new mlPasswordField($name, $value, $flyHint, $size = 10, $maxLength = '', $readOnly = FALSE) 
* ------------------------------------------------------------------------------
* class mlTextArea extends mlField
* * new mlTextArea($name, $value, $flyHint, $cols = 10, $rows = 5, $readOnly = FALSE)
* ------------------------------------------------------------------------------
* class mlHiddenField extends mlField 
* * new mlHiddenField($name, $value = '') 
* ------------------------------------------------------------------------------
* class mlComboBox extends mlField
* * new mlComboBox($name, $value, $flyHint, $arrayValues, $readOnly = FALSE)
* ------------------------------------------------------------------------------
* class mlButton extends mlField
* * new mlButton($name, $value, $flyHint, $type = 'SUBMIT', $readOnly = FALSE)
* ------------------------------------------------------------------------------
* class mlImageField extends mlField 
* * new mlImageField($name, $linkSource, $flyHint = "", $width = "", $height = "", $linkDestination = "", $target = "")
* ------------------------------------------------------------------------------
* class mlLabelField extends mlField 
* * new mlLabelField($name, $value)
* ------------------------------------------------------------------------------
* class mlFileField extends mlField
* * new mlFileField($name)
* ------------------------------------------------------------------------------
* class mlGrid extends mlField 
* * new mlGrid($name, $title, $cols, $width, $height = '')
**/

//O estilo está definido em ipucconfig.inc.php
/*
define ("ESTILO", "<STYLE TYPE='text/css'>".
				".formTitle       {font-family: Tahoma; color: #114f11; font-weight: bold;   font-size: 16px; text-align: CENTER; text-decoration: underline;}".
				".textField       {font-family: Arial;  color: #114f11; font-weight: normal; font-size: 12px; text-align: LEFT;    border-style: groove; background-color=#FFFFFF;}".
				".textFieldLock   {font-family: Arial;  color: #000000; font-weight: normal; font-size: 12px; text-align: LEFT;    border-style: groove; background-color=#F6F6D5;}".
				".fileField       {font-family: Arial;  color: #114f11; font-weight: normal; font-size: 12px; text-align: LEFT;    border-style: groove; background-color=#FFFFFF;}".
				".fileFieldLock   {font-family: Arial;  color: #000000; font-weight: normal; font-size: 12px; text-align: LEFT;    border-style: groove; background-color=#F6F6D5;}".
				".buttonField     {font-family: Arial, Helvetica, sans-serif;	font-size: 11px;	color: #FFFFFF;	border-style: solid;	border-width: 0px;  border-color: #F68B1F;	background-color: #EC995D;}".
				".buttonFieldLock {font-family: Arial, Helvetica, sans-serif;	font-size: 11px;	color: #FFFFFF;	border-style: solid;	border-width: 0px;  border-color: #F68B1F;	background-color: #EC995D;}".
				".labelField      {font-family: Arial;  color: #000000; font-weight: 700;    font-size: 12px; text-align: RIGHT;}".
				".imageField      {font-family: Arial;  color: #114f11; font-weight: normal; font-size: 12px; text-align: LEFT;      border-style: none; border-width: 0px; border-color: #000000; background-color=#FFFFFF;}".
				".markField       {font-family: Arial;  color: #114f11; font-weight: normal; font-size: 12px; text-align: LEFT;}".
				".formError       {font-family: Tahoma; color: #CC0033; font-weight: bold;   font-size: 12px; text-align: LEFT; width: 80%;}".
				".formDef         {border-style: solid; color: #EC995D; border-width: 1px; background-color: #c9d3dd; border-color: #005CA1;}". 
				".gridDef         {border-style: solid; color: #EC995D; border-width: 1px; background-color: #c9d3dd; border-color: #005CA1;}". 
				".gridTitle       {font-family: Arial;  color: #000000; font-weight: 700;    font-size: 12px; border-style: solid;  border-width: 0px; background-color: #c9d3dd; border-color: #005CA1; text-align: center;}". 
				".gridHeaders     {font-family: Arial;  color: #000000; font-weight: 500;    font-size: 10px; border-style: outset; border-width: 1px; background-color: #c9d3dd; border-color: #005CA1; text-align: center;}". 
				".gridFieldFace1  {font-family: Arial;  color: #000000; font-weight: 500;    font-size: 10px; border-style: outset; border-width: 1px; background-color: #F0F0F0; border-color: #005CA1; text-align: center;}".
				".gridFieldFace2  {font-family: Arial;  color: #000000; font-weight: 500;    font-size: 10px; border-style: outset; border-width: 1px; background-color: #FFFFFF; border-color: #005CA1; text-align: center;}".
				".gridFieldFace1L  {font-family: Arial;  color: #000000; font-weight: 500;    font-size: 10px; border-style: outset; border-width: 1px; background-color: #F0F0F0; border-color: #005CA1; text-align: left;}".
				".gridFieldFace2L  {font-family: Arial;  color: #000000; font-weight: 500;    font-size: 10px; border-style: outset; border-width: 1px; background-color: #FFFFFF; border-color: #005CA1; text-align: left;}".
				".gridFieldFace1R  {font-family: Arial;  color: #000000; font-weight: 500;    font-size: 10px; border-style: outset; border-width: 1px; background-color: #F0F0F0; border-color: #005CA1; text-align: rigth;}".
				".gridFieldFace2R  {font-family: Arial;  color: #000000; font-weight: 500;    font-size: 10px; border-style: outset; border-width: 1px; background-color: #FFFFFF; border-color: #005CA1; text-align: rigth;}".
				"</STYLE>");
				*/


class mlStyleSheet{
	var $stylesheet = array(
				".formTitle"=>" {font-family: Tahoma; color: #114f11; font-weight: bold;   font-size: 16px; text-align: CENTER; text-decoration: underline;}",
				".textField"=>" {font-family: Arial;  color: #114f11; font-weight: normal; font-size: 12px; text-align: LEFT;    border-style: groove; background-color=#FFFFFF;}",
				".textFieldLock"=>" {font-family: Arial;  color: #000000; font-weight: normal; font-size: 12px; text-align: LEFT;    border-style: groove; background-color=#F6F6D5;}",
				".fileField"=>" {font-family: Arial;  color: #114f11; font-weight: normal; font-size: 12px; text-align: LEFT;    border-style: groove; background-color=#FFFFFF;}",
				".fileFieldLock"=>" {font-family: Arial;  color: #000000; font-weight: normal; font-size: 12px; text-align: LEFT;    border-style: groove; background-color=#F6F6D5;}",
				".buttonField"=>" {font-family: Arial, Helvetica, sans-serif;	font-size: 11px;	color: #FFFFFF;	border-style: solid;	border-width: 0px;  border-color: #F68B1F;	background-color: #EC995D;}",
				".buttonFieldLock"=>" {font-family: Arial, Helvetica, sans-serif;	font-size: 11px;	color: #FFFFFF;	border-style: solid;	border-width: 0px;  border-color: #F68B1F;	background-color: #EC995D;}",
				".labelField"=>" {font-family: Arial;  color: #000000; font-weight: 700;    font-size: 12px; text-align: RIGHT;}",
				".imageField"=>" {font-family: Arial;  color: #114f11; font-weight: normal; font-size: 12px; text-align: LEFT;      border-style: none; border-width: 0px; border-color: #000000; background-color=#FFFFFF;}",
				".markField"=>" {font-family: Arial;  color: #114f11; font-weight: normal; font-size: 12px; text-align: LEFT;}",
				".formError"=>" {font-family: Tahoma; color: #CC0033; font-weight: bold;   font-size: 12px; text-align: LEFT; width: 80%;}",
				".formDef"=>" {border-style: solid; color: #EC995D; border-width: 1px; background-color: #c9d3dd; border-color: #005CA1;}", 
				".gridDef"=>" {border-style: solid; color: #EC995D; border-width: 1px; background-color: #c9d3dd; border-color: #005CA1;}",
				".gridTitle"=>" {font-family: Arial;  color: #000000; font-weight: 700;    font-size: 12px; border-style: solid;  border-width: 0px; background-color: #c9d3dd; border-color: #005CA1; text-align: center;}",
				".gridHeaders"=>" {font-family: Arial;  color: #000000; font-weight: 500;    font-size: 10px; border-style: outset; border-width: 1px; background-color: #c9d3dd; border-color: #005CA1; text-align: center;}",
				".gridFieldFace1"=>" {font-family: Arial;  color: #000000; font-weight: 500;    font-size: 10px; border-style: outset; border-width: 1px; background-color: #F0F0F0; border-color: #005CA1; text-align: center;}",
				".gridFieldFace2"=>" {font-family: Arial;  color: #000000; font-weight: 500;    font-size: 10px; border-style: outset; border-width: 1px; background-color: #FFFFFF; border-color: #005CA1; text-align: center;}",
				".gridFieldFace1L"=>" {font-family: Arial;  color: #000000; font-weight: 500;    font-size: 10px; border-style: outset; border-width: 1px; background-color: #F0F0F0; border-color: #005CA1; text-align: left;}",
				".gridFieldFace2L"=>" {font-family: Arial;  color: #000000; font-weight: 500;    font-size: 10px; border-style: outset; border-width: 1px; background-color: #FFFFFF; border-color: #005CA1; text-align: left;}",
				".gridFieldFace1R"=>" {font-family: Arial;  color: #000000; font-weight: 500;    font-size: 10px; border-style: outset; border-width: 1px; background-color: #F0F0F0; border-color: #005CA1; text-align: right;}",
				".gridFieldFace2R"=>" {font-family: Arial;  color: #000000; font-weight: 500;    font-size: 10px; border-style: outset; border-width: 1px; background-color: #FFFFFF; border-color: #005CA1; text-align: right;}",
				
				".gridFieldChecked"=>" {font-family: Arial;  color: #000000; font-weight: 200;    font-size: 10px; border-style: outset; border-width: 1px; background-color: #FFCC99; border-color: #005CA1; text-align: center;}",
				".gridFieldCheckedL"=>" {font-family: Arial;  color: #000000; font-weight: 200;    font-size: 10px; border-style: outset; border-width: 1px; background-color: #FFCC99; border-color: #005CA1; text-align: left;}",
				".gridFieldCheckedR"=>" {font-family: Arial;  color: #000000; font-weight: 200;    font-size: 10px; border-style: outset; border-width: 1px; background-color: #FFCC99; border-color: #005CA1; text-align: right;}",
				".lineHand"=>" {cursor:pointer;}");

	function getCSS(){
		$css = "\n<STYLE TYPE='text/css'>";
		foreach($this->stylesheet as $csskey => $cssdata){
			$css .= "\n".$csskey."\t".$cssdata;
		}
		$css .= "\n</STYLE>";
		return $css;
	}

}


class mlForm extends mlStyleSheet{
    var $descriptor = "function mlForm(\$title = '', \$width='AUTO', \$action = 'AUTO', \$name = 'AUTO',\$enctype='multipart/form-data') ";
    
    var $name;
    var $action;
    var $enctype;
    var $_borderTable=0;
    var $target;
    var $title;
    var $width;
    var $height;
    var $cols;
    var $colsWidth = array();
    var $rows;
    var $formErrors;
    var $formErrorMessage;
    var $errors;
    var $errorMessage;
    var $listObjects;
    var $formErrorMessageTitle;
    var $randonColor = false;
    var $_devMode = FALSE;
    
    function mlForm($title = '', $width='AUTO', $action = 'AUTO', $name = 'AUTO',$enctype='multipart/form-data'){
    
			$this->errors = 0;
			$this->errorMessage = '';
			$this->listObjects = array();
			$this->formErrorMessageTitle = "";

			$this->formErrors = 0;
			$this->formErrorMessage = array();
			
			if ($name == 'AUTO') $this->name = 'frm'.chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).chr(rand(65,90)).rand(100,999);
			else $this->name = $name;
			
            GLOBAL $PHP_SELF;
			if ($action == 'AUTO') $this->action = $PHP_SELF;
			else $this->action = $action;
			
			if ($width == 'AUTO') $this->width = '80%';
			else $this->width = $width;
			
			$this->title   = $title;
			$this->enctype = $enctype;
			//echo $this->getCSS();
      }
	  
	  function addErrorMessage($message){
	  		$message = trim($message);
			if ($message != "") array_push($this->formErrorMessage, $message);
	  }
	  
	  function setColumnsWidth($column, $width=100){
	  		if (is_numeric($column) && is_numeric($width)) {
				$width = (int) $width;
				$column = (int) $column;
				$this->colsWidth[$column] = $width;
	  		}else{
                  $this->errors++;
                  $this->errorMessage .= "<BR>Undefined parameter value for setColumnsWidth(\$column,\$width): \$column($column) or \$width($width) must be numeric ";
			}
	  }
      
      function setFormTarget($target){
			$target = trim($target);
			if ($target != '') $this->target = ' TARGET='.$target;
			else $this->target = '';
      }

      function setFormHeight($height){
			$height = trim($height);
			if (is_numeric($height)){
				$height = (int) $height;
				$this->height = $height;
			}else{
				$this->height = '';
				$this->errors++;
				$this->errorMessage .= "<BR>Undefined parameter value for setFormHeight(\$height): \$height must be numeric ";
			}
      }
      
      function setFormModel($numCols){
			if (is_numeric($numCols)){
				$numCols = (int) $numCols;
				$this->cols = $numCols;
			}else{
				$this->cols = '';
				$this->errors++;
				$this->errorMessage .= "<BR>Undefined parameter value for setFormModel(\$numCols): \$numCols must be numeric ";
			}
      }
      
      function getNextRow(){
            if (is_numeric($this->rows)) $this->rows++;
            else $this->rows = 1;
            return $this->rows;
      }
      
      function addField($row, $column, $objectField, $colSpan = 1, $align='LEFT', $valign='TOP'){
            if (!is_numeric($row)){
                  $this->errors++;
                  $this->errorMessage .= "<BR>Undefined parameter value \$row to Object ".$objectField->_TYPE;
            }
            if (!is_numeric($column)){
                  $this->errors++;
                  $this->errorMessage .= "<BR>Undefined parameter value \$column to Object ".$objectField->_TYPE;
            }
            if (!is_object($objectField)){
                  $this->errors++;
                  $this->errorMessage .= "<BR>Undefined Object on \$row ".$row." \$column ".$column.".";
            }
            if (!is_numeric($colSpan)){
                  $this->errors++;
                  $this->errorMessage .= "<BR>Undefined parameter value \$colSpan to Object ".$objectField->_TYPE;
            }
            if ($this->errors > 0) return;

            $tmp = array('COLUMN'=> $column,
                 'OBJECT'=> $objectField,
                 'SPAN'=> $colSpan,
				 'ALIGN' => $align, 
				 'VALIGN' => $valign);

            $this->listObjects[$row][$column]['OBJECT'] = $tmp;
      
      }

      function getColor(){
           $color1 = "A7C2DA";
           $color2 = "#FFFFFF";
           static $color;
           if ($color == $color1 ){
               $color = $color2;
           } else {
               $color = $color1;
           }
           return $color;
     }
      
	function generateForm(){
                $form = '';

		//test column number	
		if (!is_numeric($this->cols)){
			$this->errors++;
			$this->errorMessage .= "<BR>Undefined Form Model: To define use setFormModel(\$numCols).";
		}

		//test rows number and objects existance
		if (!is_numeric($this->rows) OR	count($this->listObjects)==0){
			$this->errors++;
			$this->errorMessage .= "<BR>No rows added to Form: Needed objects to create form fields. ".
				"<br> - (".count($this->listObjects).") Objects registered".
				"<BR> - To get a row number use getNextRow().".
				"<BR> - To add objects use addField(\$row, \$column, \$objectField).";
		}
		
		//Verify error messages (on code)
		if ($this->errors > 0){
			echo "<P ALIGN=LEFT STYLE='{border-style: solid; border-width:2px; border-color: RED; background-color: YELLOW; color: BLUE;}'><B>FORM ERROR</B>";
			echo $this->errorMessage;
			echo "</P>";
			//exit;
		}else{
			//$this->listObjects[$row][$column]
			$form .= "\n<FORM NAME='".$this->name."' ACTION='".$this->action."' ".$this->target." METHOD='POST' ENCTYPE='".$this->enctype."'>";
			
			//$form .= "\n<TABLE CLASS='formDef' STYLE='{height: ".$this->height."px;}' width='".$this->width."'>";
			if ($this->_devMode){
				$form .= "\n<TABLE BORDER=2 BORDERCOLOR=RED CLASS='formDef' STYLE='{height: ".$this->height."px;}' width='".$this->width."'>";
			}else{
				$form .= "\n<TABLE BORDER=".$this->_borderTable." BORDERCOLOR=RED CLASS='formDef' STYLE='{height: ".$this->height."px;}' width='".$this->width."'>";
			}
			
			$form .= "\n<TR ";
			
			if ($this->randonColor){
                $form .= "BGCOLOR='".$this->getColor()."'>";
            } else {
                $form .= ">";
            }
            
			$form .= "<TD CLASS='formTitle' COLSPAN='".$this->cols."'>".$this->title."</TD>";
			$form .= "</TR>";
			
			$msgError = count($this->formErrorMessage);
			if ($msgError > 0) {
				$form .= "\n<TR BGCOLOR='#FFFFC0'>";
				$form .= "<TD COLSPAN='".$this->cols."' ALIGN=CENTER><P CLASS='formError'>";
				$br = "<B>".$this->formErrorMessageTitle."</B>";
	
				for ($fe=0; $fe < $msgError; $fe++){
					$form .= "$br"." ".$this->formErrorMessage[$fe];
					$br = "<BR>";
				}
	
				$form .= "</P></TD>";
				$form .= "</TR>";
			}
			
			
			foreach ($this->listObjects as $key => $value){
			$form .= "\n<TR ";

            if ($this->randonColor){
                $form .= "BGCOLOR='".$this->getColor()."'>";
            } else {
                $form .= ">";
            }

            $i=0;
				for ($k=1; $k<=$this->cols; $k++){
	
					$tmp = $value[$k]['OBJECT'];
	
					if (is_array($tmp)) {
						$obj = $tmp['OBJECT'];
						$span = $tmp['SPAN'];
						$align = $tmp['ALIGN'];
						$valign = $tmp['VALIGN'];
						if (is_object($obj)) {
							if (is_numeric($span)){
								$tspan = "COLSPAN=$span";
								$i+= $span;
							}else{
								$tspan = "";
								$i++;
							}
							$form .= "\n<TD $tspan ALIGN='$align' VALIGN='$valign'>";
							$form .= $obj->getCode();
							$form .= "</TD>";
						}
					}else{
						if ($i<$k) {
							$form .= "\n<TD>&nbsp;&nbsp;";
							$form .= "</TD>";
							$i++;
						}
					}
					
				}
				$form .= "\n</TR>";
			}
			
			$form .= "</TABLE>";
			$form .= "</FORM>";
			
			echo $form;
		}
	}
	
	function devMode($state=FALSE){
		$this->_devMode = $state;
	}

}

class mlToolSet{
var $descriptor = " function mlToolSet()  ";
	var $elements;
	var $background;
	var $font_color;
	
 	function mlToolSet() {
		$this->elements = array();
		$this->_TYPE = 'toolSet('.$name.')';
	}

    function addField(&$objectField){
		array_push($this->elements, $objectField);
	}

	function getClass() {
		$f = $this->elements[0];
        $cl = get_class($f);
        return $cl;
    }
    
 	function getCode() {
		$allCode = "";
		foreach ($this->elements as $f) {
			$allCode .= "\n".$f->getCode();
        }
		return $allCode;
    }
}// End of toolSet CLASS


class mlField{
var $descriptor = " function mlField(\$name, \$value, \$flyHint) ";
	var $_name;
	var $_value;
	var $_flyHint;
	var $_size = 10;
	var $_maxLength;
	var $_TYPE = 'BASE FIELD';
	var $_LOCKED = FALSE;
	
	function mlField($name, $value, $flyHint){
		$this->_name = $name;
		$this->_value = $value;
		$this->_flyHint = $flyHint;
	}
	
	function getCode(){
	}
}// End of field CLASS

class mlRadioField extends mlField{
    var $descriptor = "  function mlRadioField(\$name, \$value, \$flyHint = \"\", \$checked = false, \$text = \"\", \$readOnly = FALSE) ";
    var $_checked;
    var $_text;

    function mlRadioField($name, $value, $flyHint = "", $checked = false, $text = "", $readOnly = FALSE){
		$this->_LOCKED = false;
        $this->_checked = false;
		$this->_name = $name;
		$this->_value = $value;
        $this->_text = $text;
		$this->_flyHint = $flyHint;
		$this->_TYPE = 'radioField('.$name.')';
		if (is_bool($readOnly)) $this->_LOCKED = $readOnly;
		if (is_bool($checked)) $this->_checked = $checked;
    } 

    function getCode()
    {
        $field = "";
				$field .= "<INPUT CLASS='markField' NAME='".$this->_name."' ";
    		$field .= " TITLE='".$this->_flyHint."'";
    		$field .= " TYPE='RADIO' value='".$this->_value."'";
        if ($this->_checked) $field .= " CHECKED ";
				if ($this->_LOCKED) $field .= " DISABLED ";
        $field .= ">";
  			if (trim($this->_text)!="") {
    			$field .= "<FONT CLASS='labelField'";
    			$field .= " TITLE='".$this->_flyHint."'";
    			$field .= ">".$this->_text."</FONT>";
				}
				return $field;
    } 
}


class mlCheckField extends mlField{
    var $descriptor = " function mlCheckField(\$name, \$value, \$flyHint = \"\", \$checked = false, \$text = \"\", \$readOnly = FALSE)  ";
    var $_checked;
    var $_text;

    function mlCheckField($name, $value, $flyHint = "", $checked = false, $text = "", $readOnly = FALSE){
		$this->_LOCKED = false;
        $this->_checked = false;
		$this->_name = $name;
		$this->_value = $value;
        $this->_text = $text;
		$this->_flyHint = $flyHint;
		$this->_TYPE = 'checkField('.$name.')';
		if (is_bool($readOnly)) $this->_LOCKED = $readOnly;
		if (is_bool($checked)) $this->_checked = $checked;
    } 

    function getCode()
    {
        $field = "";
		$field .= "<INPUT CLASS='markField' NAME='".$this->_name."' ";
		$field .= " TITLE='".$this->_flyHint."'";
		$field .= " TYPE='CHECKBOX' value='".$this->_value."'";
        if ($this->_checked) $field .= " CHECKED ";
		if ($this->_LOCKED) $field .= " DISABLED ";
        $field .= ">";
		if (trim($this->_text)!="") {
			$field .= "<FONT CLASS='labelField'";
			$field .= " TITLE='".$this->_flyHint."'";
			$field .= ">".$this->_text."</FONT>";
		}
		return $field;
    } 
}


class mlTextField extends mlField{
  var $descriptor = "  function mlTextField(\$name, \$value, \$flyHint, \$size = 10, \$maxLength = '', \$readOnly = FALSE) ";
	var $_fieldType;

	function mlTextField($name, $value, $flyHint, $size = 10, $maxLength = '', $readOnly = FALSE){
		$this->_name = $name;
		$this->_value = $value;
		$this->_flyHint = $flyHint;
		$this->_TYPE = 'textField('.$name.')';
		$this->_fieldType = 'TEXT';
		$this->_size = $size;
		$this->_maxLength = $maxLength;
		if (is_bool($readOnly)) $this->_LOCKED = $readOnly;
	}

      function getCode(){
          $field = "";
          $field .= "\n<INPUT TYPE='".$this->_fieldType."'";
          $field .= " NAME='".$this->_name."'";
          $field .= " VALUE='".$this->_value."'";
          $field .= " TITLE='".$this->_flyHint."'";
          if (is_numeric($this->_size)) $field .= " SIZE='".$this->_size."'";
          if (is_numeric($this->_maxLength)) $field .= " MAXLENGTH='".$this->_maxLength."'";
          if ($this->_LOCKED) {
             $field .= " READONLY";
             $field .= " CLASS='textFieldLock'";
          }else{
             $field .= " CLASS='textField'";
          }
          $field .= ">\n";
          
          return $field;
      }
}// End of textField CLASS


class mlPasswordField extends mlTextField {
  var $descriptor = "  function mlPasswordField(\$name, \$value, \$flyHint, \$size = 10, \$maxLength = '', \$readOnly = FALSE) ";
	function mlPasswordField($name, $value, $flyHint, $size = 10, $maxLength = '', $readOnly = FALSE) {
		$this->textField($name, $value, $flyHint, $size, $maxLength, $readOnly);
		$this->_fieldType = 'PASSWORD';
		$this->_TYPE = 'passwordField('.$name.')';
    }
}// End of passwordField CLASS


class mlTextArea extends mlField{
  var $descriptor = " function mlTextArea(\$name, \$value, \$flyHint, \$cols = 10, \$rows = 5, \$readOnly = FALSE) ";
	var $_fieldType;
	var $_rows;
	var $_cols;

	function mlTextArea($name, $value, $flyHint, $cols = 10, $rows = 5, $readOnly = FALSE){
		$this->_name = $name;
		$this->_value = $value;
		$this->_flyHint = $flyHint;
		$this->_TYPE = 'textArea('.$name.')';
		$this->_fieldType = 'TEXT';
		$this->_cols = $cols;
		$this->_rows = $rows;
		if (is_bool($readOnly)) $this->_LOCKED = $readOnly;
	}

      function getCode(){
          $field = "";
          $field .= "\n<TEXTAREA ";
          $field .= " NAME='".$this->_name."'";
          $field .= " TITLE='".$this->_flyHint."'";
          if (is_numeric($this->_cols)) $field .= " COLS='".$this->_cols."'";
          if (is_numeric($this->_rows)) $field .= " ROWS='".$this->_rows."'";
          if ($this->_LOCKED) {
             $field .= " READONLY";
             $field .= " CLASS='textFieldLock'";
          }else{
             $field .= " CLASS='textField'";
          }
          $field .= ">\n";
					$field .= $this->_value;          
          $field .= "</TEXTAREA>";
          
          return $field;
      }
}// End of textField CLASS




class mlHiddenField extends mlField {
  var $descriptor = " function mlHiddenField(\$name, \$value = '') ";
	function mlHiddenField($name, $value = '') {
		$this->_TYPE = 'hiddenField('.$name.')';
		$this->_name = $name;
		$this->_value = $value;
	}

	function getCode(){
		$field = "";
		$field .= "\n<INPUT TYPE='HIDDEN'";
		$field .= " NAME='".$this->_name."'";
		$field .= " VALUE='".$this->_value."'";
		$field .= ">\n";
		return $field;
	}
}

class mlComboBox extends mlField{
  var $descriptor = " function mlComboBox(\$name, \$value, \$flyHint, \$arrayValues, \$readOnly = FALSE) ";
	var $dataArray = array();

	function mlComboBox($name, $value, $flyHint, $arrayValues, $readOnly = FALSE){
		$this->_name = $name;
		$this->_TYPE = 'comboBox('.$name.')';
		if (substr($value,0,1)=='_') $value = substr($value,1, (strlen($value)-1));
		$this->_value = $value;
		$this->_flyHint = $flyHint;
		$this->_TYPE = 'comboBox('.$name.')';
		if (is_bool($readOnly)) $this->_LOCKED = $readOnly;
		if (is_array($arrayValues)) $this->dataArray = $arrayValues;
	}

	function getCode(){
          $field = "";
          $field .= "\n<SELECT NAME='".$this->_name."'";
          if ($this->_LOCKED) {
             $field .= " DISABLED";
             $field .= " CLASS='textFieldLock'";
          }else{
             $field .= " CLASS='textField'";
          }
          $field .= ">\n";
		  
		  foreach ($this->dataArray as $key => $value){
				$sel = "";
				if(strtoupper(trim(($this->_value))) == strtoupper(trim($key))) $sel = "SELECTED";
				$field .= "\n<OPTION VALUE='_$key' $sel>$value";
				$field .= "\n</OPTION>";
		  }
          $field .= "</SELECT>\n";
          
          return $field;
	}
}// End of comboBox CLASS


class mlButton extends mlField{
  var $descriptor = " function mlButton(\$name, \$value, \$flyHint, \$type = 'SUBMIT', \$readOnly = FALSE) ";
	var $type;

	function mlButton($name, $value, $flyHint, $type = 'SUBMIT', $readOnly = FALSE){
		$type = strtoupper(trim($type));
		
		switch($type){
		case 'SUBMIT': $this->type = 'SUBMIT'; break;
		case 'RESET':  $this->type = 'RESET';  break;
		case 'BUTTON': $this->type = 'BUTTON'; break;
		default:
			echo "<BR>object button($name): Invalid button type: $type; Use: SUBMIT | RESET | BUTTON<BR>";
			exit;
		}
		$this->_TYPE = 'button::'.$this->type.'('.$name.')'; 
		
		$this->_name = $name;
		if (substr($value,0,1)=='_') $value = substr($value,1, (strlen($value)-1));
		$this->_value = $value;
		$this->_flyHint = $flyHint;
		$this->_TYPE = 'button('.$name.')';
		if (is_bool($readOnly)) $this->_LOCKED = $readOnly;
	}

	function getCode(){
		$field = "";
		$field .= "\n<INPUT TYPE='".$this->type."'";
		$field .= " NAME='".$this->_name."'";
		$field .= " VALUE='".$this->_value."'";
		$field .= " TITLE='".$this->_flyHint."'";
		if ($this->_LOCKED) {
			$field .= " DISABLED";
			$field .= " CLASS='buttonFieldLock'";
		}else{
			$field .= " CLASS='buttonField'";
		}
		$field .= ">\n";
		return $field;
	}
}// End of button CLASS


class mlImageField extends mlField {
  var $descriptor = " function mlImageField( \$name, \$linkSource, \$flyHint = \"\", \$width = \"\", \$height = \"\", \$linkDestination = \"\", \$target = \"\" ) ";
    var $_href;
	var $_target;
	var $_w;
	var $_h;
    
 	function mlImageField($name, $linkSource, $flyHint = "", $width = "", $height = "", $linkDestination = "", $target = ""){
		$this->_name = $name;
		$this->_value = $linkSource;
		$this->_flyHint = $flyHint;
		$this->_w = "";
		$this->_h = "";
		$this->_href = "";
		$this->_TYPE = 'imageField('.$name.')'; 
		if (trim($width)!="") $this->_w = $width;
		if (trim($height)!="") $this->_h = $height;
		if (trim($linkDestination)!="") $this->_href = $linkDestination;
		if (trim($target)!="") $this->_target = $target;
    }

	function getCode() {
        $field = "";

		if (trim($this->_href)!="") {
			$field .= "<A HREF='".$this->_href."' BORDER=0 ";		
			if (trim($this->_target)!="") $field .= " TARGET='".$this->_target."'";		
			$field .= ">";		
		}

        $field .= "<IMG CLASS='imageField' SRC='".$this->_value."' BORDER='0'";
		if (trim($this->_flyHint)!="") $field .= " ALT='".$this->_flyHint."' TITLE='".$this->_flyHint."'";
		if (trim($this->_w)!="") $field .= " WIDTH='".$this->_w."'";
		if (trim($this->_h)!="") $field .= " HEIGHT='".$this->_h."'";
		$field .= ">";

		if (trim($this->_href)!="") $field .= "</A>";

		return $field;
    }
}// End of imageField CLASS

class mlLabelField extends mlField {
  var $descriptor = " function mlLabelField(\$name, \$value) ";
	var $label;
    var $name;
    var $value;
   
 	function mlLabelField($name, $value){
		$this->_name = $name;
		$this->_value = $value;
		$this->_TYPE = 'LabelField('.$name.')'; 
    }
	
	function getCode() {
        $field = "";
        $field .= "<LABEL CLASS='labelField' NAME='".$this->_name."'>".$this->_value."</LABEL>";
		return $field;
    }
}


class mlFileField extends mlField{
	var $descriptor = "  function mlFileField(\$name) ";
	var $_fieldType;

	function mlFileField($name){
		$this->_name = $name;
		$this->_fieldType = 'FILE';
	}

      function getCode(){
          $field = "";
          $field .= "\n <INPUT TYPE='".$this->_fieldType."'";
          $field .= " NAME='".$this->_name."'";
          $field .= " CLASS='fileField'>\n";
          return $field;
      }
}// End of textField CLASS

class mlIframe extends mlField {
}

class mlGrid extends mlField {
	var $descriptor = "function mlGrid(\$name, \$title, \$cols, \$width, \$height = '') <BR>to add Header: addHeader(\$headers = array()) <BR>to add Lines: addLine(\$line = array()) <BR> function setFormGridLine(\$targetAction = '')";
	var $label;
    var $name;
    var $value;
    var $title;
    var $cols = 0;
    var $heads = array();
    var $lines = array();
    var $styloLines = array();
    var $width = '';
    var $height = '';
    var $counter = array('ACTIVE' => false, 'SIDE' => '');
    var $_formGridLine = false;
    var $_formTargetAction = '';
	var $colsWidth=array();
	var $colsAlign = array();
	var $lineFunctions=array();
	var $lineId=array();
	var $classStyleLine="";
	var $gerarCabecalho=true;
	var $idTable;
	
 	function mlGrid($name, $title, $cols, $width, $height = '',$idTable=""){
	
		$this->_name = $name;
		$this->title = $title;
		$this->_TYPE = 'Grid('.$name.')'; 
		$this->lines = array();
		if (is_numeric($cols))
		{
		   $cols = (integer) $cols;
		   $this->cols = $cols;
		}
		else
		{
		   echo "<BR>Invalid parameter \$cols ($cols) in ".$this->_TYPE."<BR>$descriptor<BR>";
		   exit;
		}
		$width = (integer) $width;
		$height = (integer) $height;
		if (is_integer($width)) $this->width = $width;
		if (is_integer($height)) $this->height = $height;
		$this->idTable = $idTable;
    }
    
    function setFormGridLine($targetAction = ''){
    	if (trim($targetAction)!=''){
    		$this->_formGridLine = true;
    		$this->_formTargetAction = trim($targetAction);
    	}
    }
    
    function addHeader($headers = array()){
    	if (is_array($headers)){
    	   $this->heads = $headers;
    	}
    }
    
    function addLine($line = array(), $styloLine = "", $lineFunction="",$lineId=""){
    	if (is_array($this->heads)){
            if (is_array($line)){
            	if (count($line)!=count($this->heads)){
                    echo "<BR>Wrong number of columns to Line (need same count of headers) in ".$this->_TYPE."<BR>Use addLine(\$line = array())<BR>";
                    exit;
            	}else{
            		array_push($this->lines, $line);
            		array_push($this->styloLines, $styloLine);
					array_push($this->lineFunctions, $lineFunction);
					array_push($this->lineId,$lineId);
            	}
            }else{
                echo "<BR>Invalid Line (is not an array) in ".$this->_TYPE."<BR>Use addLine(\$line = array())<BR>";
                exit;
            }
    	}else{
            echo "<BR>Headers not defined in ".$this->_TYPE."<BR>Use addHeader(\$headers = array())<BR>";
            exit;
    	}
    }
    
    function setCounter($side = 'L'){
    	$side = strtoupper($side);
    	if ($side == 'L'){
		    $this->counter['ACTIVE'] = true;
			$this->counter['SIDE'] = 'LEFT';
		}elseif ($side == 'R'){
		    $this->counter['ACTIVE'] = true;
			$this->counter['SIDE'] = 'RIGHT';
		}else{
		    $this->counter['ACTIVE'] = false;
			$this->counter['SIDE'] = '';
		}
    }
	
	function setColsAlign($align) {
		$this->colsAlign = $align;
	}
	
	function setColsWidth( $colsWidth )
	{
		$this->colsWidth = $colsWidth;
	}
	
	function setColumnsWidth($column, $columnsWidth) {
		$this->colsWidth[$column] = $columnsWidth;
	}
	
	function clearLines() {
		$this->lines = array();
	}
	
	function getCode() {
        $field = "";
        $w = ""; $h = "";
        $cl = count($this->heads);
		if ($this->counter['ACTIVE']) $cl++;
	
		if (is_integer($this->width)) $w = "width='".$this->width."'";
		if (is_integer($this->height)) $h = "STYLE='{height: ".$this->height."px;}'";
		$id="";
		if($this->idTable!=""){$id = "id='".$this->idTable."'";}

        $field .= "\n<TABLE BORDER=0 $id BORDERCOLOR=RED CLASS='gridDef' CELLSPACING=0 $h $w>";
        //$field .= "\n<TABLE BORDER=1 BORDERCOLOR=RED $h $w>";
        //Make title
        if (trim($this->title)!=""){
    		$field .= "\n<TR><TD CLASS='gridTitle' COLSPAN='$cl'>".$this->title."</TD></TR>";
		}
        //Make headers
		if ($this->gerarCabecalho==true)
		{
			$field .= "\n<TR>";
			if (($this->counter['ACTIVE']) && ($this->counter['SIDE'] == 'LEFT')){
				$field .= "\n<TD CLASS='gridHeaders'></TD>";}
			for ($i=0; $i < count($this->heads); $i++){
				$colwidth="";
				if (isset($this->colsWidth[$i]))
				{
					$colwidth = ($this->colsWidth[$i]!=""?"WIDTH=".$this->colsWidth[$i]:"");
				}
				$field .= "\n<TD ".$colwidth." CLASS='gridHeaders'>".$this->heads[$i]."</TD>";
			}
			if (($this->counter['ACTIVE']) && ($this->counter['SIDE'] == 'RIGHT')){
				$field .= "\n<TD CLASS='gridHeaders'></TD>";}
			$field .= "\n</TR>";
		}
        //Make lines
        $linesCount = 0;
        $face = true;
        
        $key = "frm".chr(rand(65,85)).chr(rand(65,85)).chr(rand(65,85)).chr(rand(65,85));
        $inc = 0;
		
		$classStyleLine="";
		if ( isset($this->classStyleLine) )
		{
			$classStyleLine = " class=".$this->classStyleLine;
		}
		
		for ($i=0; $i < count($this->lines); $i++){
			$inc++;
			$line = $this->lines[$i];
			$linesCount++;
			
			if ($this->_formGridLine){
				$field .= "\n<FORM NAME='$key"."$inc' ACTION='".$this->_formTargetAction."' METHOD='POST'>";
			}
			
			$function = "";
			if ( isset($this->lineFunctions[$i]) )
			{
				$function = $this->lineFunctions[$i];
			}
			
			$id="";
			if ( isset($this->lineId[$i]) )
			{
				$id = "id=\"".$this->lineId[$i]."\"";
			}
			
			$field .= "\n<TR $function $id $classStyleLine >";
    		
			$st = "";
			if ( isset($this->styloLines[$i]) ) {
				$st = $this->styloLines[$i];
			}
			if ( $st == "" ) {
				if ($face) $st = "gridFieldFace1";
				else $st = "gridFieldFace2";
			}
			
			if (($this->counter['ACTIVE']) && ($this->counter['SIDE'] == 'LEFT'))
				$field .= "\n<TD CLASS='$st'>$linesCount</TD>";
			for ($z=0; $z < count($line); $z++){
				$colwidth="";
				if (isset($this->colsWidth[$z])) {
					$colwidth = ($this->colsWidth[$z]!=""?"WIDTH=".$this->colsWidth[$z]:"");
				}
				
				
				if (is_array($line[$z])){
					$bold = strtoupper($line[$z][2]);
					$value = $line[$z][0];
					$align = strtoupper($line[$z][1]);
				}else{
					$align="";
					$value = $line[$z];
				}
				if ( isset($this->colsAlign[$z]) ) {
					$align = $this->colsAlign[$z];
				}
				
				$tst = $st;
				if ($align == "RIGHT") $tst .="R";
				if ($align == "LEFT") $tst .="L";
				//if ($bold == 'BOLD') $value = "<B>$value</B>";
				
				//$field .= "\n<TD ".$colwidth." CLASS='$st'>".$line[$z]."</TD>";
				$field .= "\n<TD ".$colwidth." CLASS='$tst'>$value</TD>";
			}
			if (($this->counter['ACTIVE']) && ($this->counter['SIDE'] == 'RIGHT'))
				$field .= "\n<TD CLASS='$st'>$linesCount</TD>";
				
			$field .= "\n</TR>";

			if ($this->_formGridLine){
				$field .= "\n</FORM>";
			}

			$face = !$face;
		}

        $field .= "\n</TABLE>";
        return $field;
    }
}

class mlSpecialGrid extends mlGrid {
	function mlGrid($name, $title, $cols, $width, $height = '') {
		$this->mlGrid($name, $title, $cols, $width, $height = '');
	}
}

class mlCell {
	var $code;
	var $aditionalCode;
	var $nameSpan;
	var $openSpan = false;
	
	function mlCell($op) {
		
	}
	
	function getJSinvertView() {
		GLOBAL $SISCONF;
		
		$js = "\n<SCRIPT LANGUAGE=\"javaScript\">".
			"function invertView(id) {\n".
			"	var element = document.getElementById(id);
				var elementImg = document.getElementById('i'+id);
				var address = '".$SISCONF['SIS']['COMUM']['IMAGENS_TEMA']."';
				
				if (element.style.display=='none'){
					element.style.display='';
					elementImg.src = address+'close.gif';
				} else {
					element.style.display='none';
					elementImg.src = address+'open.gif';
				}
			".
			"}\n".
			"</SCRIPT>\n";
		
		return $js;
	}
	
	function setNameId( $name ) {
		$nameSpan = $name;
	}
	
	function setSpanOpened($openSpan) {
		$this->openSpan = $openSpan;
	}
	
	function setValue($code, $aditionalCode) {
		$this->code = $code;
		$this->aditionalCode = $aditionalCode;
	}
	
	function _getCode() {
		GLOBAL $SISCONF;
		
		$code = $this->code;
		
		if ( $this->aditionalCode != "" ) {
			if ( $this->nameSpan != "" ) {
				$name = $this->nameSpan;
			} else {
				$name = rand(1, 1000);
			}
			
			$display = "none";
			if ( $this->openSpan == true ) {
				$display = "";
			}
			
			$styleImg = "STYLE=\"{cursor: pointer;}\"";
			$addressImg = $SISCONF['SIS']['COMUM']['IMAGENS_TEMA'];
			$nameImg = $this->openSpan ? "close" : "open";
			$js = "onClick=\"invertView('".$name."')\"";
			$img = "<IMG ID=\"i".$name."\" SRC=\"".$addressImg.$nameImg.".gif\" ".$styleImg." ".$js."> ";
			
			$code = $img.$code."\n<BR>"."<SPAN ID=\"".$name."\" STYLE=\"{display:".$display.";}\">".$this->aditionalCode."</SPAN>";
		}
		
		return $code;;
	}
}
?>