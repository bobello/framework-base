<?php
	require_once "comum/sessao/package_sistema.class.php";
	require_once "comum/util/debug.class.php";
	require_once "comum/tela/package_message.class.php";
	require_once "comum/util/package_utils.class.php";
	require_once "comum/tela/package_form.class.php";	
	
//classe generica que instancia os objetos padroes
class system {

	//atributos da classe system
	var $numeric;
	var $dateFormat;
	var $StringFormat;
	var $dateTime;
	var $form;
	var $debug;
	var $message;
	
	//metodo constructor da classe
	function system(){
	
		//instancia as classses comuns		
		$this->numeric = new numeric();
		$this->dateFormat = new dateFormat();
		$this->StringFormat = new StringFormat();
		//$this->dateTime = new dateTime();
		$this->form = new form();
		$this->debug = new debug();
		$this->msgbox = new msgbox();

	}	
}
?>