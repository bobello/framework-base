<?php

/**
 * Rotina para a geração de Código de Barra
 * no padrão Interleved 2 of 5 (Intercalado 2 de 5)
 * utilizado para os documentos bancários conforme
 * padrão FEBRABAN.
 * UNILASALLE
 @author  Rudinei Pereira Dias
 @version 0.1
 @date 15/08/2001
 
*/

class barCodeI25{

//Public properties
  var $codigo;       //SET: Código a converter em código de barras
  var $ebf;          //SET: Espessura da barra fina: usar 1 até 2.
  var $ebg;          //SET: Espessura da barra grossa: usar 2x a 3x da esp_barra_fn.
  var $altb;         //SET: altura do código de barras
  var $ipp;          //SET: Endereço completo da imagem do ponto PRETO p/compor o código de barras
  var $ipb;          //SET: Endereço completo da imagem do ponto BRANCO p/compor o código de barras
  var $barra_html;   //Propriedade de do RETORNO do código de barras em HTML
  var $mensagemErro; //Propriedade de do RETORNO da Mensagem de Erro
  var $tamanhoTotal; //Propriedade de RETORNO do tamanho total da imagem do código de barras

//Private properties
  var $mixed_code;
  var $bc = array();
  var $bc_string;

  function barCodeI25(){
     //Construtor da classe
     $this->codigo = "";
     $this->ebf = 1;
     $this->ebg = 3;
     $this->altb = 50;
     $this->ipp = "imagens/ponto_preto.gif";
     $this->ipb = "imagens/ponto_branco.gif";
     $this->mixed_code = "";
     $this->mensagemErro = "";
     $this->bc_string = "";
     $this->tamanhoTotal = 0;
  }

  function gera(){

     $this->codigo = trim($this->codigo);

     if (strlen($this->codigo)>0) {

        $th = "";
        $new_string = "";
        $lbc = 0; $xi = 0; $k = 0;
        $this->bc_string = $this->codigo;

        //define barcode patterns
        //0 - Estreita    1 - Larga
        //Dim bc(60) As String   Obj.DrawWidth = 1

        $this->bc[0] = "00110";          //0 digit
        $this->bc[1] = "10001";          //1 digit
        $this->bc[2] = "01001";          //2 digit
        $this->bc[3] = "11000";          //3 digit
        $this->bc[4] = "00101";          //4 digit
        $this->bc[5] = "10100";          //5 digit
        $this->bc[6] = "01100";          //6 digit
        $this->bc[7] = "00011";          //7 digit
        $this->bc[8] = "10010";          //8 digit
        $this->bc[9] = "01010";          //9 digit
        $this->bc[10] = "0000";          //pre-amble
        $this->bc[11] = "100";           //post-amble

        $this->bc_string = strtoupper($this->bc_string);
		
        if ((strlen($this->bc_string) % 2) != 0){
             $this->bc_string = "0" . $this->bc_string;
             $this->barra_html = "Tamanho do Código de Barras Inválido.";
        }else{

          $lbc = strlen($this->bc_string) - 1;

          //Gera o código com os patterns
          for ($xi=0; $xi<= $lbc; $xi++){
              $k = (int) substr($this->bc_string,$xi,1);
              $new_string = $new_string . $this->bc[$k];
          }

          $this->bc_string = $new_string;

          //Faz a mixagem do Código
          $this->mixCode();

          $this->bc_string = $this->bc[10] . $this->bc_string .$this->bc[11];  //Adding Start and Stop Pattern

          $lbc = strlen($this->bc_string) - 1;

          $this->barra_html="";

          for ($xi=0; $xi<= $lbc; $xi++){
              $imgBar = "";
              $imgWid = 0;

              //barra preta, barra branca
              if ($xi % 2 == 0){ $imgBar = $this->ipp; }else{ $imgBar = $this->ipb; }

              if ($this->bc_string[$xi]=="0"){ $imgWid =  $this->ebf; }else{ $imgWid =  $this->ebg; }

              //criando as barras
              $this->barra_html = $this->barra_html .
                   "<img src=\"". $imgBar ."\" width=\"". $imgWid ."\" height=\"". $this->altb ."\" border=\"0\">";
              $this->tamanhoTotal = $this->tamanhoTotal + $imgWid;
          }
          $this->tamanhoTotal = (int) ($this->tamanhoTotal * 1.1);

        }

     }else{
         $this->barra_html = "<B>Código de Barras Indefinido.</B>";
     }
  }//End of drawBrar

  function mixCode(){
     //Fax a mixagem do valor a ser codificado pelo Código de Barras I25
  //Declaração de Variaveis
     $i = 0; $l = 0; $k = 0;  //inteiro, inteiro, longo
     $s = "";                 //String

     $l = strlen( $this->bc_string );

     if ( ( $l % 5 )!= 0 || ( $l % 2 )!= 0 ){
         $this->barra_html = "<b> Código não pode ser intercalado: Comprimento inválido (mix).</b>";
     }else{
         $s = "";
         for ( $i = 0; $i<= $l; $i += 10 ){
             $s = $s . (isset($this->bc_string[$i])?$this->bc_string[$i]:"") .  (isset($this->bc_string[$i+5])?$this->bc_string[$i+5]:"");
             $s = $s . (isset($this->bc_string[$i+1])?$this->bc_string[$i+1]:"") .  (isset($this->bc_string[$i+6])?$this->bc_string[$i+6]:"");
             $s = $s . (isset($this->bc_string[$i+2])?$this->bc_string[$i+2]:"") .  (isset($this->bc_string[$i+7])?$this->bc_string[$i+7]:"");
             $s = $s . (isset($this->bc_string[$i+3])?$this->bc_string[$i+3]:"") .  (isset($this->bc_string[$i+8])?$this->bc_string[$i+8]:"");
             $s = $s . (isset($this->bc_string[$i+4])?$this->bc_string[$i+4]:"") .  (isset($this->bc_string[$i+9])?$this->bc_string[$i+9]:"");
         }
         $this->bc_string = $s;
     }
  }//End of mixCode

}//End of Class
?>