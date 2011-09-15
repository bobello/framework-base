<?php
	$banco = "sistema_gerenciador";
	$usuario = "gelsimar";
	$senha = "76536278";
	$hostname = "localhost";
	$conn = mysql_connect($hostname, $usuario, $senha);
	mysql_select_db($banco) or die ("Não foi possível conectar no MYSQL!");
	
	if(!($conn)){
		echo "Não conectado ao MYSQL!";
	}else{
		echo "Comexão realizada com sucesso!";
	}//if(!($conn)){
	
	mysql_close();
	