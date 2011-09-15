<?php
require_once "comum/sessao/session.inc.php";
require_once "comum/sessao/configsis.inc.php"; //somente no programa
require_once "comum/banco/package_bd.class.php";
require_once "comum/sessao/package_sistema.class.php";
require_once "interface/basico/IntCadCidade.php";

/**#####################################################################
# PROGRAMA: cadCidade
# Local: modulos/edital/cadCidade.php
# Data cria��o: 29/05/2009
# Programador: GELSIMAR MACHADO
# Objetivo: Efetuar o cadastro de cidades no sistema
# Data de entrada em opera��o: 29/05/2009
# Programa:  cadCidade.php
# Classes Relacionadas
#* -> IntCadCidade.php
#* -> NegCadCidade.php
#
# Hist�rico de Manuten��es:
# Data:           Altera��o:
# Data:           Altera��o:
# Data:           Altera��o:
#
# @see IntCadCidade.php
# @see NegCadCidade.php
#
######################################################################*/
error_reporting(E_ALL ^ E_NOTICE);
GLOBAL $_REQUEST;

$db = new confDB();
$db->setBancoByAlias("LASALLE");
$db->conecta();

$SISCONF['SIS']['DEBUG'] = TRUE;
//N�o alterar C�digo do M�dulo pois � com o c�digo que se v� se o programa � interno ou externo
$p = new programa(1, 7);
$p->getProgram();
if($p->permitido()) {
	$f = new IntCadCidade($db, $_REQUEST);
    $f->setaPermissao($p->usu_permissao);
    $f->controle();
    $SISCONF['DEBUG']->debug($f);
}
$p->closeProgram();
$db->close();
?>