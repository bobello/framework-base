<?php
//phpinfo();exit;
//set_include_path("/var/www/OZ-AT/sgc_alterado/phpclasses/");

require_once "comum/sessao/session2.inc.php";
require_once "comum/sessao/configsis.inc.php";
GLOBAL $SISCONF;

//echo "<script>window.location= '".$SISCONF['SIS']['INTRANET']['HOST']."portal.php';</script>";

//dump($SISCONF,'aqui');exit;
header("Location: ".$SISCONF['SIS']['INTRANET']['HOST']."portal.php");
?>
