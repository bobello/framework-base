<?php
require_once "comum/sessao/session.inc.php";
require_once "comum/sessao/configsis.inc.php";
require_once "comum/banco/package_bd.class.php";
require_once "comum/sessao/package_sistema.class.php";
require_once "interface/basico/IntFindCidade.php";

/*##############################################################
# PROGRAMA: Pesquisa de cidades
# LOCAL: modulos/basico
# DATA CRIA��O: 28/05/2009
# DEFINI��O FEITA POR: GELSIMAR MACHADO
# PROGRAMADOR: GELSIMAR MACHADO
# OBJETIVO:
# DATA DE ENTRADA EM OPERA��O:
# ARQUIVO DO PROGRAMA: findCidade.php
#
# CLASSES RELACIONADAS
#  -> IntFindCindade.class
#	 -> NegCadCidade.class
#
# Hist�rico de Manuten��es:
# Data:           Altera��o:
# Data:           Altera��o:
# Data:           Altera��o:
#
##############################################################*/

	$SISCONF['SIS']['DEBUG'] = true;
	$db = new confDB();
	$db->setBancoByAlias('LASALLE');
	$db->conecta();

	$p = new programa(1, 38);

	$f = new IntFindCidade($db, $_REQUEST);
	$f->controller();

	$p->closeProgram(false);
	$db->close();

	$SISCONF['DEBUG']->debug($f);
?>