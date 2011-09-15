<?php

//classe que manipula arquivos e diretorios
class formDir{
	//extensão dos arquivos a serem listados pela classe
	var $filtro_extensao;
	//diretório onde se encontra os arquivos
	var $diretorio;
	// nome dos arquivos a serem excluídos
	var $arquivos;
	
	function formDir() {
		$this->diretorio = "/usr/local/apache/htdocs/modulos/financeiro/retorno/";
		$this->arquivos = array();
	}
	
	function listaArquivos($filtrar = false) {		
		$dir_atual = getcwd();
		
		$tabela = "<TABLE >";
		$tabela .= "<TR><TD class=cadastro_cabecalho></TD>";
			
		if ($handle = opendir($this->diretorio)) {		    		    
			$nome_referencia = DATE("YmdH")-24;						
			$cont = 0;
			
			//percorre o diretorio
		    while (false !== ($file = readdir($handle))) {
				if ($filtrar == true) {				    
					//percorre o nome do arquivo de traz para frente p/ verificar a extensão do arquivo
					$texto = "";
					$i = strlen($file);
					while($i >= 0){
						$c = substr($file,$i,1);
						
						if (($c == ".") && ($texto == $this->filtro_extensao)) {
							$i = 0;
							if (substr($file,0,10) <= $nome_referencia) {
								$cont++;
								$this->arquivos[$cont] = $file;								 
							}
						} else {
							$texto = $c.$texto;
						}
						$i--;
					} // while
				} else {
					$tabela .= "<TR><TD class=cadastro_cabecalho ALIGN=LEFT>.<A HREF=$HTTP_URI?acao=verificar_arquivo&arquivo=".$this->diretorio."$file>$file\n</A>.</TD></TR>";
				}
		    }
		    closedir($handle);						
		}
		
		$tabela .="</TABLE>";
		echo $tabela;
	}
	
	//recebe um array contendo o nome dos arquivos a serem excluídos
	function excluiArquivos() {
		foreach(array_keys($this->arquivos) as $k) {
			$file = $this->arquivos[$k];
			$acao = unlink($this->diretorio.$file);		
		}		
	}
}

?>