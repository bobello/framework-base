<?php
	$banco = "sistema_gerenciador";
	$usuario = "gelsimar";
	$senha = "76536278";
	$hostname = "localhost";
	$conn = mysql_connect($hostname, $usuario, $senha);
	mysql_select_db($banco) or die ("N�o foi poss�vel conectar no MYSQL!");
	
	if(!($conn)){
		echo "N�o conectado ao MYSQL!";
	}else{
		echo "Comex�o realizada com sucesso!";
	}//if(!($conn)){
	
	mysql_close();
	