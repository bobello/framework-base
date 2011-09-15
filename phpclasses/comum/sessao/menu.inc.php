<?php
require_once "comum/sessao/package_menu.class.php";

$mnu = new itemMenu();

GLOBAL $MENU;
$sMenu = 0;
GLOBAL $MENU_IMG;
GLOBAL $SISCONF;

$m_p_cor = "blue";
$m_s_cor = "red";

// menu do SGC
$MENU_IMG['HOST_IMAGENS'] = "modulos/temas/comum/images/";
$MENU_IMG['IMG_PASTA'] = $MENU_IMG['HOST_IMAGENS']."pasta.gif";
$MENU_IMG['IMG_EXPLODE'] = $MENU_IMG['HOST_IMAGENS']."ico-pasta.gif";
$MENU_IMG['IMG_FINAL'] = $MENU_IMG['HOST_IMAGENS']."ico-open.gif";
$MENU_IMG['IMG_MYMENU'] = $MENU_IMG['HOST_IMAGENS']."SGU-menu-meu.gif";

$pgInicial = "abertura.php";

$pMenu = 1;
$sMenu = 0;

$sMenu = 0;
$MENU[$pMenu] = $mnu->getItemMenu("Informações básicas", $pgInicial."?menu[]=".$pMenu, 0, 0, "", "","Informações básicas");
$MENU[$pMenu]['MENU'][$sMenu] = $mnu->getItemMenu("Cadastro de cidade","modulos/basico/cadCidade.php", 1, 1);
$sMenu++;

$pMenu++;
$sMenu = 0;
$MENU[$pMenu] = $mnu->getItemMenu("Menu exemplo", $pgInicial."?menu[]=".$pMenu, 0, 0, "", "","Exemplo");
$MENU[$pMenu]['MENU'][$sMenu] = $mnu->getItemMenu("Programa exemplo", "modulos/edital/cadEdital.php", 1, 12);
$sMenu++;

// DEFINIÇÃO DO MENU SUSPENSO
GLOBAL $MENU_SUSPENSO;

$MENU_SUSPENSO[0]["TITLE"] = $MENU[1]["NOME_ACESSO_RAPIDO"];
$MENU_SUSPENSO[0]["ESPECIAL"] = true;
$MENU_SUSPENSO[0]["SUB"][] = $MENU[1];

$MENU_SUSPENSO[1]["TITLE"] = $MENU[2]["NOME_ACESSO_RAPIDO"];
$MENU_SUSPENSO[1]["ESPECIAL"] = true;
$MENU_SUSPENSO[1]["SUB"][] = $MENU[2];
