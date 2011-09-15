<?php

require "comum/sessao/session2.inc.php";
require_once "portal.class.php";
require_once "comum/sessao/configsis.inc.php"; //somente no programa
require "comum/sessao/package_sistema.class.php";
require_once "comum/banco/package_bd.class.php";
require_once "comum/sessao/menu.inc.php";

GLOBAL $MENU;
GLOBAL $SISCONF;
GLOBAL $SGC_INTERNET;

$menu = (isset($_REQUEST['menu'])?$_REQUEST['menu']:null);

if (empty($SISCONF['SESSAO']['USUARIO']['ID'])) {
    $l = new login();
    $l->showLogin("", (isset($_SESSION["mensagem_erro"])?$_SESSION["mensagem_erro"]:"") );
} else {
    $portal = new Portal_SGU($_REQUEST);
    $portal->setUsuario($SISCONF['SESSAO']['USUARIO']['USUARIO'], $SISCONF['SESSAO']['USUARIO']['SENHA']);
    $hash = "";

// ===========PARTE MODIFICADA=============
    echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">".
        "\n<html> ".
        "\n	<head>".
		"\n 	<link href=\"jsmenu/general.css\" type=text/css rel=stylesheet>".
		"\n		<link href=\"jsmenu/theme.css\" type=text/css rel=stylesheet>".
		"\n		<link href=\"jsmenu/theme_red.css\" type=text/css rel=stylesheet>".
        "\n		<link href=\"".$SISCONF['SIS']['COMUM']['TEMAS']."default/styloSGU.css\" rel=\"stylesheet\" type=\"text/css\">".
        "\n		<meta http-equiv=Content-Type content=\"text/html; charset=windows-1252\">".
        "\n		<meta content=\"MSHTML 5.50.4923.2500\" name=generator>".
        "\n		<script language type='javascript'>".
        "\n			function procura() ".
        "\n			{".
        "\n				window.location.hash = '".$hash."';".
        "\n			}".
        "\n			function janeladeinformacao(pro,mod)".
        "\n			{".
        "\n				param='programa='+pro+'&modulo='+mod;".
        "\n				window.open('infoprograma.php?'+param, 'novatela', 'scrollbars=yes,menubar=no,resizable=yes,width=400,height=350,top=100,left=200');".
        "\n			}".
        "\n		</script>".
        "\n	</head>";
// ========================================
    echo "\n	<body class=fundo-sembolha vlink=#7b7bc0 alink=#ff0000 link=#0000ff";
    if ($hash != "") {
        echo " onload=\"procura()\"";
    }
    echo ">";
    echo "\n		<style type='text/css'>"
         ."\n		    .tituloSecao"
         ."\n           {"
         ."\n	       		font-size: 11pt;"
         ."\n	       		font-family: Verdana;"
         ."\n	       		font-weight: 700;"
         ."\n	       		background-color: #D2D9E3;"
         ."\n 	       		text-decoration:none;"
         ."\n 		    }"
         ."\n   		.menu"
         ."\n			{"
         ."\n       		text-decoration: none;"
         ."\n		   	}"
         ."\n		</style>";

    GLOBAL $SISCONF;

    $db = new confDB();
    $db->setBancoByAlias('SISTEMA');
    $db->conecta();

    $SRC_IMAGEM = "/portal/modulos/temas/comum/images/";

    $objAlerta = new alerta($db);
    $msgAlerta = "";
    $msgAlerta = $objAlerta->exibeAlerta(true);

    echo "\n		<br />";
    echo "\n		<table cellspacing=0 cellpadding=0 border=0 width=700 align=center>".
        "\n		<tr>".
		"\n			<td align=center>".
        "\n        		".$msgAlerta.
        "\n				<br />".
        "\n			</td>".
        "\n		</tr>".
        "\n		<tr>".
        "\n			<td>".
        "\n				<table style='width: 690px; height: 239px' cellspacing=0 cellpadding=0 align=center border=0>".
		"\n					<tbody>".
		"\n						<tr>".
		"\n							<td>".
		"\n								<table width=\"90%\"cellspacing=8 cellpadding=0 align=center border=0>".
		"\n									<tbody>";
	$imgpasta = $MENU_IMG['IMG_PASTA'];
    if (empty($menu)) {
    	$tam = count($MENU);
    	for($i = 1; $i<=$tam; $i++){
	    	echo "\n										<tr>".
				"\n											<td valign=middle align=left width=50%><a href=\"".$MENU[$i]["LNK"]."\" class='titulosecao'>".
				"\n												<font class='titulosecao'><img src='".$imgpasta."' border='0' align='left'>".mb_strtoupper($MENU[$i]["NOME"])."</font></a>".
	    		"\n											</td>";
	    	$i++;
	    	if(isset($MENU[$i]["LNK"]) && isset($MENU[$i]["NOME"])){
				echo "\n											<td valign=middle align=left width=50%><a href=\"".$MENU[$i]["LNK"]."\" class='titulosecao'>".
					"\n												<font class='titulosecao'><img src='".$imgpasta."' border='0' align='left'>".mb_strtoupper($MENU[$i]["NOME"])."</font></a>".
		    		"\n											</td>".
					"\n										</tr>";
	    	}
    	}
	} else {
        $portal->menu();
	}
	echo "\n									</tbody>".
		"\n								</table>".
		"\n							</td>".
		"\n						</tbody>".
		"\n					</table>".
		"\n				</td>".
		"\n			</tr>".
		"\n		</table>".
		"\n	</body>".
    	"\n</html>";
}
?>
