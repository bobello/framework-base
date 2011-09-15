<?php
require "comum/sessao/session2.inc.php";
require "comum/sessao/configsis.inc.php"; //somente no programa
require "comum/sessao/package_sistema.class.php";
require_once "portal.class.php";

GLOBAL $SISCONF;

$portal = new Portal_SGU($_REQUEST);

echo "<!DOCTYPE HTML PUBLIC \"-//W3C//DTD HTML 4.0 Transitional//EN\">
	<html><head>
	<meta http-equiv=Content-Type content=\"text/html; charset=windows-1252\">
		<meta content=\"MSHTML 5.50.4923.2500\" name=generator>
	<link rel=StyleSheet href=\"".$SISCONF['SIS']['COMUM']['TEMAS']."comum/styloSGU.css\" type=\"text/css\">
	</head>
	<body ".$portal->style_body()." vlink=#7b7bc0 alink=#ff0000 link=#0000ff background=\"\">";

$portal->menu_top();
$portal->menuRapido();
?>