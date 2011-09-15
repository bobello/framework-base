<?php
require_once "comum/sessao/configsis.inc.php";

class theme {
	var $host;
    var $path;
    var $form_obj;

 	function theme($tema) 
 	{
		$this -> form_obj = array();        
 		$this -> form_obj['FRAME']['LOGO'] = "";
        $this -> form_obj['FRAME']['MENU'] = "";
        $this -> form_obj['FRAME']['ITEM'] = "";
        $this -> form_obj['FRAME']['PROGRAMA'] = "";
	}    
}
?>