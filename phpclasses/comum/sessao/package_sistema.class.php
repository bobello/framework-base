<?php
require_once "comum/banco/package_bd.class.php";
require_once "comum/tela/package_theme.class.php";
require_once "comum/tela/package_message.class.php";
require_once "comum/util/system.class.php";

define("SG_VERSAO", "v1.0");

class programa {
    var $modid;
    var $prgid;
    var $emManutencao;  //Indica se o programa está em manutenção ou não.
    //Programas em manutenção só poderão ser usados
    //pelos máquinas com o IP registrado no configsis
    var $titulo;
    var $DB;
    var $registered;
    var $useDB;
    var $errors;
    var $usu_valido;
    var $usu_permissao;
    var $tema;
    var $_MESSAGER;
    var $menuOn;
    var $_paginaLink;
    var $sisObject;
    var $debug;
    var $pageBackGroundColor = "WHITE";
    var $onLoad;
    var $timer;
    var $classeFundoBody="fundo-sembolha";
    var $usoInterno;
    var $enderecoJS;
    var $enderecoCSS;

    // DESENV function programa($mod, $prg, $manutencao = false, $pageBackGroundColor = "WHITE", $iniciaHTML = true, $onLoad='', $classBody="fundo-sembolha") {
    function programa($mod, $prg, $manutencao = false, $pageBackGroundColor = "WHITE", $iniciaHTML = true, $onLoad='', $usoInterno = true, $classBody="fundo-sembolha") {
        global $_REQUEST;
        if (isset($_REQUEST["iniHTML"])) {
            if ( $_REQUEST["iniHTML"]=="0" ) {
                $iniciaHTML = false;
            }
        }
        $this->timer = new timer();
        $this->emManutencao = $manutencao;
        $this->setPageBackGroundColor($pageBackGroundColor);
        $this->onLoad = $onLoad;
        $this->usoInterno = $usoInterno;
        if ( $iniciaHTML ) {
            $this->exibeMenu();
            $this->_iniciate();
        }
        $this->usu_valido = false;
        $this->usu_permissao = array();
        $this->modid = $mod;
        $this->prgid = $prg;
        $this->registered = false;
        $this->errors = "";
        $this->setUseDB(true);
        if ( $iniciaHTML ) {
            $this->getTema();
        }
        $this->_MESSAGER = new message();
        $this->sisObject = new system();
        $this->debug = false;
        $this->metodoDebug = $_POST;
        $this->enderecoJS["src"] = "";
        $this->enderecoJS["codigo"] = "";
        $this->enderecoCSS["src"] = "";
        $this->enderecoCSS["codigo"] = "";
    }

    function setTimer($status = true) {
    //Altera o Status para exibir os cálculos dos
    //tempos de criação e carga da página
        GLOBAL $SISCONF;
        $SISCONF['SIS']['METRICS']['ACTIVE'] = $status;
    }

    function adicionaCSS( $tipo, $css ) {
        $end = "";
        switch( $tipo ) {
            case "src":
                if ( ( is_array( $css ) ) && ( empty( $css ) == false ) ) {
                    foreach( $css as $chv => $endereco) {
                        $end .= "\n <link rel=\"stylesheet\" type=\"text/css\" href=\"".$endereco."\" />";
                    }
                }
                $this->enderecoCSS["src"] = $end;
                break;

            case "codigo":
                $this->enderecoCSS["codigo"] = $css;
                break;
        }
    }

    function adicionaJS( $tipo, $js ) {
        $end = "";
        switch( $tipo ) {
            case "src":
                if ( ( is_array( $js ) ) && ( empty( $js ) == false ) ) {
                    foreach( $js as $chv => $endereco) {
                        $end .= "\n <script type=\"text/javascript\" src=\"".$endereco."\"></script>";
                    }
                }
                $this->enderecoJS["src"] = $end;
                break;

            case "codigo":
                $this->enderecoJS["codigo"] = $js;
                break;
        }
    }

    function setPrograma($mod, $prg) {
        $this->modid = $mod;
        $this->prgid = $prg;
        $this->registered = true;
    }

    function validaIpManutencao() {
        GLOBAL $SISCONF;
        $mvalido = false;
        $ip = getenv('REMOTE_ADDR');
        for ($i=0; $i<count($SISCONF['SIS']['DEBUG_IP']); $i++) {
            if ($ip==$SISCONF['SIS']['DEBUG_IP'][$i]) { $mvalido = true; }
        }
        return $mvalido;
    }

    //metodo que seta a exibicao do menu
    function exibeMenu($value = true) {
        $this->menuOn = $value;
    }

    //metodo que seta a exibicao do debug
    function setDebug($value = false,$metodo='$_POST') {
        $this->debug = $value;
        $this->metodoDebug = $metodo;
    }


    function setPageBackGroundColor($bgColor = "WHITE") {
        $this->pageBackGroundColor = $bgColor;
    }

    /**
     * Procedimentos de encerramento do programa
     */
    function closeProgram($exibeRodape = true) {
        global $_REQUEST;        
        $this->_finalize($exibeRodape);
    }

    function getTema() {
    // busca um tema para cada usuario
        $this->tema = "default";
    }

    function setUseDB($value) {
        $this->useDB = $value;
    }

    function setActiveDBConnection(&$dbconn) {
        $this->DB = &$dbconn;
        $this->registered = true;
        $this->getProgram();
    }

    function getProgram() {
        if (($this->registered == true) || ($this->useDB == false)) {
            $pqry = new genericQuery($this->DB);

            $pqry->TQuery("SELECT * FROM programa WHERE id_sistema = ".$this->modid);

            echo $pqry->checkerrors();
        } else {
            $this->errors .= "<br />programa:getProgram()->Não foi referenciada a conexão com o banco de dados.";
        }
    }

    function setClasseFundoBody( $fundo ) {
        $this->classeFundoBody = $fundo;
    }

    function _iniciate() {
        GLOBAL $SISCONF;
        GLOBAL $SGU_INTERNET;
        GLOBAL $HTTP_REFERER;

        $linkStilo = "";
        switch ($SGU_INTERNET) {

            default:
                $linkStilo = $SISCONF['SIS']['INTRANET']['THEMA']['HOST'];
                break;
        }

        preg_match('^[a-zA-Z_-]*[.]{1}css$^',$linkStilo,$tmpReg);

        if ( !( isset( $tmpReg[0] ) ) ) {
            $linkStilo .= "styloSGU.css";
        }

        $onLoad = "<script language='javaScript'>
                  function setaFocusFirstElement()
                  {
                    sair = '0';
                    for (indice=0; indice<document.forms.length; indice++) {
                        for (i=0; i<document.forms[indice].elements.length; i++) {
                            if (document.forms[indice].elements[i].type == 'radio') {
                                if (document.forms[indice].elements[i].disabled == false) {
                                    setTimeout('document.forms[indice].elements[i].focus()', 500);
                                    sair = '1';
                                    break;
                                }
                            }
                            if (document.forms[indice].elements[i].type == 'text') {
                                if (document.forms[indice].elements[i].disabled == false) {
                                    if (document.forms[indice].elements[i].readOnly == false) {
                                        setTimeout('document.forms[indice].elements[i].focus()', 500);
                                        sair = '1';
                                        break;
                                    }
                                }
                            }
                            if (document.forms[indice].elements[i].type == 'textarea') {
                                if (document.forms[indice].elements[i].disabled == false) {
                                    if (document.forms[indice].elements[i].readOnly == false) {
                                        setTimeout('document.forms[indice].elements[i].focus()', 500);
                                        sair = '1';
                                        break;
                                    }
                                }
                            }
                            if (document.forms[indice].elements[i].type == 'checkbox') {
                                if (document.forms[indice].elements[i].disabled == false) {
                                    setTimeout('document.forms[indice].elements[i].focus()', 500);
                                    sair = '1';
                                    break;
                                }
                            }
                            if (document.forms[indice].elements[i].type == 'select-one') {
                                if (document.forms[indice].elements[i].disabled == false) {
                                    setTimeout('document.forms[indice].elements[i].focus()', 500);
                                    sair = '1';
                                    break;
                                }
                            }
                        }
                        if (sair == '1') {
                            break;
                        }
                    }
                  }
				  function marcarTodosDivSelecaoCheckBox(form, nomeArray) {
					var index;
					var totalElem = form.elements.length;
					for(index=0; index < totalElem; index++) {
					if ( form.elements[index].type == 'checkbox' ) {
				          InstStr = new String(form.elements[index].name);
				          retorno = InstStr.indexOf(nomeArray);
				          if ( form.elements[index].disabled == false && ( retorno != -1) ) {
				              form.elements[index].checked = true;
				          }
				      }
				  }
				}
				
				function desmarcarTodosDivSelecaoCheckBox(form, nomeArray) {
				  var index;
				  var totalElem = form.elements.length;
				  for(index=0; index < totalElem; index++) {
				      if ( form.elements[index].type == 'checkbox' ) {
				          InstStr = new String(form.elements[index].name);
				          retorno = InstStr.indexOf(nomeArray);
				          if ( form.elements[index].disabled == false && ( retorno != -1) ) {
				              form.elements[index].checked = false;
				          }
				      }
				  }
				}
				   function mostraDivSelecaoCheckBox(idDivElementos, idBotao, nomeBotaoOcultaDiv, nomeBotaoExibeDiv){
				       objElementos = document.getElementById(idDivElementos);
				       objBotao = document.getElementById(idBotao);
				       if (objElementos.style.display == 'none'){
				           objElementos.style.display = 'block';
				           objBotao.value = nomeBotaoOcultaDiv;
				       }else{
				           objElementos.style.display = 'none';
				           objBotao.value = nomeBotaoExibeDiv;
				       }
					   
				   }
                  </script>";

        if(trim($this->onLoad)=="") {
            $this->onLoad = "onload=\"setaFocusFirstElement(); self.focus();\"";
        }

        echo "<html>".
            "<head>".
            "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">".
            "<title>SG | Sistema Gerenciador</title>".
            "<link rel=StyleSheet HREF=\"$linkStilo\" type=\"text/css\">";
        if ($this->enderecoJS["src"] != "") echo $this->enderecoJS["src"];
        if ($this->enderecoCSS["src"] != "") echo $this->enderecoCSS["src"];
        if ($this->enderecoJS["codigo"] != "") echo $this->enderecoJS["codigo"];
        if ($this->enderecoCSS["codigo"] != "") echo $this->enderecoCSS["codigo"];
        echo "\n".$onLoad."\n".$this->getHeaderScripts();

        echo "</head>\n<body bgcolor=\"".$this->pageBackGroundColor."\" ".$this->onLoad."";
        if ( $this->usoInterno ) echo " class=fundo-sembolha";
        echo ">";

        //Script para cálculo do tempo de carga da página.
        echo "<script language='JavaScript'>".
            "<!--".
            "\n startday = new Date();".
            "\n clockStart = startday.getTime();".
            "\nfunction initStopwatch() ".
            "\n{ ".
            "\n     var myTime = new Date(); ".
            "\n     var timeNow = myTime.getTime();  ".
            "\n     var timeDiff = timeNow - clockStart; ".
            "\n     this.diffSecs = timeDiff/1000; ".
            "\n     return(this.diffSecs); ".
            "\n}".
            "\n//-->".
            "\n</script>";

        $x = $this->timer->start();

        $linkStilo .= "styloSGU.css";

        if ($this->emManutencao == true) {
            echo "<h1><center>PROGRAMA EM MANUTENÇÃO</center></h1>";
        }

        if ($this->menuOn) $mnu = new menu($SISCONF['SESSAO']['USUARIO']['USUARIO']);

        if (isset($HTTP_REFERER)) {
            if (strpos($HTTP_REFERER, '?') === false) {
                $SISCONF['PAGINA']['LINK'] = $HTTP_REFERER;
                $SISCONF['PAGINA']['VARS'] = '';
            } else {
            // $mstr = (string) $HTTP_REFERER;
                $link = explode("?", $HTTP_REFERER); //$mstr);
                $SISCONF['PAGINA']['LINK'] = $link[0];
                $SISCONF['PAGINA']['VARS'] = $link[1];
            }
        }else {
            $SISCONF['PAGINA']['LINK'] = '';
            $SISCONF['PAGINA']['VARS'] = '';
        }
    }

    function _finalize($exibeRodape = true) {
        if ($exibeRodape) {
            echo "<p class='naoVisivelImpressao'><Table border=0 align=center style=\"{background-color:#0f0f0f}\" width=700>";
            echo "<tr>";
            echo "<td align=center style=\"{background-color:#f0f0f0;}\">";
            echo "<table border=0 style=\"{background-color:#f0f0f0;}\" width=100%>";
            echo "<tr>";
            echo "<td align=left style=\"{background-color:#f0f0f0;}\">".
                "<font style=\"{font-family: verdana, tahoma; font-size: 10px; }\" color=black >".
                "SG ".SG_VERSAO." - ". date('d/m/Y H:i'). "</font></td>";
            echo "<td align=right style=\"{background-color:#f0f0f0;}\">".
                "<font style=\"{font-family: verdana, tahoma; font-size: 10px; }\" color=black >".
                "Prg. (". $this->modid ."-". $this->prgid. ")".
                "</font></td>";
            echo "</tr>";
            echo "</table>";
            echo "</td>";
            echo "</tr>";
            echo "</table></p>";
            echo "</body></html>";
        }
        
    }

    function getUsuario() {
        GLOBAL $SISCONF;
        $_usu = array();

        $_usu['ID'] = $SISCONF['SESSAO']['USUARIO']['ID'];
        $_usu['USER'] = $SISCONF['SESSAO']['USUARIO']['USUARIO'];
        return $_usu;
    }

    function isValido() {
        return $this->usu_valido;
    }

    function getPermissoes() {
        GLOBAL $SISCONF;
        GLOBAL $SGU_INTERNET;

        $this->usu_permissao['LEITURA'] = false;
        $this->usu_permissao['GRAVACAO'] = false;
        $this->usu_permissao['ALTERACAO'] = false;
        $this->usu_permissao['EXCLUSAO'] = false;

        $permDB = new confDB();
        $permDB->setBancoByAlias('SISTEMA');
        $permDB->conecta();

        $permQY = new genericQuery($permDB);
        $id = $SISCONF['SESSAO']['USUARIO']['ID'];

        $pqry = "SELECT * ".
            " FROM usuario_x_programa WHERE sistema_id= ".$this->prgid.
            " AND modulo_id= ".$this->modid.
            " AND (usuario_id = ".$id." OR usuario_id IN ".
            " (SELECT grupo_id FROM usuario_x_grupo WHERE usuario_id=".$id."))";
        $permQY->TQuery($pqry);

        while ($row = $permQY->fetchrow()) {
            $this->usu_permissao['LEITURA'] = true;

            if ($row['UXP_EXCLUSAO'] == true) {
                $this->usu_permissao['EXCLUSAO'] = true;
            }
            if ($row['UXP_ALTERACAO'] == true) {
                $this->usu_permissao['ALTERACAO'] = true;
                $this->usu_permissao['GRAVACAO'] = true;
            }
        }
    }

    function getAllPermissoes() {
        GlOBAL $SISCONF;

        $permDB = new confDB();
        $permDB->setBancoByAlias('SISTEMA');
        $permDB->conecta();

        $permQY = new genericQuery($permDB);
        $id = $SISCONF['SESSAO']['USUARIO']['ID'];

        $pqry = "SELECT sistema_id, modulo_id".
            " FROM usuario_x_programa".
            " WHERE usuario_id = ".$id.
            " OR usuario_id IN ".
            "(SELECT grupo_id FROM usuario_x_grupo".
            " WHERE usuario_id = ".$id.")";

        $permQY->TQuery($pqry);

        $perm = array();
        while ($row = $permQY->fetchrow()) {
            $perm[$row["MODULO_ID"]."|".$row["SISTEMA_ID"]] = true;
        }

        return $perm;
    }

    //==========PARTE ADICONADA==================

    function getTitulo() {
        GLOBAL $SISCONF;
        GLOBAL $SGU_INTERNET;

        $tituloDB = new confDB();
        $tituloDB->setBancoByAlias('SISTEMA');
        $tituloDB->conecta();

        $tituloQY = new genericQuery($tituloDB);

        $pqry = "select prog_titulo ".
            " from programa where id_sistema= $this->modid".
            " and id_modulo= $this->prgid";

        $tituloQY->TQuery($pqry);
        $row = $tituloQY->fetchrow();
        $titulo=$row["MOD_TITULO"];
        return $titulo;
    }

    //===========================================

    function showPermissoes() {
        $msg = " Consulta:";
        if ($this->usu_permissao['LEITURA'] == true) {
            $msg .= "<font color=blue>TRUE</font>";
        } else {
            $msg .= "<font color=red>FALSE</font>";
        }

        $msg .= "<br /> Alteração: ";
        if ($this->usu_permissao['ALTERACAO'] == true) {
            $msg .= "<font color=blue>TRUE</font>";
        } else {
            $msg .= "<font color=RED>FALSE</font>";
        }

        $msg .= "<br /> Exclusão: ";
        if ($this->usu_permissao['EXCLUSAO'] == true) {
            $msg .= "<font color=blue>TRUE</font>";
        } else {
            $msg .= "<font color=red>FALSE</font>";
        }

        $msg = "<b>" . $msg . "</b>";
        echo $this->getMensagemErro($msg, WARNING, '<u>Permissões:</u>');
    }

    function getErros() {
        if (trim($this->errors) != "") {
            return $this->getMensagemErro($this->errors, ERROR) . "<br />";
        } else {
            return "";
        }
    }

    // ============PARTE MODIFICADA================
    function permitido($mostrarMensagem='S') {
    // ============================================
        GLOBAL $PHP_SELF;
        GLOBAL $_SERVER;
        global $SISCONF;
        
        $host = $SISCONF['SIS']['COMUM']['DOMAIN']."abertura.php";
        
        $this->getPermissoes();
        $l = new logPrograma();
        $_usu = $this->getUsuario();

        if ($this->usu_permissao['LEITURA'] == false) {
            $msg = "<table border=0 style=\"{background-color:#0f0f0f;}\" width=300 align=center cellpadding=0 bgcolor=#0f0f0f>";
            $msg .= "<tr>";
            $msg .= "<td align=center style=\"{background-color:#f0f0f0;}\">";
            $msg .= "<table border=0 style=\"{background-color:#f0f0f0;}\" width=100% cellspacing=0>";
            $msg .= "<tr>";
            $msg .= "<td colspan=2 align=center style=\"{background-color:#f0f0f0;}\">".
                "<font style=\"{font-family: verdana, tahoma; font-size: 10px; }\" color=black >".
                "<br />Você não possui permissão de acesso<br />para este programa.<br />".
                "Para utilizá-lo, solicite a autorização<BR>de seu superior imediato." .
                "<br /><a href=\"".$host."\">Clique aqui para retornar.</a><br /><br />".
                "</font></td>";
            $msg .= "</tr>";
            $msg .= "<tr style=\"{background-color:#0F0F0F;}\">";
            $msg .= "<td align=left style=\"{background-color:#0f0f0f;}\">".
                "<font style=\"{font-family: verdana, tahoma; font-size: 10px; }\" color=white >".
                "SG ".SG_VERSAO." - ". date('d/m/Y H:i'). "</font></td>";
            $msg .= "<td align=right style=\"{background-color:#0f0f0f;}\">".
                "<font style=\"{font-family: verdana, tahoma; font-size: 10px; }\" color=white>".
                "Prg. (". $this->modid ."-". $this->prgid. ")".
                "</font></td>";
            $msg .= "</tr>";
            $msg .= "</table>";
            $msg .= "</td>";
            $msg .= "</tr>";
            $msg .= "</table><br />";
            // ============PARTE MODIFICADA================
            if ( $mostrarMensagem == 'S' ) {
                echo $msg;
            //$l->log('Sem permissão de acesso ao módulo.', 1, $this->modid, $this->prgid, $_usu['USER'], 'NEGADO');
            }
        // ============================================

        } else {
            GLOBAL $SISCONF;
            //$l->log('Acesso concedido ao módulo', 1, $this->modid, $this->prgid, $_usu['USER']);
            if ( $this->emManutencao == true ) {
                if ( $this->validaIpManutencao() != true ) {
                    $this->usu_permissao['LEITURA'] = false;

                    $msg = "<table border=0 style=\"{background-color=#0f0f0f;}\" width=300 align=center cellpadding=0>";
                    $msg .= "<tr>";
                    $msg .= "<td align=center style=\"{background-color=#f0f0f0;}\" >";
                    $msg .= "<table border=0 style=\"{background-color=#f0f0f0;}\" width=100% cellspacing=0>";
                    $msg .= "<tr>";
                    $msg .= "<td colspan=2 style=\"{background-color=#f0f0f0;}\" >".
                        "<font style=\"{font-family: verdana, tahoma; font-size: 10px; }\" color=black >".
                        "<br /><p align=center>PROGRAMA EM MANUTENÇÃO</p>".
                        "<p align=left>Prezado Usuário:<br />Neste momento este programa se encontra".
                        " em Manutenção. Por favor, tente acessá-lo mais tarde.</p>" .
                        "<p align=center><a href=\"".$SISCONF['SIS']['COMUM']['HOST']."/portal/abertura.php\">Clique aqui para retornar.</A>".
                        "<br /><br /></p></font></td>";
                    $msg .= "</tr>";
                    $msg .= "<tr style=\"{background-color=#0f0f0f;}\">";
                    $msg .= "<td align=left style=\"{background-color=#0f0f0f;}\" >".
                        "<font style=\"{font-family: verdana, tahoma; font-size: 10px; }\" color=white >".
                        "SG ".SG_VERSAO." - ". date('d/m/Y H:i'). "</font></td>";
                    $msg .= "<td align=right style=\"{background-color=#0f0f0f;}\" >".
                        "<font style=\"{font-family: verdana, tahoma; font-size: 10px; }\" color=white >".
                        "Prg. (". $this->modid ."-". $this->prgid. ")".
                        "</font></td>";
                    $msg .= "</tr>";
                    $msg .= "</table>";
                    $msg .= "</td>";
                    $msg .= "</tr>";
                    $msg .= "</table><br />";
                    // ============PARTE MODIFICADA================
                    if ($mostrarMensagem=='S') {
                        echo $msg;
                    }
                // ============================================
                }
            }
        }
        return $this->usu_permissao['LEITURA'];
    }

    function getMensagemErro($mensagem, $tipo = ERROR, $titulo = 'Prezado Usuário:', $log = true) {
        $_mensagem = $this->_MESSAGER->getMessage($mensagem, $tipo, $titulo);

        if ($log) {
            $l = new logPrograma();
            $_usu = $this->getUsuario();

            if ($usu_valido) {
                $l->log($mensagem, 0, $this->modid, $this->prgid, $_usu['USER']);
            } else {
                $l->log($mensagem, 0, $this->modid, $this->prgid, $_usu['USER'], 'NEGADO');
            }
        }

        return $_mensagem;
    }

    function getHeaderScripts() {
        $scpt = "\n<script language=\"javascript\">\n
            <!--\n
                function FundoDestacado(Obj) {\n
                Obj.className = \"cadastro2\";\n
                }\n
                function FundoNormal(Obj){\n
                    Obj.className = \"cadastro\";\n
                }\n
            //-->\n</script>";


        // Script que não faz nada!!
        $scpt = "\n<script language=\"javascript\">\n
            <!--\n
                function FundoDestacado(Obj){\n
                }\n
                function FundoNormal(Obj){\n
                }\n
            //-->\n</script>";

        return $scpt;
    }

    //metodo que retorna os objetos da classe system
    function getSisObjects() {
        $this->sisObject = new system();
        return $this->sisObject;
    }

}


/**
 * # MySQL-Front Dump 2.4
 * # Host: 10.0.1.211   Database: sislog
 *
 * CREATE TABLE `registro_log` (
 *     `rgl_data` datetime NOT NULL default '0000-00-00 00:00:00',
 *     `tpl_id` int(5) unsigned zerofill NOT NULL default '00000',
 *     `rgl_ip` varchar(50) NOT NULL default '',
 *     `rgl_descr` varchar(255) NOT NULL default '',
 *     `rgl_usuario` varchar(50) NOT NULL default '',
 *     `rgl_login` tinyint(1) unsigned default '0',
 *     `rgl_acesso` varchar(50) NOT NULL default '',
 *     KEY `rgl_data` (`rgl_data`,`tpl_id`)
 * ) TYPE=MyISAM;
 *
 * CREATE TABLE `tipo_log` (
 *     `tpl_id` int(5) unsigned zerofill NOT NULL default '00000',
 *     `tpl_descr` varchar(150) NOT NULL default '',
 *     PRIMARY KEY  (`tpl_id`),
 *     UNIQUE KEY `tpl_descr` (`tpl_descr`),
 *     KEY `tpl_id` (`tpl_id`)
 * ) TYPE=MyISAM COMMENT='tipo de log';
 *
 * INSERT INTO tipo_log VALUES("00000", "Não especificado");
 * INSERT INTO tipo_log VALUES("00001", "Acesso Sistema");
 */

class logPrograma {
    var $rgl_data;
    var $datetime;
    var $tpl_id;
    var $rgl_ip;
    var $rgl_descr;
    var $rgl_usuario;
    var $rgl_login;
    var $rgl_acesso;
    var $programa;
    var $navegador;

    function logPrograma() {
    }
}

class menu {
    var $modulo;

    function menu($usuario = '') {

    }
}

class login {
    function showLogin($SGU_INTERNET="", $mens_erro="") {
        GLOBAL $SISCONF;

        // limpa as variáveis de sessão
        GLOBAL $user;                   $user = "";
        GLOBAL $pwd;                    $pwd = "";
        GLOBAL $db;                     $db = "";
        GLOBAL $usernome;               $usernome = "";

        GLOBAL $userid;                 $userid = "";
        GLOBAL $theme;                  $theme = "";
        GLOBAL $resultado_pesquisa;     $resultado_pesquisa = "";
        GLOBAL $operacao;               $operacao = "";        
        GLOBAL $SISCONF;

        $style = "background-attachment: scroll; ";
        $linkStilo = $SISCONF['SIS']['INTRANET']['THEMA']['HOST']."styloSGU.css";

        echo "<html>";
        echo "\n	<head>";
        echo "\n		<link rel=StyleSheet href=\"".$linkStilo."\" type=\"text/css\">";
        echo "\n	</head>";
        echo "\n	<body onload=\"document.logar.l_user.focus()\" class=fundo-sembolha>";
        echo "\n		<table border=0 cellpadding=0 cellspacing=0 align=left width='100%' style='{".$style."}'>";
        echo "\n			<tr>";
        echo "\n				<td style='{".$style."}'>";
        echo "\n					<br /><br /><br /><br /><br /><br /><br />";

        $form = new form( "Login", "conn.php", "300" );
        $form->setNoBorder();
        $form->setFormName('logar');

        if ( $mens_erro != "" ) {
            $form->addErrorMessage("<center><b>".$mens_erro."</b></center>");
            $form->AddField( new NewLine("","") );

            $form->AddField( new Text("&nbsp;") );
            $form->AddField( new Text("&nbsp;") );
            $form->AddField( new NewLine("","") );
        }

        $form->AddField( new Text("Usuário:", true) );
        $form->AddField( new TextField("l_user","l_user","",20,"",20) );
        $form->AddField( new NewLine("","") );

        $form->AddField( new Text("Senha:", true) );
        $form->AddField( new PasswordField("l_user","l_pwd","", 20, 30) );
        $form->AddField( new NewLine("","") );

        $bt = new toolButton();
        $bt->addButton( new FormButton("Entrar","btSubmit","submit") );

        $form->AddField( new ClearField("") );
        $form->AddField( $bt );
        $form->Generate();

        echo "\n				</td>";
        echo "\n			</tr>";
        echo "\n		</table>";
        echo "\n	</body>";
        echo "\n</html>";
    }

    /**
     * verifica a permissão do usuário lgado para inscrição via web
     *
     * @param object $db
     * @return boolean
     */
    function verificaPermissaoUsuarioWeb($db) {
        GLOBAL $SISCONF;

        $permissao = false;

        $objQry = new genericQuery($db);

        $sql = "SELECT usu_web"
            ."\n FROM usuario"
            ."\n WHERE id_usuario = ".$SISCONF["SESSAO"]["USUARIO"]["ID"];

        $objQry->TQuery($sql );
        if($row = $objQry->fetchrow() ) {
            if( $row["USU_WEB"] == "t") {
                $permissao = true;
            }else {
                $permissao = false;
            }
        }

        return $permissao;
    }
}


# Classe para emitir alertas ao usuário
class alerta {
    var $mensagem;      # Armazena a mensagem
    var $tipoAlerta;
    var $conn;          # Armazena a Conexão com o banco
    var $usuario;
    var $grupos;        # Grupos a que o usuário pertence
    var $campo;         # Nome da coluna SQL que conterá o Resultado

    var $sql;           # SQL para pesquisa da mensagem
    # É importante que este retorne uma única linha

    function alerta(&$conexao) {
        GLOBAL $SISCONF;
        $this->conn = $conexao;
        $this->grupos="";
        $this->usuario = $SISCONF['SESSAO']['USUARIO']['ID'];
        $this->setaGrupos();
    }

    function setaGrupos() {
        if (trim($this->usuario)!="") {
            $sql = " select ug.grupo_id,ug.usuario_id "
                ." from usuario u, usuario_x_grupo ug "
                ." where u.id_usuario=".$this->usuario
                ." and u.id_usuario = ug.usuario_id ";

            $query = new genericQuery($this->conn);
            $query->Tquery($sql);

            $this->grupos = "";
            while ($linha=$query->fetchrow()) {
                $this->grupos .= $linha['GRUPO_ID'].',';
            }
            $tamGrupos = (strlen($this->grupos)-1);
            $this->grupos = substr($this->grupos,0,$tamGrupos);
        }
    }

    function exibeAlerta($retorna=false, $fonte="arial", $tamanho="2", $cor="#000000", $style="") {
        $retorno = "";
        $estilo = "STYLE='{background-color: #E4E4CB; border: 1px solid #4C6D9A; }'";

        if (!$retorna) {
            if (trim($this->mensagem)!="") {
                echo "<table width='80%' cellpadding=2 border=0 bgcolor='#FF0000' cellspacing=2>\n";
                echo "  <tr>\n";
                echo "      <td bgcolor='yellow'>\n";
                echo "          <font face='".$fonte."' size=".$tamanho." color='".$cor."' ".$style.">".$this->mensagem."</font>\n";
                echo "      </td>\n";
                echo "  </tr>\n";
                echo "</table>\n";
            }
        } else {
            if (trim($this->mensagem)!="") {
                $retorno .= "<table width='80%' ".$estilo.">\n";
                $retorno .= "   <tr>\n";
                $retorno .= "       <td bgcolor='#EAE9F4' ALIGN=center>\n";
                $retorno .= "           <B><font face='".$fonte."' size=".$tamanho." color='".$cor."' ".$style.">".$this->mensagem."</font></b>\n";
                $retorno .= "       </td>\n";
                $retorno .= "   </tr>\n";
                $retorno .= "</table>\n";
                return $retorno;
            }
        }
    }

}

?>