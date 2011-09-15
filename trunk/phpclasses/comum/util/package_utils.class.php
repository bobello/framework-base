<?php
// package que contem clases com funções uteis
// para validação e conversão
// classe que faz verificações de valores numericos
class Numeric {


// metodo que verifica se um valor é numérico
    function IsNumeric($contents,$verificarEspaco=true) {
        if($verificarEspaco==true) {
            $contents = trim($contents);
        }
        $contents = (string)$contents;
        $l = strlen($contents);
        $countPoints = 0;
        $countComma = 0;
        $result = true;
        if (trim($contents) == "") {
            return false;
        }

        for($i = 0; $i < $l; $i++) {
            $part = substr ($contents, $i, 1);

            switch($part) {
                case '0': break;
                case '1': break;
                case '2': break;
                case '3': break;
                case '4': break;
                case '5': break;
                case '6': break;
                case '7': break;
                case '8': break;
                case '9': break;
                case '.':
                    $countPoints++;
                    break;
                case ',':
                    $countComma++;
                    break;
                default:
                    $result = false;
                    return false;
            } // switch
        }

        $countPoints += $countComma;
        if ($countPoints > 1) $result = false;

        # Testes adicionados, para que a função retorne false caso o valor testado seja somente '.' ou ',':
        if ($result=== true) {
            $result = is_numeric((float)$result);
        }

        if (($result=== true) and (strlen($contents)==1) and (($contents == ".") or ($contents == ","))) {
            $result=false;
        }
        return $result;
    }

    // metodo que verifica se um valor é inteiro
    function IsInteger($contents,$verificarEspaco=true) {

        if($verificarEspaco==true) {
            $contents = trim($contents);
        }
        $contents = (string)$contents;
        $l = strlen($contents);
        $result = true;
        if (trim($contents) == "") {
            return false;
        }

        for($i = 0; $i < $l; $i++) {
            $part = substr ($contents, $i, 1);

            switch($part) {
                case '0': break;
                case '1': break;
                case '2': break;
                case '3': break;
                case '4': break;
                case '5': break;
                case '6': break;
                case '7': break;
                case '8': break;
                case '9': break;
                default:
                    $result = false;
                    return false;
            } // switch
        }

        return $result;
    }

    // função que transforma um número decimal em número romano
    function retornaRomano($valor) {
        $aInteiros = array();
        $aRomanos = array();
        $resultado = "";

        $aInteiros[0] = 1;
        $aInteiros[1] = 4;
        $aInteiros[2] = 5;
        $aInteiros[3] = 9;
        $aInteiros[4] = 10;
        $aInteiros[5] = 40;
        $aInteiros[6] = 50;
        $aInteiros[7] = 90;
        $aInteiros[8] = 100;
        $aInteiros[9] = 400;
        $aInteiros[10] = 500;
        $aInteiros[11] = 900;
        $aInteiros[12] = 1000;

        $aRomanos[0] = "I";
        $aRomanos[1] = "IV";
        $aRomanos[2] = "V";
        $aRomanos[3] = "IX";
        $aRomanos[4] = "X";
        $aRomanos[5] = "XL";
        $aRomanos[6] = "L";
        $aRomanos[7] = "XC";
        $aRomanos[8] = "C";
        $aRomanos[9] = "CD";
        $aRomanos[10] = "D";
        $aRomanos[11] = "CM";
        $aRomanos[12] = "M";

        for ($i=12; $i>=0;$i--) {
            While ($valor >= $aInteiros[$i]) {
                $valor = $valor - $aInteiros[$i];
                $resultado = $resultado.$aRomanos[$i];
            }
        }
        return $resultado;
    }

    // metodo que formata um valor para moeda
    function formataValorBR($val, $num_casa_decimal) {
        $pos = strpos($val,",");
        if($pos !== false) {
            $val = str_replace(".", "", $val);
        }
        $val = str_replace(",", ".", $val);
        $val = (float) $val;
        $val = number_format($val, $num_casa_decimal, ',', '.');
        return $val;
    }

    // metodo que formata um valor para duas casas decimais
    function formataValor2CasasDecimais($val) {
        $val = number_format($val / 100, 2, ',', '');
        return $val;
    }

    // metodo que formata um valor negativo, substituindo o sinal "-" que existe antes do valor por "(<valor>)"
    //OBS: o valor já deve estar formatado para este método funcionar corretamente
    function formataValorNegativo( $valor ) {
        $valor = trim($valor);

        if ( substr($valor, 0, 1) == "-" ) {
            $valor = "(".substr($valor, 1, (strlen($valor)-1)).")";
        }

        return $valor;
    }

    /*
        valorPorExtenso - ? :)
        Copyright (C) 2000 andre camargo

        This program is free software; you can redistribute it and/or modify
        it under the terms of the GNU General Public License as published by
        the Free Software Foundation; either version 2 of the License, or
        (at your option) any later version.

        This program is distributed in the hope that it will be useful,
        but WITHOUT ANY WARRANTY; without even the implied warranty of
        MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
        GNU General Public License for more details.

        You should have received a copy of the GNU General Public License
        along with this program; if not, write to the Free Software
        Foundation, Inc., 675 Mass Ave, Cambridge, MA 02139, USA.

        Andr&eacute;) Ribeiro Camargo (acamargo@atlas.ucpel.tche.br)
        Rua Silveira Martins, 592/102
        Canguçu-RS-Brasil
        CEP 96.600-000
    */

    // funcao............: valorPorExtenso
    // ---------------------------------------------------------------------------
    // desenvolvido por..: andré camargo
    // versoes...........: 0.1 19:00 14/02/2000
    //                     1.0 12:06 16/02/2000
    // descricao.........: esta função recebe um valor numérico e retorna uma
    //                     string contendo o valor de entrada por extenso
    // parametros entrada: $valor (formato que a função number_format entenda :)
    // parametros saída..: string com $valor por extenso

    function valorPorExtenso($valor=0, $moeda=false) {

        if ($moeda) {
            $singular = array("centavo", "real", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("centavos", "reais", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        }else {
            $singular = array("", "", "mil", "milhão", "bilhão", "trilhão", "quatrilhão");
            $plural = array("", "", "mil", "milhões", "bilhões", "trilhões", "quatrilhões");
        }

        $c = array("", "cem", "duzentos", "trezentos", "quatrocentos", "quinhentos", "seiscentos", "setecentos", "oitocentos", "novecentos");
        $d = array("", "dez", "vinte", "trinta", "quarenta", "cinquenta", "sessenta", "setenta", "oitenta", "noventa");
        $d10 = array("dez", "onze", "doze", "treze", "quatorze", "quinze", "dezesseis", "dezessete", "dezoito", "dezenove");
        $u = array("", "um", "dois", "três", "quatro", "cinco", "seis", "sete", "oito", "nove");

        $z=0;

        $valor = number_format($valor, 2, ".", ".");
        $inteiro = explode(".", $valor);

        for($i=0;$i<count($inteiro);$i++)
            for($ii=strlen($inteiro[$i]);$ii<3;$ii++)
                $inteiro[$i] = "0".$inteiro[$i];

        // $fim identifica onde que deve se dar junção de centenas por "e" ou por ","
        $fim = count($inteiro) - ($inteiro[count($inteiro)-1] > 0 ? 1 : 2);

        for ($i=0;$i<count($inteiro);$i++) {
            $valor = $inteiro[$i];
            $rc = (($valor > 100) && ($valor < 200)) ? "cento" : $c[$valor[0]];
            $rd = ($valor[1] < 2) ? "" : $d[$valor[1]];
            $ru = ($valor > 0) ? (($valor[1] == 1) ? $d10[$valor[2]] : $u[$valor[2]]) : "";

            $r = $rc.(($rc && ($rd || $ru)) ? " e " : "").$rd.(($rd &&  $ru) ? " e " : "").$ru;
            $t = count($inteiro)-1-$i;
            $r .= $r ? " ".($valor > 1 ? $plural[$t] : $singular[$t]) : "";

            if ($valor == "000") {
                $z++;
            }elseif ($z > 0) {
                $z--;
            }

            if (($t==1) && ($z>0) && ($inteiro[0] > 0)) {
                $r .= (($z>1) ? " de " : "").$plural[$t];
            }

            if ($r) {
                $rt = $rt . ((($i > 0) && ($i <= $fim) && ($inteiro[0] > 0) && ($z < 1)) ? ( ($i < $fim) ? ", " : " e ") : " ") . $r;
            }
        }

        return($rt ? $rt : "zero");
    }

    /**
     * Retorna um número Ordinal
     *
     * @param int $intNumSeq
     * @return string
     * @access public
     */
    function getNumOrdinal($intNumSeq) {
        $strNumOrdinal = "";
        if(trim($intNumSeq) != "") {
            $strNumOrdinal = $intNumSeq."º";
        }

        return $strNumOrdinal;
}//setaNumOrdinal

}

// classe que contem metodos de conversao de datas
// e funcoes com valores em formato de data
class DateFormat {

    var $dateSize = "";

    // Datas no formato dd/mm/yyyy
    function dateDiff($inicialDate, $finalDate) {
    // echo "Deferençca entre $inicialDate e $finalDate : ";
        if ( !$this->isDate($inicialDate) ) {
        // echo " :dateDiff - Data inicial Inválida!";
            return false;
        }
        if ( !$this->isDate($finalDate) ) {
        // echo " :dateDiff - Data final Inválida!";
            return false;
        }
        $di = explode("/", $inicialDate);
        $df = explode("/", $finalDate);

        $iY = $di[2]; $im = $di[1]; $id = $di[0];   //inicial
        $fY = $df[2]; $fm = $df[1]; $fd = $df[0];   //final

        if (($iY > 2037) or ($fY > 2037)) {
            echo " :dateDiff - Overflow do Ano: Superior a 2037 (Não suportado)";
            return false;
        } else {
            $mkf = (double) mktime(0, 0, 0, $fm, $fd, $fY);
            $mki = (double) mktime(0, 0, 0, $im, $id, $iY);

            $diff = (double) (($mkf - $mki) / 86400);
            // $diff = (double) (( mktime(0,0,0,$fm,$fd,$fY) - mktime(0,0,0,$im,$id,$iY)) / 86400 );
            return round($diff);
        }
    }

    // metodo que verifica se um valor é valido como data
    function isDate($param) {
        $result = false;
        $param = trim($param);

        if (($param != "") || (!empty($param))) {
            $data = explode("/", "$param");

            if ( count($data) != 3 ) {
                return false;
            }

            $d = $data[0];
            $m = $data[1];
            $y = $data[2];

            if ((is_numeric($m)) and (is_numeric($d)) and (is_numeric($y))) {
                $res = checkdate($m, $d, $y);
                if ($res == 1) {
                    $result = true;
                } else {
                    $result = false;
                }
            }else {
                $result = false;
            }

        }
        return $result;
    }

    //retorna o próxima dia útil a partir de hoje
    function retornaProximoDiaUtil() {
        $data = date("d/m/Y", mktime(0, 0, 0, date('m'), date('d')+1, date('Y') ) );
        $ano =  substr("$data", 6, 4);
        $mes =  substr("$data", 3, 2);
        $dia =  substr("$data", 0, 2);
        $diaSemana = date("w", mktime(0, 0, 0, $mes, $dia, $ano) );

        if( $diaSemana == 6 ) {
            $data = date("d/m/Y", mktime(0, 0, 0, date('m'), date('d')+3, date('Y') ) );
        }elseif( $diaSemana == 0 ) {
            $data = date("d/m/Y", mktime(0, 0, 0, date('m'), date('d')+2, date('Y') ) );
        }
        return $data;
    }

    #Valida um horário
    #Formato HH:MM:SS ou HH:MM
    function isTime( $horario , $formatHHMMSS , $formato24Horas ) {
        $pHor = explode(":",$horario);

        $num = new Numeric;
        if($formatHHMMSS==TRUE) {
            if(count($pHor)!=3) {
                return FALSE;
            }
        }else {
            if(count($pHor)!=2) {
                return FALSE;
            }
        }
        foreach($pHor as $lixo => $val) {
            if ($num->IsNumeric($val)==FALSE) {
                return FALSE;
            }
        }
        if ($formato24Horas==TRUE) {
            $limiteIniHoras = 0;
            $limiteFinHoras = 23;
        }else {
            $limiteIniHoras = 1;
            $limiteFinHoras = 12;
        }
        if  (((int)($pHor[0])<$limiteIniHoras)  || ((int)($pHor[0])>$limiteFinHoras)) {
            return FALSE;
        }
        if  (((int)($pHor[1])<0)  || ((int)($pHor[1])>59)) {
            return FALSE;
        }
        if ($formatHHMMSS === TRUE) {
            if  (((int)($pHor[2])<0)  || ((int)($pHor[2])>59)) {
                return FALSE;
            }
        }
        return TRUE;
    }

    // funcao que transforma uma data de Y-M-D para D/M/Y
    function ymdTodmy($param) {
        $param = trim($param);

        if (($param != "") || (!empty($param))) {
            $data = explode("-", "$param");
            $d = $data[2];
            $m = $data[1];
            $y = $data[0];

            $data = $d . "/" . $m . "/" . $y;
            return $data;
        }
    }

    // funcao que transforma uma data de D/M/Y para Y-M-D
    function dmyToymd($param) {
        $param = trim($param);

        if (($param != "") || (!empty($param))) {
            $data = explode("/", "$param");
            $d = $data[0];
            $m = $data[1];
            $y = $data[2];

            $data = $y . "-" . $m . "-" . $d;

            return $data;
        }
    }

    function formatDateBR($orig_data) {
        if ($this->isDate($orig_data)) {
            $data = explode("/","$orig_data");
            $d=$data[0];
            $m=$data[1];
            $y=$data[2];

            if (($y>=0) && ($y < 80)) {
                if(strlen($y) == 1) {
                    $y = "200".$y;
                } elseif(strlen($y) == 2) {
                    $y = "20".$y;
                }
            } elseif (($y>=81) && ($y < 99) ) {
                if(strlen($y) == 2) {
                    $y = "19".$y;
                }
            }

            $res = sprintf("%02d/%02d/%04d", $d, $m, $y);
            return $res;
        }else {
            return "";
        }
    }
}


// classe que contem funcoes de conversoes e verificacoes de
// valores strings
class StringFormat {
    function isString($param) {
        $result = "";

        return $result;
    }

    //========================================================
    /* Esta função serve para concatenar caracteres ZERO
    em uma string qualquer.
    Sintaxe:   Variavel = ACZ (A Integer, B String, C Boolean) String
    Uso: A = Valor inteiro que indica o tamanho final de da String
         B = String que receberá os caracteres
         C = Valor booleano (1,0) que indica a posição de concatenação
         dos caracteres brancos (1 Esquerda, 0 Direita)
    */
    function ACZ($COMP, $KWORD, $POS, $caracter=0) {
        $B = "";
        $KWORD = trim($KWORD);
        If ($COMP < strlen($KWORD)) {return substr($KWORD, 0, $COMP);}
        Else {
            $I = $COMP - strlen($KWORD);
            For($J=1; $J<=$I; $J++) {$B = $B.$caracter;}
            If ($POS) {$ACZ = $B.$KWORD;}
            Else {$ACZ = $KWORD.$B;}
            return $ACZ;

        }

    }

    function ACP($COMP, $KWORD , $POS , $SUBCHAR = "") {

    /* Esta função serve para concatenar caracteres em branco
    em uma string qualquer.
    Sintaxe:   Variavel = ACP (A Integer, B String, C Boolean) String
    Uso: A = Valor inteiro que indica o tamanho final de da String
         B = String que receberá os caracteres
         C = Valor booleano (1,0) que indica a posição de concatenação
             dos caracteres brancos (1 Esquerda, 0 Direita)*/

    # Adicionado Por diegom em 21/06/2005 para corrigir o erro referente ao chamado 27696
    //$KWORD = substr($KWORD,0,($COMP-1));

        $SUBCHAR = Trim($SUBCHAR);


        $B = "";
        if ($SUBCHAR == "") {
            $SUBCHAR = " ";
        }
        else {
            if (mb_strlen($SUBCHAR) > 1) {
                $SUBCHAR = " ";
            }
        }

        if ($COMP < mb_strlen($KWORD)) {
            $ACP = substr($KWORD,0 ,$COMP);
        }
        else {
            $I = $COMP - mb_strlen($KWORD);
            for( $J=1; $J<=$I;$J++) {
                $B = $B . $SUBCHAR;
            }
            if ($POS) {
                $ACP = $B . $KWORD;
            }
            else {
                $ACP = $KWORD . $B;
            }
        }
        return $ACP;

    }

    function right($texto, $tamanho) {
        if ( $tamanho <= 0 ) return "";
        $tam = mb_strlen($texto);

        if ( $tam <= $tamanho ) {
            return $texto;
        }

        $temp = 1;
        if ( $tam > 1 ) $temp = 0;

        return substr($texto, ($tam-$tamanho-$temp));
    }

    //metodo que completa uma sring com zeros a esquerda
    function left($strstr, $size = 1) {
        $strstr = trim($strstr);
        $l = strlen($strstr);
        $k = 0;
        if ($l > $size) {
            $strstr = substr($strstr, 0, $size);
        } elseif ($l < $size) {
            $k = $size - $l;
        }
        $strstr = str_repeat("0", $k) . $strstr;
        return $strstr;
    }

}

// classe que verifica um cpf
class cpf {
    var $expressao_regular_de_cpf = "[0-9]{3}\\.?[0-9]{3}\\.?[0-9]{3}-?[0-9]{2}";

    /**
     * cpf::clim()
     * Tiras espaços e tabulações
     *
     * @param  $cnpj
     * @return
     */
    function clim($cpf) {
        $cpf = preg_replace("^[ ]*[  ]*^", "", $cpf);

        return $cpf;
    }

    public static  function convertCPFToNumber($cpf) {
        if( strlen($cpf) > 8 ) {
            return str_replace(array('-','.'),array('',''), $cpf);
        }
    }

    /**
     * cpf::isNUMB()
     * verifica se digitou so numeros e tem 11 digitos
     *
     * @param  $cnpj
     * @return
     */
    function isNUMB($cpf) {
    // 1 - somente número e tem 11 digitos
    // 0 - não e só número ou não tem 11 digitos
        $digitos = preg_replace("^[-. \t]^", "", $cpf);
        /*echo "Digitos: ".$digitos."<br>";
        echo "Exp: ".$this->expressao_regular_de_cpf."<br>";
        var_dump(ereg("^" . $this->expressao_regular_de_cpf . "\$", $digitos));*/
        if(!preg_match("^" . $this->expressao_regular_de_cpf . "\$^", $digitos)) {
            return 0;
        }
        return 1;
    }

    /**
     * cpf::checaCPF()
     * Função que verifica se o cpf é valido ou não
     *
     * @param  $cpf
     * @return
     */
    function checaCPF($cpf) {
        if (strlen($cpf) != 11 || $cpf == "00000000000" || $cpf == "11111111111" ||
            $cpf == "22222222222" || $cpf == "33333333333" || $cpf == "44444444444" ||
            $cpf == "55555555555" || $cpf == "66666666666" || $cpf == "77777777777" ||
            $cpf == "88888888888" || $cpf == "99999999999")
            return 0;

        $soma = 0;
        for ($i = 0; $i < 9; $i ++)
            $soma += (int)(substr($cpf, $i, 1)) * (10 - $i);

        $resto = 11 - ($soma % 11);
        if ($resto == 10 || $resto == 11)
            $resto = 0;

        if ($resto != (int)(substr($cpf, 9, 1)))
            return 0;

        $soma = 0;
        for ($i = 0; $i < 10; $i ++)
            $soma += (int)(substr($cpf, $i, 1)) * (11 - $i);
        $resto = 11 - ($soma % 11);
        if ($resto == 10 || $resto == 11)
            $resto = 0;

        if ($resto != (int)(substr($cpf, 10, 1)))
            return 0;

        return 1;
    }

    /**
     * cpf::verifica_cpf()
     * Função chamadora para validação do cpf
     *
     * @param  $cpf
     * @return
     */
    function verifica_cpf($cpf) {

        $cpf = $this->clim($cpf);
        if($this->isNUMB($cpf) != 1) {
            return 0;
        }

        if ($this->checaCPF($cpf)) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * cpf::formataCPF()
     * Verifica se o CPF passado é válido, se for retorna a string formatada,
     * caso contrário retornará zero
     *
     * @param  $cpf
     * @return
     */
    function formataCPF($cpf) {
        if ($this->verifica_cpf($cpf) == 1) {
            $tmpCPF = str_replace('.','',$cpf);
            $tmpCPF = str_replace('/','',$tmpCPF);
            $tmpCPF = str_replace('-','',$tmpCPF);
            $cpf = substr($tmpCPF,0,3).".".substr($tmpCPF,3,3).".".substr($tmpCPF,6,3)."-".substr($tmpCPF,9,2);
            return $cpf;
        } else {
            return 0;
        }
    }
}

class cnpj {
    var $expressao_regular_de_cnpj = "[0-9]{2}\\.?[0-9]{3}\\.?[0-9]{3}/?[0-9]{4}-?[0-9]{2}";

    /**
     * cnpj::clim()
     * Tiras espaços e tabulações
     *
     * @param  $cnpj
     * @return
     */
    function clim($cnpj) {
        $cnpj = preg_replace("^[ ]*[ ]*^", "", $cnpj);

        return $cnpj;
    }

    /**
     * cnpj::isNUMB()
     * verifica se digitou so numeros e tem 14 digitos
     *
     * @param  $cnpj
     * @return
     */
    function isNUMB($cnpj) {
    // 1 - somente número e tem 14 digitos
    // 0 - não e só número ou não tem 14 digitos
        $digitos = preg_replace("^[-. \t/]^", "", $cnpj);

        if(!preg_match("^" . $this->expressao_regular_de_cnpj . "\$" . "^", $digitos)) {
            return 0;
        }
        return 1;
    }

    /**
     * cnpj::checaCNPJ()
     * Função que verifica se o cnpj é valido ou não
     *
     * @param  $cnpj
     * @return
     */
    function checaCNPJ($cnpj) {
        if ( strlen($cnpj) != 14 ) {
            return 0;
        }

        $mod11 = new modulo11;

        // Válida Primeiro Dígito
        if ( $mod11->modulo11Padrao(substr($cnpj, 0, 12)) != substr($cnpj, 12, 1) ) {
            return 0;
        }

        // Válida Segundo Dígito
        if ( $mod11->modulo11Padrao(substr($cnpj, 0, 13)) != substr($cnpj, 13, 1) ) {
            return 0;
        }

        return 1;
    }

    /**
     * cnpj::verifica_cnpj()
     * Função chamadora para validação do cnpj
     *
     * @param  $cnpj
     * @return
     */
    function verifica_cnpj($cnpj) {
        $cnpj = $this->clim($cnpj);
        if($this->isNUMB($cnpj) != 1) {
            return 0;
        }

        if ( $this->checaCNPJ($cnpj) ) {
            return 1;
        } else {
            return 0;
        }
    }

    /**
     * cpf::formataCNPJ()
     * Verifica se o CNPF passado é válido, se for retorna a string formatada,
     * caso contrário retornará zero
     *
     * @param  $cnpf
     * @return
     */
    function formataCNPJ($cnpf) {
        if ($this->verifica_cnpj($cnpf) == 1) {
            $tmpCNPJ = str_replace('.','',$cnpf);
            $tmpCNPJ = str_replace('/','',$tmpCNPJ);
            $tmpCNPJ = str_replace('-','',$tmpCNPJ);
            $cnpf = substr($tmpCNPJ,0,2).".".substr($tmpCNPJ,2,3).".".substr($tmpCNPJ,5,3)."/".substr($tmpCNPJ,8,4)."-".substr($tmpCNPJ,12,2);
            return $cnpf;
        } else {
            return 0;
        }
    }
}

// calcula o modulo 11 de um numero
class modulo11 {
    function modulo11Padrao($numero) {
        $i = 0;
        $tam = 0;
        $ind = 0;
        $valor1 = 0;
        $valor_total = 0;
        $digito1 = 0;
        $peso = 0;
        $num = 0;

        $peso = 9;
        $valor_total = 0;
        $tam = strlen($numero) - 1;

        for ($i = $tam; $i >= 0; $i--) {
            $num = strval(substr($numero, $i, 1));
            $valor1 = $num * $peso;
            $peso--;

            if ($peso < 2)
                $peso = 9;

            $valor_total += $valor1;
        }

        $resto = $valor_total % 11;

        if ($resto == 10) {
            $digito1 = 0;
        }else {
            if ($resto == 0) {
                $digito1 = 0;
            }else {
                $digito1 = $resto;
            }
        }

        return $digito1;
    }


    function CALCMOD11_BCOBRASIL($seq) {
    // Executa o cálculo MODULO 11
    // Segundo os critérios do Banco Do BRASIL
        $l = 0;
        $i = 0;
        $s = 0;

        $calc = 0;
        $resto = 0;
        $soma = 0;

        $seq2 = "";
        $seq = trim($seq);
        $l = strlen($seq);

        settype($l, "integer");

        $i = $l;
        do {
            $i--;
            $seq2 = $seq2 . substr($seq, $i, 1);
        } while ($i > 0);

        $soma = 0;
        $i = 0;
        $calc = 9;
        $z = 0;

        do {
            $z = substr($seq2, $i, 1);
            settype($z, "integer");
            $s = $z * $calc;
            $soma = $soma + $s;
            $calc = $calc - 1;

            if($calc < 2) {
                $calc = 9;
            }
            $i = $i + 1;
        } while ($i <> ($l + 1));

        $resto = $soma % 11;

        If ($resto == 10) {
            $seq2 = "X";
        }Else {
            $seq2 = $resto;
        }

        if (strlen($seq2) > 1) {
            return "";
        }else {
            return $seq2;
        }
    }
}

// calcula o modulo 10 de um numero
class modulo10 {
//Executa o cálculo MODULO 10
    function modulo10Padrao($Seq) {

        $SEQ2 = trim($Seq);
        $L = mb_strlen($SEQ2)-1;
        $s = 0;
        $SOMA = 0;
        while ($L >= 0) {
            $s = (integer) (substr($SEQ2, $L, 1));
            $s = $s * 2;
            if ( $s > 9) {$s = $s - 9;}
            $SOMA = $SOMA + $s;
            $L = $L - 1;
            if ($L >= 0) {

                $s = (integer)(substr($SEQ2, $L, 1));
                $s = $s * 1;
                if ($s > 9) {$s = $s - 9;}
                $SOMA = $SOMA + $s;
                $L = $L - 1;
            }
        }

        $RESTO = bcmod($SOMA , 10);

        if ($RESTO == 0) {
            $ret = "0";
        }
        else {
            $ret = (string)(bcsub(10 , $RESTO));
        }
        return $ret;
    }
}

class funcoesHtml {
    function cabecalhoPadraoHtml($largura, $alinham, $titulo1, $titulo1_size, $titulo2, $titulo2_size, $titulo3,
        $titulo3_size, $nroPag, $imprimeTituloSistema = true, $imprimeData = true, $imprimeHora = true, $cabUNI=TRUE) {
        GLOBAL $SISCONF;

        $local = $SISCONF['SIS']['COMUM']['IMAGENS'];

        $imgSrc = $local;
        if ( $cabUNI == true ) $imgSrc .= "/unilasalle_pb_pq.jpg";
        else $imgSrc .= "colegio-pb.jpg";

        $tituloSistema = "";
        $data = "";
        $hora = "";
        if ( (int)$nroPag > 0 ) $nroPag = "Pág: ".sprintf("%04d", $nroPag);
        if ( $imprimeTituloSistema ) $tituloSistema = "SGL";
        if ( $imprimeData ) $data = date("d/m/Y");
        if ( $imprimeHora ) $hora = date("H:i");

        $width = "";
        if ($largura != "" ) $width = " WIDTH=".$largura;

        $align = "CENTER";
        if ( $alinham != "" ) $align = $alinham;

        $tamTitulo1 = $titulo1_size;
        if ( (int)$tamTitulo1 == 0 ) $tamTitulo1 = "14";

        $tamTitulo2 = $titulo2_size;
        if ( (int)$tamTitulo2 == 0 ) $tamTitulo2 = "14";

        $tamTitulo3 = $titulo3_size;
        if ( (int)$tamTitulo3 == 0 ) $tamTitulo3 = "12";


        $estilo = " STYLE=\"{font-size:<tamFonte>px; font-family:tahoma; font-weight:normal;}\"";
        $estiloSis = " STYLE=\"{font-size:10px; font-family:tahoma; font-weight:normal;}\"";

        $htmlTitulos = "\n<TABLE BORDER=0 WIDTH=\"99%\" CELLSPACING=0 CELLPADING=0 ALIGN=right>";
        $htmlTitulos .= "\n<TR><TD><FONT ".str_replace("<tamFonte>",$tamTitulo1,$estilo).">".$titulo1."&nbsp;</TD></TR>";
        $htmlTitulos .= "\n<TR><TD><FONT ".str_replace("<tamFonte>",$tamTitulo2,$estilo).">".$titulo2."&nbsp;</TD></TR>";
        $htmlTitulos .= "\n<TR><TD><FONT ".str_replace("<tamFonte>",$tamTitulo3,$estilo).">".$titulo3."&nbsp;</TD></TR>";
        $htmlTitulos .= "\n</TABLE>";

        $htmlSistema = "\n<TABLE BORDER=0 WIDTH=\"99%\" CELLSPACING=0 CELLPADING=0 ALIGN=right><TR>";
        $htmlSistema .= "\n<TD><FONT ".$estiloSis.">".$tituloSistema."&nbsp;</TD>";
        $htmlSistema .= "\n<TD ALIGN=right><FONT ".$estiloSis.">".$data."&nbsp;</TD>";
        $htmlSistema .= "\n<TD WIDTH=5>&nbsp;</TD>";
        $htmlSistema .= "\n<TD><FONT ".$estiloSis.">".$hora."&nbsp;</TD>";
        $htmlSistema .= "\n<TD ALIGN=right><FONT ".$estiloSis.">&nbsp;".$nroPag."</TD>";
        $htmlSistema .= "\n</TR></TABLE>";


        $estiloTabela = "STYLE=\"{border:1px solid black;}\"";
        $html = "<TABLE BORDER=0 ALIGN=\"".$align."\" ".$width." ".$estiloTabela." CELLSPACING=0 CELLPADING=0>";

        $html .= "<TR><TD WIDTH=300 ALIGN=center ROWSPAN=2 VALIGN=top STYLE=\"{border-right:1px solid black;}\">".
            "<IMG SRC=\"".$imgSrc."\" WIDTH=280></TD>";
        $html .= "<TD VALIGN=top STYLE=\"{border-bottom:1px solid black;}\">".$htmlTitulos."</TD></TR>";

        $html .= "<TR><TD>".$htmlSistema."</TD></TR>";

        $html .= "</TABLE>";

        return $html;
    }
}

function checkEmail($strEmail) {
    $conta = "^[a-zA-Z0-9\._-]+@";
    $domino = "[a-zA-Z0-9\._-]+.";
    $extensao = "([a-zA-Z]{2,4})$";

    $pattern = $conta.$domino.$extensao;

    if ( @ereg($pattern, $strEmail) ){
        return true;
    }
    else{
        return false;
    }


}

?>