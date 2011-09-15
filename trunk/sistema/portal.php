<?php
require "comum/sessao/session2.inc.php";
require_once "comum/banco/package_bd.class.php";

GLOBAL $SISCONF;
GLOBAL $MENU;
GLOBAL $SGU_INTERNET;

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.01 Transitional//EN\">";
echo "<html>".
"\n<head>".
"\n<meta http-equiv=\"Content-Type\" content=\"text/html; charset=iso-8859-1\">".
"\n<title>SG | Sistema Gerenciador</title>".
"\n<link href=\"jsmenu/general.css\" type=text/css rel=stylesheet>".
"\n<link href=\"jsmenu/theme.css\" type=text/css rel=stylesheet>".
"\n<link href=\"jsmenu/theme_red.css\" type=text/css rel=stylesheet>";
echo "\n<link href=\"".$SISCONF['SIS']['COMUM']['TEMAS']."default/styloSGU.css\" rel=\"stylesheet\" type=\"text/css\">";

echo "<script src=\"jsmenu/JSCookMenu.js\" type=text/javascript></script>".
"\n<script src=\"jsmenu/theme_red.js\" type=text/javascript></script>";

echo "\n<script language=\"javascript\">\n";

$logo = "logo.gif";
//session_destroy();
if ( !empty($SISCONF['SESSAO']['USUARIO']['ID']) ) {
    $DB = new confDB();
    $DB->setBancoByAlias('SISTEMA');
    $DB->conecta();

    require_once "conversaoMenu.php";
}

echo "</script>";

echo "</head>";
echo "<body class='fundo-bolha'>";
$link = $SISCONF['SIS']['COMUM']['IMAGENS_TEMA'];
$target = $SISCONF['SIS']['TARGET']['PROGRAMA'];

echo "<table border='1' cellpadding='0' cellspacing='0' style='height:99%; width: 100%;'>".
	"\n<tr>".
      "\n<td colspan=2 valign='top' style='height: 80px; width: 100%;'>".
            "\n<table width='100%' border='0' cellpadding='0' cellspacing='0' class='tabela-menu'>".
                "\n<tr>".
                    "\n<td width='11%'><img src='".$link."ico-sgl.gif' alt='SG - Sistema Gerenciador'></td>".
                    "\n<td width='62%' align='left' valign='middle'>".
                        "\n<table border='0' cellspacing='0' cellpadding='0'>".
                            "\n<tr>".
                                "\n<td><a href='abertura.php' ".$target."><img src=\"".$link."ico-home.gif\" border=\"0\"></a></td>";
                                if ( !empty($SISCONF['SESSAO']['USUARIO']['ID']) ) {                                
                                	echo "\n<td><a href=\"logoff.php\" ".$target."><img src=\"".$link."ico-encerramento.gif\" alt=\"Encerramento de Sess&atilde;o\" border=\"0\"></a></td>";
                                }
                            echo "\n</tr>".
                        "\n</table>".
                    "\n</td>".
                "\n</tr>".
            "\n</table>";
            $caminhoImagens = $SISCONF['SIS']['COMUM']['IMAGENS_TEMA'];
            // aqui vai o menu Javascript
            if ( !empty($SISCONF['SESSAO']['USUARIO']['ID']) ) {
                echo "<table width='100%' border='0' cellpadding='0' cellspacing='0' class='linha-menu'><tr>".
                    "\n<td width='93%' align='left' valign='middle' STYLE='height: 15px;' class='linha-menu'>".
                        "\n<div id=menuSGU class='linha-menu'></div>".
                		"\n<script type=text/javascript><!--".
                        "\ncmDraw ('menuSGU', menuSGU, null, cmThemeMiniBlack, 'ThemeMiniBlack');".
                        "\n--></script>".
                	"\n</td>".
                "\n</tr></table>";
            }
echo "</td>".
    "\n</tr>";

    $linkPage = "abertura.php";
    if ( empty($SISCONF['SESSAO']['USUARIO']['ID']) ) {
        if ( isset($pagina)) {
            $page = $pagina;
        }
    } else {
        if ( isset($pagina)) {
            $page = $pagina;
        }

        if (!empty($page2)) {
            $linkPage = $page2;
        } elseif (empty($page)) {
            $texto = "";

            $linkPage = "abertura.php";
            if (isset($op_menu)) {
                $opcao = explode("_",$op_menu);
                $menu = $opcao[0];
                $subMenu = $opcao[1];

                $linkPage .= "menu=".$_REQUEST['op_menu']."&op_menu=".$_REQUEST['op_menu'].$texto;
            }
        } else {
            $linkPage = $page;
        }
    }
	echo "\n<tr>".
            "\n<td colspan=2 style='height: 90%; width: 100%'>";
                echo "\n<iframe name=corpo id='corpo_sgl' src=\"".$linkPage."\" style='height: 97%; width:100%;' width='100%' align='top' frameborder='0' marginwidth='0' marginheight='0' scrolling='auto'>".
                "\n</iframe>";
    echo "\n</td></tr>";
echo "\n</table></body></html>";
?>
