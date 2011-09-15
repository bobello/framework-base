<?php
class itemMenu{

    var $menuItem;
	
	function itemMenu(){
		$this->menuItem = array();
		$this->menuItem['NOME'] = "";
		$this->menuItem['NOME_ACESSO_RAPIDO'] = "";
		$this->menuItem['LNK'] = "0";
		$this->menuItem['PROG'] = "0";
		$this->menuItem['MOD'] = "0";
		$this->menuIten['TARGET'] = "";
		$this->menuIten['IMG'] = "";
	}
	
	function getItemMenu($descricao, $link, $prgId = 0, $modId = 0, $target = "", $img = "", $nomeAcessoRapido = ""){
		$this->menuItem = array();
		$this->menuItem['NOME'] = $descricao;
		$this->menuItem['NOME_ACESSO_RAPIDO'] = $nomeAcessoRapido;
		$this->menuItem['LNK'] = $link;
		$this->menuItem['PROG'] = $prgId;
		$this->menuItem['MOD'] = $modId;
		$this->menuItem['TARGET'] = $target;		
		$this->menuItem['IMG'] = $img;
		
		return $this->menuItem;
	}
}
?>