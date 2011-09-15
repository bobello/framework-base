<?php
class calendar{
    var $D;
    var $M;
    var $Y;
    var $H;
    var $I;
    var $pD;
    var $pM;
    var $pY;
    var $mes;
    var $parametersDiasNormais = array();
    var $retorno;
    var $corDiasSemana;
    var $mostrarNavegadordeMes=TRUE;
    var $datasEspeciais = array();
    var $linkDiaEspecial ="";
    var $parametersDiasEspeciais = array();
    var $gerarCheckBox = FALSE;
    var $gerarMarcadorDia = FALSE;
    var $JS;
    var $diasSelecionados = array();

    function calendar($requests){
        $this->D = date('d');
        $this->M = date('m');
        $this->Y = date('Y');
        $this->H = date('h');
        $this->I = date('i');

        $this->pD = $requests['d'];
        $this->pM = $requests['m'];
        $this->pY = $requests['y'];

        GLOBAL $SGU_INTERNET;
        if ($SGU_INTERNET == "PORTAL_PROF") {
            $this->corDiasSemana = "#D0DCE6";
        }else{
            $this->corDiasSemana = "#483D8B";
        }

        if (!is_numeric($this->pD)) {$this->pD = $this->D;}
        if (!is_numeric($this->pM)) {$this->pM = $this->M;}
        if (!is_numeric($this->pY)) {$this->pY = $this->Y;}

        $this->diasSelecionados = $requests["diasSelecionados"]; //array com os dias que virão selecionados no calendário.
        //echo "<pre>"; print_r($this->diasSelecionados); echo "</pre>";
    }

    function addParDiaNormal($parameter, $value){
        $tmpP = array();
        $tmpP['PARAM'] = $parameter;
        $tmpP['VALUE'] = $value;

        array_push($this->parametersDiasNormais, $tmpP);

    }

    function addDataEspecial($data){
        array_push($this->datasEspeciais, $data);
    }

    function addParDiaEspecial($parameter, $value){

        $tmpP = array();
        $tmpP['PARAM'] = $parameter;
        $tmpP['VALUE'] = $value;
        array_push($this->parametersDiasEspeciais, $tmpP);

    }

    function setJSporDia($JS){
        $this->JS = $JS;
    }

    function getFuncaoJSMarcarTodososDiasdoMes( $nameForm )  {
        $script = "<script language = 'javascript'>".
                        "   function marcarTodososDiasdoMes( anomes  , obj ){\n".
                        "       var teste = new String(  anomes );\n".
                        "       var pSolic = teste.split( '_',3);\n".
                        "       var dia = new String();\n".
                        "       var peloMenosUmMarcado = new Boolean(false);\n".
                        "       for(a=0;a<document.".$nameForm.".elements.length;a++) {".
                        "           var teste = new String(document.".$nameForm.".elements[a].name) ;".
                        "           var pDia = teste.split( '_' , 4);\n".
                        "           if ((pDia[0]=='DIA') && (pDia[2]==pSolic[0]) && (pDia[3]==pSolic[1])) {\n".
                        "               if(document.".$nameForm.".elements[a].checked == true){".
                        "                   peloMenosUmMarcado = true;".
                        "               }".
                        "           }\n".
                        "       }\n".
                        "       for(a=0;a<document.".$nameForm.".elements.length;a++) {".
                        "           var teste = new String(document.".$nameForm.".elements[a].name) ;".
                        "           var pDia = teste.split( '_' , 4);\n".
                        "           if ((pDia[0]=='DIA') && (pDia[2]==pSolic[0]) && (pDia[3]==pSolic[1])) {\n".
                        "               if (peloMenosUmMarcado == true){\n".
                        "                   document.".$nameForm.".elements[a].checked = false;\n".
                        "               }else{\n".
                        "                   document.".$nameForm.".elements[a].checked = true;\n".
                        "               }".
                        "           }\n".
                        "       }\n".
                        "   }\n".
                        "</script>";



        return $script;
    }

    function gerar(){
        $this->mes = null;
        $additionalParamIDiasNormais = "";
        for($i=0; $i<count($this->parametersDiasNormais); $i++){
            $additionalParamNormal .= "&".
                $this->parametersDiasNormais[$i]['PARAM'].
                "=".
                $this->parametersDiasNormais[$i]['VALUE'];
        }

        $additionalParamIDiasEspeciais = "";
        if (count($this->datasEspeciais) > 0) {
            ksort($this->datasEspeciais);
            for($i=0; $i<count($this->parametersDiasEspeciais ); $i++){
                $additionalParamIDiasEspeciais .= "&".
                    $this->parametersDiasEspeciais[$i]['PARAM'].
                    "=".
                    $this->parametersDiasEspeciais[$i]['VALUE'];
            }
        }

        $fno = "<FONT STYLE=\"{font-size: 7pt; font-family: tahoma;}\">";
        $fnt = "<FONT SIZE=1 FACE=TAHOMA COLOR=".$this->corDiasSemana.">";
        $fnc = "</FONT>";

        /* AZULAO */
        //$c1 = " BGCOLOR=#091CA9 "; //FAF9A0
        //$c2 = " BGCOLOR=#043AFF ";
        //$lcls = " STYLE=\"{color:#FFFFFF; text-decoration: none; font-weight: normal;  font-style: normal}\"";

        /* CINZA */
        //$c1 = " BGCOLOR=#a0a0a0 "; //FAF9A0
        //$c2 = " BGCOLOR=#f0f0f0 ";
        //$lcls = " STYLE=\"{color:#000000; text-decoration: none; font-weight: normal;  font-style: normal}\"";

        GLOBAL $SGU_INTERNET;

        if ($SGU_INTERNET == "PORTAL_PROF") {
            $c1 = " BGCOLOR=#D2D9E3 "; //FAF9A0
            $c2 = " BGCOLOR=#D0DCE6 ";
            $c3 = " BGCOLOR=#363635 ";
        }else{
            /* BRANCO-AZUL */
            $c1 = " BGCOLOR=#0A4DD8 "; //FAF9A0
            $c2 = " BGCOLOR=#f0f0f0 ";
            $c3 = " BGCOLOR=#f0f0f0 ";
        }

        if ($SGU_INTERNET == "PORTAL_PROF") {
            $lcls                 = " STYLE=\"{color:#493E8B; text-decoration: none; font-weight: normal;  font-style: normal}\"";
            $lclsDiaAtual         = " STYLE=\"{color:#493E8B; text-decoration: none; font-weight: 700;  font-style: normal}\"";
            $lclsDiaEspecial      = " STYLE=\"{border-style: solid; border-width: 1px; border-color: #493E8B; color:#493E8B; background-color: #493E8B; padding-left: 2px; text-decoration: none; font-weight: 700;  font-style: normal}\"";
            $lclsDiaEspecialAtual = " STYLE=\"{border-style: solid; border-width: 1px; border-color: #493E8B; color:#493E8B; background-color: #493E8B; padding-left: 2px; text-decoration: none; font-weight: 700;  font-style: normal}\"";
        }else{
            $lcls                 = " STYLE=\"{color:#0A4DD8; text-decoration: none; font-weight: normal;  font-style: normal}\"";
            $lclsDiaAtual         = " STYLE=\"{color:#FF0000; text-decoration: none; font-weight: 700;  font-style: normal}\"";
            $lclsDiaEspecial      = " STYLE=\"{border-style: solid; border-width: 1px; border-color: #0060A8; color:#000000; background-color: #FFFFCC; padding-left: 2px; text-decoration: none; font-weight: 700;  font-style: normal}\"";
            $lclsDiaEspecialAtual = " STYLE=\"{border-style: solid; border-width: 1px; border-color: #0060A8; color:#FF0000; background-color: #FFFFCC; padding-left: 2px; text-decoration: none; font-weight: 700;  font-style: normal}\"";
        }

        if ($SGU_INTERNET == "PORTAL_PROF") {
            $corSabadoDomingo = "#D0DCE6";
        }else{
            $corSabadoDomingo = "EEEEFE";
        }

        //$lcls = " STYLE=\"{color:#FF0000; text-decoration: none; font-weight: normal;  font-style: normal}\"";

        $tmpD = 1;
        $tmpM = $this->pM;
        $tmpY = $this->pY;

        $meses[1]="JAN";
        $meses[2]="FEV";
        $meses[3]="MAR";
        $meses[4]="ABR";
        $meses[5]="MAI";
        $meses[6]="JUN";
        $meses[7]="JUL";
        $meses[8]="AGO";
        $meses[9]="SET";
        $meses[10]="OUT";
        $meses[11]="NOV";
        $meses[12]="DEZ";

        GLOBAL $PHP_SELF;

        for ($tmpD=1; $tmpD<=31; $tmpD++){
            if (checkdate($tmpM,$tmpD,$tmpY) == true){
                $day = mktime(0,0,0, $this->pM, $tmpD, $this->pY);
                $dt = getdate($day);
                $dow = $dt['wday'];

                $this->mes[$tmpD]['dow'] = $dow;

                if ($tmpD == $this->pD) {
                    $this->mes[$tmpD]['sel'] = 1;
                }else{
                    $this->mes[$tmpD]['sel'] = 0;
                }
            }
        }

        /*if (count($this->datasEspeciais ) > count($this->mes)) {
            echo "<center><h1><font face=\"Arial, Tahoma\" size = 2 color=\"#FF0000\">Existem mais dias Especiais do que Dias do Mês</font></h1></center>";
            exit;
        }*/

        $sem = 0;
        $diadeHoje=NULL;
        for ($i=1; $i<=count($this->mes); $i++){
            $checked = "";
            if (count($this->diasSelecionados) > 0) {
                if ($i < 10) {
                    $i = "0".$i;
                }
                if ($this->pM < 10) {
                    $this->pM = "0".$this->pM;
                }
                if (in_array($i."/".$this->pM."/".$this->pY , $this->diasSelecionados)) {
                    $checked = "checked";
                }
                if ($i < 10) {
                    $i = substr($i, 1, 2);
                }
                if ($this->pM < 10) {
                    $this->pM = substr($this->pM, 1, 2);
                }
            }
            $dow = $this->mes[$i]['dow'];

            $adParm = $additionalParamNormal;
            if ( date('d/m/Y' ) == $i."/".$this->pM."/".$this->pY ) {

                $estilo = $lclsDiaAtual;

            }else{

                $estilo = $lcls;

            }

            foreach($this->datasEspeciais as $idx => $val ){

                $diaFormatado = ( mb_strlen( $i ) == 1 ? '0'. $i : $i );
                $mesFormatado = ( mb_strlen( $this->pM ) == 1 ? '0'. $this->pM : $this->pM );
                $anoFormatado = ( mb_strlen( $this->pY ) == 2 ? '20'. $this->pY : $this->pY );
                $dataFormatada = $diaFormatado."/".$mesFormatado."/".$anoFormatado ;

                if ( $dataFormatada == $val ) {

                    if ($estilo == $lclsDiaAtual) {
                        $estilo = $lclsDiaEspecialAtual;
                    }else{
                        $estilo = $lclsDiaEspecial;
                    }
                    $adParm = $additionalParamIDiasEspeciais;
                }

            }

             if (($this->gerarMarcadorDia==FALSE) && ($this->gerarCheckBox==FALSE)) {
                $conteudo = "<A ".$estilo." HREF='".$PHP_SELF."?".
                    "d=".$i."&m=".$this->pM."&y=".$this->pY.$adParm."'>".$i."</A>";
            }else{
                $names['id'][$sem][$dow] = "DIA_".$i."_".$this->pM."_".$this->pY;
                if ($this->gerarCheckBox == TRUE){
                    if ( date('d/m/Y' ) == $i."/".$this->pM."/".$this->pY ) {
                        $estilo = $lclsDiaAtual;
                    }

                   $conteudo = "<LABEL $estilo FOR=DIA_".$i."_".$this->pM."_".$this->pY." >".$i."</LABEL><BR>".
                            "<INPUT TYPE ='CHECKBOX' NAME=DIA_".$i."_".$this->pM."_".$this->pY.
                            " ID=DIA_".$i."_".$this->pM."_".$this->pY." VALUE=1 ".$checked.">";
                }else{
                    $conteudo = $i;
                }
            }

            $conteudos['cal'][$sem][$dow]= $conteudo;

            if ($dow >= 6) {
                $sem++;
            }

        }

        $x = (integer)$this->pM;
        if ($x < 2) {
            $mesAnt = 12;
            $anoAnt = (integer) ($this->pY - 1);
            $diaAnt = (integer) $this->pD;
            while(checkdate($mesAnt,$diaAnt,$anoAnt) == false){
                $diaAnt--;
            } // while
        }else{
            $mesAnt = (integer) ($this->pM - 1);
            $anoAnt = (integer) ($this->pY);
            $diaAnt = (integer) $this->pD;
            while(checkdate($mesAnt,$diaAnt,$anoAnt) == false){
                $diaAnt--;
            } // while
        }
        if ($x > 11) {
            $mesPos = 1;
            $anoPos = (integer) ($this->pY + 1);
            $diaPos = (integer) $this->pD;
            while(checkdate($mesPos,$diaPos,$anoPos) == false){
                $diaPos--;
            } // while
        }else{
            $mesPos = (integer) ($this->pM + 1);
            $anoPos = (integer) ($this->pY);
            $diaPos = (integer) $this->pD;
            while(checkdate($mesPos,$diaPos,$anoPos) == false){
                $diaPos--;
            } // while
        }

        $linkAnt = "";
        $linkPos = "";
        if ($this->mostrarNavegadordeMes==TRUE) {
            $linkAnt ="<A $lcls HREF='$PHP_SELF?".
                    "d=$diaAnt&m=$mesAnt&y=$anoAnt".$additionalParam."'>&lt;&lt;</A>";
            $linkPos ="<A $lcls HREF='$PHP_SELF?".
                    "d=$diaPos&m=$mesPos&y=$anoPos".$additionalParam."'>&gt;&gt;</A>";
        }

        $linkAnoAnt = "<A  HREF='$PHP_SELF?".
                "d=".$i."&m=".$this->pM."&y=".($this->pY - 1)."".$additionalParam."'>".($this->pY - 1)."</A>";
        $linkAnoPos = "<A HREF='$PHP_SELF?".
                "d=".$i."&m=".$this->pM."&y=".($this->pY + 1)."".$additionalParam."'>".($this->pY + 1)."</A>";

        $al = " ALIGN=CENTER ";
        $cal = "";

        $cal .= "<TABLE BORDER=0 $c3>";
        $cal .= "<TR>";
        $cal .="<TD>";

        $cal .= "<TABLE BORDER=0 $c1>";
        $cal .= "<TR>";
        $cal .="<TD $c2>";

        $cal .= "<TABLE BORDER=0 $c1 CELLSPACING=0 CELLPADDING=0 STYLE=\"{WIDTH: 150px; HEIGHT: 100px;}\">";
        $cal .= "<TR>";
        $cal .="<TD COLSPAN=7 ALIGN=CENTER $c2>$fno $linkAnt <B>".$meses[$x]."/".$this->pY."</B> $linkPos $fnc</TD>";
        $cal .="</TR>";
        /*
        $cal .= "<TR>";
        $cal .="<TD COLSPAN=3 ALIGN=RIGHT $c2>$fno<B>$linkAnoAnt</B>$fnc</TD>";
        $cal .="<TD $c2></TD>";
        $cal .="<TD COLSPAN=3 ALIGN=LEFT $c2>$fno<B>$linkAnoPos</B>$fnc</TD>";
        $cal .="</TR>";
        */

        if ($this->gerarCheckBox==TRUE){
            $cal .= "<TR>".
                        "<TD COLSPAN=7 ALIGN=CENTER><A href='javaScript:marcarTodososDiasdoMes( \"".$this->pM."_".$this->pY."\",this );'>(Des)Marcar Todo Mês</A>".
                        "</TD>".
                         "</TR>";
        }

        $cal .= "<TR>";
        $cal .= "<TD $al STYLE={background-color:".$corSabadoDomingo."} >$fnt<B>D</B>$fnc</TD>
                <TD $al >$fnt<B>S</B>$fnc</TD>
                <TD $al >$fnt<B>T</B>$fnc</TD>
                <TD $al >$fnt<B>Q</B>$fnc</TD>
                <TD $al >$fnt<B>Q</B>$fnc</TD>
                <TD $al >$fnt<B>S</B>$fnc</TD>
                <TD $al STYLE={background-color:".$corSabadoDomingo."}>$fnt<B>S</B>$fnc</TD>";
        $cal .= "</TR>";

        //echo $meses[$x]."-".$this->pY."-".count($this->mes['cal'])."<br>";

        if (count($conteudos['cal'])<6){
            if($this->gerarCheckBox == TRUE ){
                $tamanho = "30";
            }else{
                $tamanho = "10";
            }
            for($contador = 1;$contador<=6-count($conteudos['cal']);$contador++){
                $cal .= "<TR>".
                        "<TD STYLE={background-color:".$corSabadoDomingo."} ></TD><TD COLSPAN=5 height=".$tamanho.">&nbsp".
                        "</TD><TD STYLE={background-color:".$corSabadoDomingo."} ></TD>".
                        "</TR>";
            }
        }

        for ($i=0; $i<=count($conteudos['cal']);$i++){
            $cal .= "<TR>";
            for ($v=0; $v<7;$v++){
                $name="";
                $js = "";
                if($this->JS!=""){
                    $js = $this->JS;
                }
                if (($this->gerarMarcadorDia == TRUE) && ($this->gerarCheckBox == FALSE)) {
                    $name = "NAME = ".$names['id'][$i][$v]." ID=".$names['id'][$i][$v];
                }
                //border-style:solid;border-color:#AAAAAA;border-width:1px;
                if ((($v==0) || ($v==6))) {
                    $tdColor = "background-color:#".$corSabadoDomingo."";
                }else{
                    $tdColor = "background-color:#F0F4F9";
                }
                $cal .= "\n<TD". $al .$c2." ".$name." ".$js." STYLE={".$tdColor.";}>".$fno.$conteudos['cal'][$i][$v].$fnc."</TD>";
            }
            $cal .= "</TR>";
        }
        $cal .= "</TABLE>";

        $cal .= "</TD>";
        $cal .= "</TR>";
        $cal .= "</TABLE>";

        $cal .= "</TD>";
        $cal .= "</TR>";
        $cal .= "</TABLE>";

        $this->retorno =  $cal;

    }//gerar

    function mostrar(){
        echo $this->retorno;
    }

}
?>