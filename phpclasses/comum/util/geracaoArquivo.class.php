<?php
require_once "comum/tela/package_message.class.php";
require_once "comum/sessao/configsis.inc.php";
require_once "comum/util/package_utils.class.php";

	//classe que possui metodos de tratamento de arquivos para leitura e escrita
class geracaoArquivo{
     var $_file;
     var $_file_extension;
     var $filename;
     var $filepointer;
     var $_default_path; //Este path deve ter permissao de escrita para o PHP,
     // e estar visível via web (para download)
     var $_default_http_path; //Endereço de download dos arquivos
     var $erros;
     var $noDateInFile;
     var $dlFile;

	//metodo construtor da classe    
	function geracaoArquivo(){
	         $this->clearFields();
	         $this->resetFields();
	}
	
	//metodo que limpa as propriedades da classe    
	function clearFields(){
	         $this->_file = "";
	         $this->filename = "";
	         $this->_default_path = "";
	         $this->_default_http_path = "";
	         $this->erros = "";
	         $this->_file_extension = "rem";
	         $this->noDateInFile = false;
	         $this->dlFile = "";
	}
	
	//metodo que inicia as propriedades da classe    
	function resetFields(){
	         GLOBAL $SISCONF;
	         $this->_file = "";
	         $this->_default_path = $SISCONF['SIS']['INTRANET']['PROGRAMA']."financeiro/remessa/";
	         $this->_default_http_path = $SISCONF['SIS']['INTRANET']['HOST'] . "/portal/modulos/financeiro/remessa/";
	         $this->_file_extension = "rem";
	         $this->noDateInFile = true;
	 }
	
	 //metodo que seta a extensao do arquivo	    
	 function setExtension($extension){
	         $this->_file_extension = $extension;
	 }
	
	 //metodo que seta o nome do arquivo    
	 function setName($name){
	         $this->_file = $name;
	 }
	
	 //metodo que busca o caminho do arquivo   
	 function getFullPathFile(){
	         return $this->filename;
	 }
	
	 //metodo que abre o arquivo   
	 function openFile(){
	        $this->errors = "Não foi possível abrir o arquivo ".$this->filename;
	        $this->filepointer = @fopen($this->filename, "w")
	    	or die($this->errors);
	        $this->errors = "";
	  }
	  
	  //metodo que abre o arquivo para leitura
	  function openFileRead($arquivo){
			$this->filepointer = @fopen($arquivo, "r")or die("nao abriu");
	  }  
	
	 //metodo que le uma parte de um arquivo
	 function stringFile($fp,$inicio,$fim){
			$string = "";
			$buffer = fread($fp,$inicio-1);
			$i = 0;
			while($fim > $i){
				$buffer = fgetc($fp);
				$string .= $buffer;
				$i++;
			} // while
			$this->closeFile();
			return $string;
	}
	 
	
	 //metodo que grava uma string no arquivo   
	 function writeFile($textString){
	         $textString = $textString;
	         if ($this->filepointer){
	             fputs($this->filepointer,$textString.chr(13).chr(10));
	         }
	 }
	 
	 //metodo que fecha o arquivo  
	 function closeFile(){
	         if ($this->filepointer){
	             fclose($this->filepointer);
	         }
	
	 }
	
	 //metodo que monta o nome do arquivo   
	 function makeFilename(){
	         if ($this->noDateInFile == true){
	             $this->_file = $this->_file . "." . $this->_file_extension;
	             }else{
	             $dt = date('YmdHis');
	             $this->_file = $this->_file . $dt . "." . $this->_file_extension;
	         }
	         $this->_default_http_path = $this->_default_http_path . $this->_file;
	         $this->filename = $this->_default_path . $this->_file;
	  }
	
	 //metodo que adiciona um erro no array de erros   
	 function addErro($erro){
	         array_push($this->erros, $erro . "<BR>");
	 }
	
	 //metodo que monta uma lista com os arquivos    
	 function listDownloadFile(){
	         GLOBAL $SISCONF;
	         $dln = "<TABLE CLASS=download BORDER=0 CELLPADDING=2 CELLSPACING=0 ALIGN=CENTER>";
	         $dln .= "<TR>";
	         $dln .= "<TD CLASS=download ALIGN=CENTER><B>Download do Arquivo de Remessa</B><BR>
				Clique com o botão direito do mouse e escolha 'Salvar destino como...'</TD>";
	         $dln .= "</TR>";
	         $dln .= "<TR>";
	         $dln .= "<TD CLASS=download ALIGN=CENTER>
				<IMG SRC=\"" . $SISCONF['SIS']['INTRANET']['THEMA']['HOST'] . "disco2.gif\">
				<A HREF=\"" . $this->_default_http_path . "\">" . $this->_default_http_path . "</A></TD>";
	         $dln .= "</TR>";
	         $dln .= "</TABLE>";
	         $this->dlFile = $dln;
	  }
	
	 //metodo que mostra os erros encontrados   
	 function showErrors(){
	         $mens = "";
	         $k = count($this->erros);
	         if ($k > 0){
	             $mens = "Os seguintes erros foram verificados:<BR><BR><P ALIGN=LEFT>";
	             for ($i = 0; $i < $k;$i++){
	                 $mens .= "  =>" . $this->erros[$i] . "";
	                 }
	             $mens .= "</P>";
	             $msg = new message();
	             $msh = $msg->getMessage($mens, ERROR);
	             echo $msh;
	             }
	 }
	
	 //metodo que repete um determinado caracter quantas vezes for indicado   
	 function charRepeat($char, $loops = 1)
	 {
     	$result = "";
        $char = trim($char);
        $l = strlen($char);
        if ($l > 0){
        	$char = substr($char, 0, 1);
        }elseif ($l == 0){
            $char = " ";
        }

        for ($i = 1; $i <= $loops; $i++){
            $result .= $char;
        }
        
        return $result;
     }
	
	 //metodo que completa uma sring com zeros a esquerda   
	function zerosLeft($strstr, $size = 1)
	{
        $strstr = trim($strstr);
        $l = strlen($strstr);
		$k = 0;     
	 	if ($l > $size){
            $strstr = substr($strstr, 0, $size);
        }elseif ($l < $size){
            $k = $size - $l;
        }
   		$strstr = $this->charRepeat("0", $k) . $strstr;
        return $strstr;
	}
	
	//metodo que completa uma string com zeros a direita   
	function zerosRight($strstr, $size = 1)
	{
    	$strstr = trim($strstr);
    	$l = strlen($strstr);
    	$k = 0;
 		if ($l > $size){
        	$strstr = substr($strstr, 0, $size);
        }elseif ($l < $size){
            $k = $size - $l;
        }
 		$strstr = $strstr . $this->charRepeat("0", $k);
 		return $strstr;
 	}
	
	 //metodo que completa com espacos em branco a direita   
	 function spacesRight($strstr, $size = 1){
	         $strstr = trim($strstr);
	         $l = strlen($strstr);
			 $k = 0;
			 if ($l > $size){
	            $strstr = substr($strstr, 0, $size);
	             }elseif ($l < $size){
	             $k = $size - $l;
	             }
			 $strstr = $strstr . $this->charRepeat(" ", $k);
			 return $strstr;
	  }
	
	 //metodo que converte um valor    
	 function convertValue($value){
	 //NÃO MEXER NESTA FUNÇÃO!!!
		$result = false;
		$v = array(0 => '0', 1 => '0');
		$pos = strrpos($value, ".");
		if ($pos === false){
			$pos = strrpos($value, ","); 
			if ($pos === false){
				$v[0] = $value;
				$v[1] = 0;
			}else{
				$v = explode(",", $value);
			}
		}else{
			$v = explode(".", $value);
		}
		if (is_array($v)){
			$result = $v[0] . $this->zerosRight($v[1], $size = 2);
		}
		return $result;
	 }
	
	 //metodo que formata uma data   
	 function formatDate($date){
	         $dt = explode("/", $date);
	         $ndt = $this->zerosLeft($dt[0], 2) . $this->zerosLeft($dt[1], 2) . $this->zerosLeft($dt[2], 2);
	         return $ndt;
	 }

	 //metodo que formata uma data   
	 function formatDate2($date){
	         $dt = explode("/", $date);
	         $ndt = $this->zerosLeft($dt[0], 2) . $this->zerosLeft($dt[1], 2) . $this->zerosLeft($dt[2], 4);
	         return $ndt;
	 }

	 
	 //metodo que formata uma data DDMMAAA para DD/MM/AAAA 
	 function formatDateDDMMAAAA($date){
	         $dia = substr($date, 0, 2);
			 $mes = substr($date, 2, 2);
			 $ano = substr($date, 4, 7);
			 $ndt = $dia."/".$mes."/".$ano;
	         return $ndt;
	 }
	
	 //metodo que transforma uma string    
	 function toAscii($string){
	         // O objetivo desta função é converter uma string<=255
	        // caracteres para o padrão ascii abaixo de 127 tornando-a
	        // sem acentuação, possibilitando a impressão em impressoras
	        // matriciais sem quaisquer outras configurações
	        $w = "";
	        $l = "";
	        $t = 0;
	        $t = strlen($string);
		 if ($t > 0){
	             for ($i = 0; $i < $t; $i++){
	                 $l = substr($string, $i, 1);
	                 $oc = ord($l);
	                 if ($oc > 126){
	                     switch($oc){
	                             case 192: $l = "A";
	                        break;
	                     case 193: $l = "A";
	                        break;
	                     case 194: $l = "A";
	                        break;
	                     case 195: $l = "A";
	                        break;
	                     case 196: $l = "A";
	                        break;
	                     case 197: $l = "A";
	                        break;
	                     case 198: $l = "A";
	                        break;
	                     case 199: $l = "C";
	                        break;
	                     case 200: $l = "E";
	                        break;
	                     case 201: $l = "E";
	                        break;
	                     case 202: $l = "E";
	                        break;
	                     case 203: $l = "E";
	                        break;
	                     case 204: $l = "I";
	                        break;
	                     case 205: $l = "I";
	                        break;
	                     case 206: $l = "I";
	                        break;
	                     case 207: $l = "I";
	                        break;
	                     case 208: $l = "E";
	                        break;
	                     case 209: $l = "N";
	                        break;
	                     case 210: $l = "O";
	                        break;
	                     case 211: $l = "O";
	                        break;
	                     case 212: $l = "O";
	                        break;
	                     case 213: $l = "O";
	                        break;
	                     case 214: $l = "O";
	                        break;
	                     case 215: $l = "x";
	                        break;
	                     case 217: $l = "U";
	                        break;
	                     case 218: $l = "U";
	                        break;
	                     case 219: $l = "U";
	                        break;
	                     case 220: $l = "U";
	                        break;
	                     case 221: $l = "Y";
	                        break;
	                     case 223: $l = "b";
	                        break;
	                     case 224: $l = "a";
	                        break;
	                     case 225: $l = "a";
	                        break;
	                     case 226: $l = "a";
	                        break;
	                     case 227: $l = "a";
	                        break;
	                     case 228: $l = "a";
	                        break;
	                     case 229: $l = "a";
	                        break;
	                     case 230: $l = "a";
	                        break;
	                     case 231: $l = "c";
	                        break;
	                     case 232: $l = "e";
	                        break;
	                     case 233: $l = "e";
	                        break;
	                     case 234: $l = "e";
	                        break;
	                     case 235: $l = "e";
	                        break;
	                     case 236: $l = "i";
	                        break;
	                     case 237: $l = "i";
	                        break;
	                     case 238: $l = "i";
	                        break;
	                     case 239: $l = "i";
	                        break;
	                     case 241: $l = "n";
	                        break;
	                     case 242: $l = "o";
	                        break;
	                     case 243: $l = "o";
	                        break;
	                     case 244: $l = "o";
	                        break;
	                     case 245: $l = "o";
	                        break;
	                     case 246: $l = "o";
	                        break;
	                     case 247: $l = "o";
	                        break;
	                     case 248: $l = "o";
	                        break;
	                     case 249: $l = "u";
	                        break;
	                     case 250: $l = "u";
	                        break;
	                     case 251: $l = "u";
	                        break;
	                     case 252: $l = "u";
	                        break;
	                     case 253: $l = "y";
	                        break;
	                     case 254: $l = "b";
	                        break;
	                     case 255: $l = "y";
	                        break;
	                     default: $l = " ";
	                        break;
	                         } // switch
	                     } // if
	               $w .= $l;
	                 }
	             }
	         return $w;
	 }
	
	 //metodo que calcula o modulo 11 conforme padrao BB   
	 function CALCMOD11_BCOBRASIL($numero){
	         $mod = new modulo11();
	         return $mod->CALCMOD11_BCOBRASIL($numero);
	 }
	
	 //metodo que calcula o modulo 11 no padrao santander   
	 function modulo11_Santander($numero){
	         $mod = new modulo11();
	         return $mod->modulo11Padrao($numero);
	 }    
}
?>