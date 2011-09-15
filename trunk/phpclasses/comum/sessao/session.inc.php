<?php
if (!isset($_SESSION)) {
	session_start();
}
date_default_timezone_set("America/Sao_Paulo");
/*
session_register('user');
session_register('pwd');
session_register('db');
session_register('usernome');
session_register('page');
session_register('page2');
session_register('userid');
session_register('menssagem_erro');
session_register('theme');
session_register('resultado_pesquisa');
session_register('operacao');
session_register('ano_semestre');
session_register('user_ip');
session_register('selecao_prof_cpf');
session_register('selecao_prof_senha');
session_register('colOpcaoLogin');
session_register('alunoGrad');
session_register('alunoPos');
session_register('bancoDeDados');
session_register('cpf');
session_register('dt_nasc');
*/

$user_ip = getenv ("REMOTE_ADDR");
GLOBAL $SGU_INTERNET;
$user = $_SESSION["user"];
$pwd = $_SESSION["pwd"];

?>