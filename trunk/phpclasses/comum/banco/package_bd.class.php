<?php
require_once "comum/sessao/configsis.inc.php";
//classe de generica de conexao com o banco
class genericDB {
	// propriedade publicas
	var $usuario;
	var $senha;
	var $banco;
	var $porta=5432;
	var $host;
	var $base;
	var $aliasDB;

	// propriedades privadas
	var $nomeBD;
	var $connectionid;
	var $conOK;
	var $errors;
	var $rowsAffected;
	var $commitparameter;
	var $charset;

	//metodo construtor
	function genericDB() {
		$this->conOK = false;
		$this->commitparameter = 0;
		$this->errors = '';
		$this->rowsAffected = 0;
	}

	//metodo que seta o banco para a conexao
	function setBanco($nome_BD = 'ORACLE') {
		$nome_BD = strtoupper($nome_BD);
		switch($nome_BD) {
			case 'ORACLE': $this->nomeBD = $nome_BD;
			break;
			case 'MYSQL': $this->nomeBD = $nome_BD;
			break;
			case 'POSTGRES': $this->nomeBD = $nome_BD;
			break;
			case 'MATRIX': $this->nomeBD = $nome_BD;
			break;
			default:
				$this->nomeBD = '';
				$this->errors = "<br />genericDB::setBanco->Banco de dados inválido ($nome_BD)!<br />";
				break;
		} // switch
	}

	//metodo que seta as veriaveis para a conexao generica
	function setConexao($pUsuario, $pSenha, $pBanco = '', $pHost = '', $pPorta = '5433') {
		if ($this->nomeBD == '') {
			$this->errors = "<br />genericDB::setConexao->Banco de dados não informado!<br />";
		} else {
			$this->usuario = $pUsuario;
			$this->senha = $pSenha;
			$this->banco = $pBanco;
			$this->host = '';
			$this->base = '';

			switch($this->nomeBD) {
				case 'MYSQL':
					$this->host = $pHost;
					$this->base = $pBanco;
					break;
				case 'POSTGRES':
					$this->host = $pHost;
					$this->base = $pBanco;
					break;
				case 'MATRIX':
					$this->host = $pHost;
					$this->base = $pBanco;
					break;
			} // switch

			$this->conOK = true;
		}
	}

	//metodo que seta a condicao de autocommit para o banco
	function SetAutocommit($condition) {
		switch($this->nomeBD) {
			case 'MYSQL':
			case 'MATRIX':
			case 'POSTGRES':
				if($condition == true) {
					$this->autocommit = true;
					$this->commitparameter = 1;
				} else {
					$this->autocommit = false;
					$this->commitparameter = 0;
				}
				break;
			case 'ORACLE':
				if($condition == true) {
					$this->autocommit = true;
					$this->commitparameter = OCI_COMMIT_ON_SUCCESS;
				} else {
					$this->autocommit = false;
					$this->commitparameter = OCI_DEFAULT;
				}
				break;
		}
	}

	//método que verifica se o horário permite conexão com o banco
	function permiteConectar(){

	}

	//metodo que faz a conexao com o banco
	function conecta($mostraErro=true) {
		$this->permiteConectar();
		$host = "Host (".getenv("HTTP_HOST").")";
		global $SISCONF;
		$host .= $SISCONF['SIS']['COMUM']['MENSAGEM']['CONEXAO'];
		if ($this->conOK) {
			switch($this->nomeBD){
				case 'ORACLE':
					$this->connectionid = @OCILogon($this->usuario, $this->senha, $this->banco);
					//or die('<b><center>Sem conexão com o Banco de Dados<br />'.$host.'</center></b>');
					$this->errors = OCIError($this->connectionid);
					if (empty($this->errors)) $this->conOK = true;
					else $this->conOK = false;

					if ( (!$this->conOK) and ( $mostraErro==true) ) {
						echo "<b><center>Sem conexão com o Banco de Dados<br />".$host."</center></b>";
						exit;
					}
					break;
				case 'MYSQL':
					$this->connectionid = @mysql_connect($this->host, $this->usuario, $this->senha)
					or die('<b><center>Sem conexão com o Banco de Dados<br />'.$host.'</center></b>');
					mysql_select_db($this->base, $this->connectionid);
					$this->charset = @mysql_client_encoding($this->connectionid);
					$this->conOK = true;
					break;
				case 'POSTGRES':
					$this->connectionid = @pg_connect("host=".$this->host." user=".$this->usuario." password=".$this->senha." port=".$this->porta." dbname=".$this->base."");
					$this->errors = @pg_last_error($this->connectionid);
					if (empty($this->errors)) {
						$this->conOK = true;
					} else {
						$this->conOK = false;
					}

					if ( (!$this->conOK) and ( $mostraErro==true) ) {
						echo "<b><center>Sem conexão com o Banco de Dados<br />".$host."</center></b>";
						exit;
					}

					$stat = @pg_connection_status($this->connectionid);
					if ($stat === 0) {
						if ($this->commitparameter == 0) {
							$result = @pg_query($this->connectionid, 'begin;');
							if ($result) {
								$this->conOK = true;
							} else {
								$this->conOK = false;
							}
						} else {
							$this->conOK = true;
						}
					} else {
						$this->conOK = false;
						$this->errors = "genericDB::conecta->Falha ao conectar c/Postgres!";
					}
					break;
				case 'MATRIX':
					$this->connectionid = 99999;
					break;
			} // switch
    			
			return $this->connectionid;
		} else {
			$this->errors = "<br />genericDB::conecta->Conexao de Banco não preparada!<br />";
			return false;
		}
	}

	//metodo de checkerror
	function checkerrors() {
		switch($this->nomeBD){
			case 'ORACLE':
				if ($this->errors["code"]) {
					$tmp = "[Código " . $this->errors["code"] . "] " . $this->errors["message"];
					$tmp = "<table align=CENTER border=2 width=250><tr><td>$tmp<td></tr></table>";
				}
				break;
			case 'MYSQL':
				if ($this->errors != '') {
					$tmp = $this->errors;
					$tmp = "<table align=CENTER border=2 width=250><tr><td>$tmp<td></tr></table>";
				}
				break;
			case 'MATRIX':
				if ($this->errors != '') {
					$tmp = $this->errors;
					$tmp = "<table align=CENTER border=2 width=250><tr><td>$tmp<td></tr></table>";
				}
				break;
			case 'POSTGRES':
				$tmp=$this->errors;
				break;
		}
		return $tmp;
	}

	//metodo que encerra a conexao com o banco
	function close(){
		switch($this->nomeBD){
			case 'ORACLE':
				@OCILogOff($this->connectionid);
				break;
			case 'MYSQL':
				@mysql_close($this->connectionid);
				break;
			case 'POSTGRES':
				@pg_close($this->connectionid);
				break;
			case 'MATRIX':
				//DO NOTHING
				break;
		}
	}
}

//classe que busca das configuracoes os parametros para a conexao
class confDB extends genericDB {
	function setBancoByAlias($alias){
		global $SISCONF;

		$this->aliasDB = strtoupper($alias);

		$this->nomeBD = strtoupper($SISCONF['DB'][$this->aliasDB]['BANCO']);
		if(isset($SISCONF['DB'][$this->aliasDB]['SID'])){$this->banco = $SISCONF['DB'][$this->aliasDB]['SID'];}
		$this->host = $SISCONF['DB'][$this->aliasDB]['HOST'];
		$this->base = $SISCONF['DB'][$this->aliasDB]['BASE'];

		if (strtoupper($SISCONF['DB'][$this->aliasDB]['USER_CONNECT']) == 'REQUEST'){
			$this->usuario = $SISCONF['SESSAO']['USUARIO']['USUARIO'];
			$this->senha = $SISCONF['SESSAO']['USUARIO']['SENHA'];
		}elseif (strtoupper($SISCONF['DB'][$this->aliasDB]['USER_CONNECT']) == 'DEFAULT'){
			$this->usuario = $SISCONF['DB'][$this->aliasDB]['USUARIO'];
			$this->senha = $SISCONF['DB'][$this->aliasDB]['SENHA'];
		}else{
			$this->usuario = '';
			$this->senha = '';
		}

		$this->SetAutocommit(false);
		$this->conOK = true;
	}

	//metodo que mostra os dados
	function show() {
		global $SISCONF;

		echo "<br />USER.USER - " . $SISCONF['SESSION']['USUARIO']['USUARIO'];
		echo "<br />USER.NAME - " . $SISCONF['SESSION']['USUARIO']['NOME'];
		echo "<br />DB.BANCO - " . $SISCONF['DB'][$this->aliasDB]['BANCO'];
		echo "<br />DB.SID - " . $SISCONF['DB'][$this->aliasDB]['SID'];
		echo "<br />DB.HOST - " . $SISCONF['DB'][$this->aliasDB]['HOST'];
		echo "<br />DB.BASE - " . $SISCONF['DB'][$this->aliasDB]['BASE'];
		echo "<br />DB.USER_CONNECT - " . $SISCONF['DB'][$this->aliasDB]['USER_CONNECT'];
		echo "<br />DB.USER - " . $SISCONF['DB'][$this->aliasDB]['USUARIO'];
		echo "<br />";
		echo "<br />this->aliasDB - " . $this->aliasDB;
		echo "<br />this->nomeBD - " . $this->nomeBD;
		echo "<br />this->banco - " . $this->banco;
		echo "<br />this->host - " . $this->host;
		echo "<br />this->base - " . $this->base;
		echo "<br />this->usuario - " . $this->usuario;
		echo "<br />";
	}
}

//classe de query generica
class genericQuery {
	var $statement;
	var $quey;
	var $connection;
	var $nomeBD;
	var $conOK;
	var $errorCode;
	var $errors;
	var $row;
	var $processoOk;
	var $rowsAffected;
	var $rowsSelected;
	var $lastQuery;
	var $matrixRowId;   //Utilizado exclusivamente para query com MATRIZES
	var $commitparameter;
	var $timer;

	var $tableDef = array();

	//metodo constructor
	function genericQuery(&$DBObject) {
		$this->conOK = false;
		$this->connection = &$DBObject->connectionid;
		$this->commitparameter = $DBObject->commitparameter;
		$this->nomeBD = $DBObject->nomeBD;
		$this->conOK = $DBObject->conOK;

		$this->errors = "";
		$this->processoOk = false;
		$this->matrixRowId = 0;
		$this->timer = new timer();
	}

	//metodo de query
	function query($sql) {

		global $SISCONF;
		if (!is_array($sql)) {
			$this->lastQuery = $sql;
		}

		if ($this->conOK) {
			$this->timer->start();
			switch($this->nomeBD){
				case 'ORACLE':
					$this->statement = @OCIParse ($this->connection, $sql);
					$tmp = OCIError($this->connection);
					$this->errorCode = $tmp['code'];
					$this->errors = $tmp['message'];
					if ($this->errors == "") {
						@OCIExecute ($this->statement, $this->commitparameter);
						$tmp = OCIError($this->statement);
						$this->errorCode = $tmp['code'];
						$this->errors = $tmp['message'];
					}
					$this->rowsAffected = @OCIRowCount($this->statement);

					break;
				case 'MYSQL':
					$teste = @mysql_free_result($this->statement);

					$this->connection->charset = @mysql_client_encoding($this->connection->connectionid);

					//Statement
					$this->statement = @mysql_query($sql, $this->connection);
					$this->errors = mysql_error($this->connection);
					$this->rowsAffected = @mysql_affected_rows($this->connection);
					$this->rowsSelected = @mysql_num_rows($this->statement);
					break;

				case 'POSTGRES':
					$this->statement = pg_query($this->connection,$sql);
					$this->errors = pg_last_error($this->connection);
					$this->rowsAffected = pg_num_rows($this->statement);
					$this->rowsSelected = pg_num_rows($this->statement);
					break;
				case 'MATRIX':
					if (is_array($sql)) {
						$this->statement = $sql;
						$this->errors = "";
						$this->rowsAffected = 0;
						$this->rowsSelected = count($sql) - 1;
						$this->matrixRowId = 0;
					}
					break;

			} // switch

			$this->timer->stop();
			$mem = $sql;

			if ($this->nomeBD != 'MATRIX') {
				$sql = strtoupper($sql);
				$atual = false;
				if (!(strpos($sql, "INSERT") === false)) { $atual = true;}
				if (!(strpos($sql, "UPDATE") === false)) { $atual = true;}
				if (!(strpos($sql, "DELETE") === false)) { $atual = true;}

				if (preg_match('^GRANT^',$sql)) {
					$atual = false;
				}

				$terros = "";
				if (($this->rowsAffected < 1) && ($atual == true)) {
					$this->errors = "Nenhum registro foi afetado!<br />".$this->errors;
					$terros = $this->errors;
					$this->processoOk = false;
				}else{
					$this->processoOk = true;
				}

				$mem = $mem."<br />".
                        "Registros afetados: ".$this->rowsAffected."<br />".
                        "Registros selecionados: ".$this->rowsSelected."<br />".
                        "<b>$terros</b>".
                        "<br /><b>Tempo Decorrido: ".$this->timer->total."</b>";
				memorizeQuery($mem);
			}

			return $this->connection;

		}else{
			$this->errors = "<br />genericQuery::query: Conexao de Banco não preparada!<br />";
			return false;
		}
	}

	//metodo de query para uso com TRANSAÇÃO
	function TQuery($sql, $breakProgram = true, $programa = ' transação no banco de dados.') {
		global $SISCONF;
		global $PHP_SELF;

		if ( isLocked() ) {
			$breakProgram = true;
		} else {
			$breakProgram = false;
		}

		$retorno = true;
		$this->lastQuery = $sql;
		if ($this->conOK) {
			$this->timer->start();
			switch($this->nomeBD){
				case 'ORACLE':
					$this->errors = "";
					if ($breakProgram == true) {
						$this->statement = @OCIParse ($this->connection, $sql) or die($this->erroPadrao($this->statement, $programa, $PHP_SELF));
						@OCIExecute ($this->statement, OCI_DEFAULT) or die($this->erroPadrao($this->statement, $programa, $PHP_SELF));
					}else{
						$this->statement = @OCIParse ($this->connection, $sql);
						$tmp = OCIError($this->connection);
						$this->errorCode = $tmp["code"];
						$this->errors = $tmp["message"];
						if ($this->errors == "") {
							@OCIExecute ($this->statement, OCI_DEFAULT);
							$tmp = OCIError($this->statement);
							$this->errorCode = $tmp["code"];
							$this->errors = $tmp["message"];
							$retorno = false;
						}
					}
					$this->rowsAffected = @OCIRowCount($this->statement);

					break;
				case 'MYSQL':
					//Verificando e alterando o COLLATE
					//$this->connection->charset = @mysql_client_encoding($this->connection->connectionid);

					//Statement

					if ($breakProgram == true) {
						$teste = @mysql_free_result($this->statement);
						$this->statement = @mysql_query($sql, $this->connection) or die ($this->erroPadrao($this->connection, $programa, $PHP_SELF));
					}else{
						$teste = @mysql_free_result($this->statement);
						$this->statement = @mysql_query($sql, $this->connection);

						$this->errors = mysql_error($this->connection);
					}

					$this->rowsAffected = @mysql_affected_rows($this->connection);
					$this->rowsSelected = @mysql_num_rows($this->statement);
					break;

				case 'POSTGRES':
					$this->statement = @pg_query($this->connection, $path);
					
					if ($breakProgram == true) {
						$this->statement = pg_query($this->connection,$sql) or die ($this->erroPadrao($this->connection, $programa, $PHP_SELF));
					} else {
						$this->statement = pg_query($this->connection,$sql);
					}
					$this->rowsAffected = @pg_affected_rows($this->statement);
					$this->rowsSelected = @pg_num_rows($this->statement);
					$this->errors = @pg_result_error($this->statement);
					if ($this->errors=="") {
						$this->errors .= pg_last_error($this->connection);
					}
					break;
				case 'MATRIX':
					if (is_array($sql)) {
						$this->statement = $sql;
						$this->errors = "";
						$this->rowsAffected = 0;
						$this->rowsSelected = count($sql) - 1;
						$this->matrixRowId = 0;
					}
					break;
			} // switch

			$this->timer->stop();
			$mem = $sql;

			if ($this->nomeBD != 'MATRIX') {
				$sql = strtoupper($sql);
				$sql = str_replace("\n",'',$sql);
				$sql = trim($sql);

				$atual = false;
				if (!(strpos($sql, "INSERT") === false)) { $atual = true;}
				if (!(strpos($sql, "UPDATE") === false)) { $atual = true;}
				if (!(strpos($sql, "DELETE") === false)) { $atual = true;}

				//if (ereg('^GRANT',$sql)) {
				if (preg_match('^GRANT^',$sql)) {
					$atual = false;
				}

				$terros = "";
				if (($this->rowsAffected < 1) && ($atual == true)) {
					$this->errors = "Nenhum registro foi afetado!<br />".$this->errors;
					$terros = $this->errors;
					$this->processoOk = false;
				}else{
					$this->processoOk = true;
				}

				$mem = " <b>(Em Transação)</b> ".$mem."<br />".
                        "Registros afetados: ".$this->rowsAffected."<br />".
                        "Registros selecionados: ".$this->rowsSelected."<br />".
                        "<b>$terros</b>".
                        "<br /><b>Tempo Decorrido: ".$this->timer->total."</b>";
				memorizeQuery($mem);
			}

			return $retorno;

		}else{
			$this->errors = "<br />genericQuery::query: Conexao de Banco não preparada!<br />";
			return false;
		}
	}

	function erroPadrao(&$objeto, $operacao, $link) {
		//FGormata a mensagem de erro padrão

		$mensagem = "";

		if ($this->conOK) {
			switch($this->nomeBD){
				case 'ORACLE':
					if ($objeto == '') {
						$erro = ocierror();
					} else {
						$erro = ocierror($objeto);
					}
					break;
				case 'MYSQL':
					echo "</pre>";
					$erro['code'] = mysql_errno($objeto);
					$erro['message'] = mysql_error($objeto);
					$erro['sqltext'] = $this->lastQuery;
					break;
				case 'POSTGRES':
					$erro['code'] = "";
					$erro['message'] = @pg_last_error($objeto);
					$erro['sqltext'] = $this->lastQuery;
					break;
				case 'MYSQL':
					$erro['code'] = "";
					$erro['message'] = "";
					$erro['sqltext'] = "";
					break;
			} // switch
		}


		$codigo = $erro['code'];
		$mensagem = $erro['message']." [offset ]";
		$instrucao = $erro['sqltext'];
		$bgcor = " STYLE =\"{background:#A0A0A0}\" ";
		$bgcor2 = " STYLE =\"{background:#f9f9f9}\" ";

		$this->rollback();

		$out = "<table border=0 $bgcor align=CENTER width=450>";
		$out .= "<tr><td align=CENTER colspan=3 $bgcor><font size=5>Mensagem de Erro</font></td></tr>";
		$out .= "<tr><td align=CENTER colspan=3 $bgcor2><font size=4>Ocorreu um erro durante a execução da $operacao</font></td></tr>";

		$out .= "<tr><td align=CENTER ROWSPAN=4 $bgcor><b><font size=3>
		<P>E<br />R<br />R<br />O</P>
		</font></b></td>";
		$out .= "".
                "<td align=RIGHT Valign=TOP $bgcor2><font size=2><b>Código</b></font></td>".
                "<td align=LEFT Valign=TOP $bgcor2><font size=2>$codigo</font></td>".
                "</tr>";
		$out .= "<tr>".
                "<td align=RIGHT Valign=TOP $bgcor2><font size=2><b>Mensagem</b></font></td>".
                "<td align=LEFT Valign=TOP $bgcor2><font size=2>$mensagem</font></td>".
                "</tr>";
		$out .= "<tr>";
		$out .= "<td align=RIGHT Valign=TOP $bgcor2><font size=2><b>Instrução</b></font></td>".
                    "<td align=LEFT Valign=TOP $bgcor2>";
		if (isDeveloper()) {
			$str_e = "";
			foreach($erro as $k => $v){
				$str_e .= "[$k] = $v <br />";
			}
			$out .= "<font size=2>$str_e<hr />$instrucao</font>";
		}
		$out .= "</td></tr>";
		$out .= "<tr>".
                "<td align=CENTER colspan=2 Valign=TOP $bgcor2><font size=2><b>Aviso: Transação desfeita.</b></font></td>".
                "</tr>";
		$out .= "<tr><td align=CENTER colspan=3 $bgcor2><font size=3><a href='$link'><b>Clique aqui para voltar</b></a></font></td></tr>";

		$out .= "</table>";

		echo $out;

		exit;
	}

	function commit(){
		if ($this->conOK) {
			switch($this->nomeBD){
				case 'ORACLE':
					$committed = OCICommit($this->connection);
					if(!$committed) {
						$error = OCIError($this->connection);
						$this->errors = "genericQuery::commit->Falha ao tentar Commit. [Oracle reports]: " . $error['message'];
						return false;
					}
					return true;
					break;
				case 'MYSQL':
					$this->errors = "genericQuery::commit->MySQL não Implmentado!";
					break;
				case 'POSTGRES':
					if ($this->commitparameter == 0) {
						$result = @pg_query($this->connection, 'commit;');
						if ($result) {
							$result = @pg_query($this->connection, 'begin;');
							return true;
						} else {
							$error = @pg_last_error($this->connection);
							$this->errors = "genericQuery::commit->Falha ao tentar Commit. [Postgres reports]: " . $error;
							return false;
						}
					} else {
						$error = @pg_last_error($this->connection);
						$this->errors = "A transação estava setada para autocommit!";
						return false;
					}
					break;
				case 'MATRIX':
					break;
			} // switch

			$mem = " <b>Comitando a Transação</b>";
			memorizeQuery($mem);
		}else{
			$this->errors = "<br />genericQuery::commit: Conexao de Banco não preparada!<br />";
			return false;
		}
	}

	function rollback(){
		if ($this->conOK) {
			switch($this->nomeBD){
				case 'ORACLE':
					$rolled = ocirollback($this->connection);
					if(!$rolled) {
						$error = OCIError($this->connection);
						$this->errors = "genericQuery::rollback->Falha ao tentar Rollback. [Oracle reports]: " . $error['message'];
						return false;
					}
					return true;
					break;
				case 'MYSQL':
					$this->errors = "genericQuery::rollback->MySQL não Implmentado!";
					break;
				case 'POSTGRES':
					if ($this->commitparameter == 0) {
						$result = @pg_query($this->connection, 'rollback;');
						if ($result) {
							$result = @pg_query($this->connection, 'begin;');
							return true;
						} else {
							$error = @pg_last_error($this->connection);
							$this->errors = "genericQuery::commit->Falha ao tentar Rollback. [Postgres reports]: " . $error;
							return false;
						}
					} else {
						$error = @pg_last_error($this->connection);
						$this->errors = "A transação estava setada para autocommit!";
						return false;
					}
					break;
				case 'MATRIX':
					break;
			} // switch

			$mem = " <b>Rollback da Transação</b>";
			memorizeQuery($mem);
		}else{
			$this->errors = "<br />genericQuery::rollback: Conexao de Banco não preparada!<br />";
			return false;
		}
	}

	function free(){
		if ($this->conOK) {
			switch($this->nomeBD){
				case 'ORACLE':
					@OCIFreeStatement($this->statement);
					$this->statement = null;
					$this->row = null;
					break;
				case 'MYSQL':
					$teste = @mysql_free_result($this->statement);
					$this->statement = null;
					$this->row = null;
					break;
				case 'POSTGRES':
					$teste = @pg_free_result($this->statement);
					$this->statement = null;
					$this->row = null;
					break;
				case 'MATRIX':
					$this->statement = null;
					$this->row = null;
					break;
			} // switch
		}else{
			$this->errors = "<br />genericQuery::free: Conexao de Banco não preparada!<br />";
			return false;
		}
	}

	//metodo de fetchrow
	function fetchrow($mode = 'CASEUPPER') {
		$this->rowsAffected = 0;
		if ($this->conOK){
			$results = array();

			switch($this->nomeBD) {
				case 'ORACLE':
					@OCIFetchInto($this->statement, $results, OCI_ASSOC + OCI_RETURN_NULLS);
					$this->rowsAffected = @OCIRowCount($this->statement);
					$this->errors = OCIError($this->statement);
					$this->row = $results;
					break;
				case 'MYSQL':
					$results = @mysql_fetch_array($this->statement);
					$this->errors = mysql_error($this->connection);
					
					if ((is_array($results)) and ($mode == 'CASEUPPER')) {
						$results = array_change_key_case($results, CASE_UPPER);
					}
					
					$this->row = $results;
					break;
				case 'POSTGRES':
					$results = pg_fetch_assoc($this->statement);
					$this->errors = @pg_result_error($this->statement);

					if ((is_array($results)) and ($mode == 'CASEUPPER')) {
						$results = array_change_key_case($results, CASE_UPPER);
					}
					$this->row = $results;
					break;
				case 'MATRIX':
					$this->matrixRowId++;   //Linha 0 é a do nome dos campos
					//a partir da 1 é campos
					if ($this->matrixRowId >= count($this->statement)) {
						//Chegou ao fim da Matriz
						return false;
					}
					$fieldNames  = $this->statement[0];
					$fieldValues = $this->statement[$this->matrixRowId];
					$results = array();
					for ($stm = 0; $stm < count($fieldNames); $stm++){
						$fn = strtoupper($fieldNames[$stm]);
						$results[$fn] = $fieldValues[$stm];
					}
					$this->row = $results;
					break;
			}

			// var_dump($results);
			return $results;
		}else{
			return false;
		}
	}


	//metodo de fetchall
	//Retorna todas as linhas odo resultado para um array
	//function fetchall($mode = 'CASEUPPER') {
	function fetchall($style='ROW',$mode = 'CASEUPPER') {
		$this->rowsAffected = 0;
		if ($this->conOK){
			$results = array();

			switch($this->nomeBD) {
				case 'ORACLE':
					if ($style=='COLUMN') ocifetchstatement($this->statement, $results);
					else ocifetchstatement($this->statement, $results,0 ,-1, OCI_FETCHSTATEMENT_BY_ROW);
					$this->rowsAffected = Count($results);
					$this->errors = OCIError($this->statement);
					$this->row = $results;
					break;
				case 'MYSQL':
					$results = @mysql_fetch_array($this->statement);
					$this->errors = mysql_error($this->connection);

					if ((is_array($results)) and ($mode == 'CASEUPPER')) {
						$results = array_change_key_case($results, CASE_UPPER);
					}

					$this->row = $results;
					break;
				case 'POSTGRES':
					$results = @pg_fetch_all($this->statement);
					$this->errors = @pg_result_error($this->statement);

					if ((is_array($results)) and ($mode == 'CASEUPPER')) {
						foreach($results as $chave => $dados) {
							$dados = array_change_key_case($dados, CASE_UPPER);
							$results[$chave] = $dados;
						}
					}

					$this->row = $results;
					break;

				case 'MATRIX':
					$this->matrixRowId++;   //Linha 0 é a do nome dos campos
					//a partir da 1 é campos
					if ($this->matrixRowId >= count($this->statement)) {
						//Chegou ao fim da Matriz
						return false;
					}
					$fieldNames  = $this->statement[0];
					$fieldValues = $this->statement[$this->matrixRowId];
					$results = array();
					for ($stm = 0; $stm < count($fieldNames); $stm++){
						$fn = strtoupper($fieldNames[$stm]);
						$results[$fn] = $fieldValues[$stm];
					}
					$this->row = $results;
					break;
			}

			// var_dump($results);
			return $results;
		}else{
			return false;
		}
	}


	//metodo de countrows
	function countrows(){
		switch($this->nomeBD){
			case 'ORACLE':
				$rows = OCIRowCount($this->statement);
				break;
			case 'MYSQL':
				$rows = mysql_num_rows($this->statement);
				break;
			case 'POSTGRES':
				$rows = @pg_num_rows($this->statement);
				break;
			case 'MATRIX':
				$rows = count($this->statement) - 1;
				break;
		}
		return $rows;
	}

	//metodo de checkerros
	function checkerrors() {
		$tmp = false;
		switch($this->nomeBD){
			case 'ORACLE':
				if(is_array($this->errors)){
					if($this->errors["code"]){
						$tmp = "[Código " . $this->errors["code"] . "] " . $this->errors["message"];
					}
				}else{
					if ($this->errorCode != "") {
						$tmp = "[Código " . $this->errorCode . "] " . $this->errors;
					}
				}
				if ($tmp != false){
					$tmp = "<table align=CENTER border=2 width=250><tr><td>$tmp</td></tr></table>";
				}
				break;
			case 'MYSQL':
				if ($this->errors != ''){
					$tmp = $this->errors;
					$tmp = "<table align=CENTER border=2 width=250><tr><td>$tmp</td></tr></table>";
				}
				break;
			case 'POSTGRES':
				if ( $this->errors != '' ) {
					$tmp = $this->errors;
					$tmp = "<table align=CENTER border=2 width=250><tr><td>$tmp</td></tr></table>";
				}
				break;
			case 'MATRIX':
				if ($this->errors != ''){
					$tmp = $this->errors;
					$tmp = "<table align=CENTER border=2 width=250><tr><td>$tmp</td></tr></table>";
				}
				break;
		}

		return $tmp;
	}

	function getTableDefinition($TableName){
		if (trim($TableName)=="") {
			$this->errors = "<br />genericQuery::getTableDefinition - Nome da tabela não informado!";
			return;
		}
		$this->tableDef["$TableName"]['NOME']=$TableName;
		switch($this->nomeBD){
			case 'ORACLE':
				$col = "SELECT * FROM $TableName WHERE ROWNUM=1";
				$pk = "SELECT POSITION, COLUMN_NAME, TABLE_NAME ".
                        " FROM all_cons_columns ".
                        " WHERE CONSTRAINT_NAME in ( ".
                        " select CONSTRAINT_NAME from all_constraints ".
                        " where TABLE_NAME=UPPER('$TableName') ".
                        " and CONSTRAINT_TYPE='P') ".
                        " ORDER BY POSITION ";

				$this->TQuery($col);
				$ncols = OCINumCols($this->statement);
				$this->tableDef["$TableName"]['COLS'] = array();
				for ( $i = 1; $i <= $ncols; $i++ ) {
					$tmp = array();
					$tmp['COLNAME'] = OCIColumnName($this->statement,$i);
					$tmp['COLTYPE'] = OCIColumnType($this->statement,$i);
					$tmp['COLSIZE'] = OCIColumnSize($this->statement,$i);
					$tmp['COLKEY'] = false;
					$tmp['COLKEYPOS'] = 0;
					array_push($this->tableDef["$TableName"]['COLS'], $tmp);
				}
				$this->TQuery($pk);
				while($pkrow = $this->fetchrow()){
					$pcol = $pkrow['COLUMN_NAME'];
					$ppos = $pkrow['POSITION'];
					$as=count($this->tableDef["$TableName"]['COLS']);
					for ($i=0; $i<$as; $i++){
						if (trim($this->tableDef["$TableName"]['COLS'][$i]['COLNAME'])==trim($pcol)) {
							$this->tableDef["$TableName"]['COLS'][$i]['COLKEY'] = true;
							$this->tableDef["$TableName"]['COLS'][$i]['COLKEYPOS'] = $ppos;
						}
					}
				} // while
				break;
			case 'MYSQL':
				$this->errors = "<br />genericQuery::getTableDefinition->Postgres não Implmentado!";
				break;
			case 'POSTGRES':

				$col = "SELECT * FROM $TableName LIMIT 1 OFFSET 0";
				$this->TQuery($col);
				$ncols = @pg_num_fields($this->statement);
				$this->tableDef["$TableName"]['COLS'] = array();
				for ( $i = 0; $i < $ncols; $i++ ) {
					$tmp = array();
					$tmp['COLNAME'] = @pg_field_name($this->statement,$i);
					$tmp['COLTYPE'] = @pg_field_type($this->statement,$i);
					$tmp['COLSIZE'] = @pg_field_size($this->statement,$i);
					$tmp['COLKEY'] = false;
					$tmp['COLKEYPOS'] = 0;
					$tmp['ISNULL'] = @pg_field_is_null($this->statement,$i);
					array_push($this->tableDef["$TableName"]['COLS'], $tmp);
				}

				break;
			case 'MATRIX':
				break;
		}
	}

}


// classe que retorna o tempo de execucao de um script
class timer{

	var $start;
	var $stop;
	var $total;


	// metodo que retorna o time do start
	function timer(){
	}

	// metodo que retorna o time do start
	function start(){
		$micro = explode(" ", microtime()); // busca a data
		$this->start = $micro[1] + $micro[0]; // calcula o valor
		return $this->start;
	}

	// metodo que retorna o time do stop
	function stop(){
		$micro = explode(" ", microtime()); // busca a data
		$this->stop = $micro[1] + $micro[0]; // calcula o valor
		$this->total = $this->stop - $this->start;
		return $this->total;
	}
}

/**
 * Formata uma string para sql
 *
 * @param string $str
 * @param bool $arrayParam['maiuscula']
 * @param bool $arrayParam['aspasSimples']
 * @param bool $arrayParam['naoAlterarCaracter']
 * @return string
 * @access public
 */
function formataString($str, $arrayParam = array())
{
	if( trim( $str ) == "" ){ //SE NÃO TIVER ESPAÇO EM BRANCO ENTÃO NÃO RETORNA NADA.
		return "null";
	}

	$strNaoAlterarCaracter = (isset($arrayParam['naoAlterarCaracter']) ? $arrayParam['naoAlterarCaracter'] : false);
	$str = trim($str);
	
	$str = str_replace("\'","'",$str);
	$str = str_replace("'","''",$str);

	if(isset($arrayParam['aspasSimples']) && $arrayParam['aspasSimples'] == false){
		$str = "\"".$str."\"";
	} else {
		$str = "'".$str."'";
	}


	if($strNaoAlterarCaracter == false ){	
		if(isset($arrayParam['maiuscula'])){
			if( $arrayParam['maiuscula'] == false){
				$str = mb_strtolower($str);
			} else {
				$str = mb_strtoupper($str);
			}		
		}		
	}
	
	return $str;
}

/**
 * Formata um float para sql
 *
 * @param float $flt
 * @param int $arrayParam["casasDecimais"]
 * @return float
 * @access public
 */
function formataFloat($flt, $arrayParam = array())
{
	if( trim( $flt ) == "" ){
		return "null";
	}

	$intCasasDec = 2;
	if(isset($arrayParam["casasDecimais"])){
		$intCasasDec = $arrayParam["casasDecimais"];
	}

	$pos = strpos($flt,",");
	if($pos !== false){
		$flt = str_replace(".", "", $flt);
	}

	$flt = str_replace(",", ".", $flt);
	$flt = (float)$flt;
	$flt = number_format($flt, $intCasasDec, '.', '');
	return $flt;
}

/**
 * formata um campo para o tipo data para um sql
 *
 * @param date $dat
 * @param string $arrayParam['formataHora']
 * @return date
 */
function formataDate($dat, $arrayParam = array())
{
	if( trim( $dat ) == "" ){
		return "null";
	}

	$formataHora = "";
	if(isset($arrayParam["formataHora"])){
		$formataHora = " HH24:MI:SS";
	}

	$dat = "to_timestamp( '".$dat."', 'DD/MM/YYYY".$formataHora."' )";

	return $dat;
}

/**
 * Seta as permissões em um array passado por parametro
 *
 * @param boolean $arrPermissao["GRAVACAO"] permissões da classe para GRAVAÇÃO de registro
 * @param boolean $arrPermissao["ALTERACAO"] permissões da classe para ALTERAÇÃO de registro
 * @param boolean $arrPermissao["EXCLUSAO"] permissões da classe para EXCLUSÃO de registro
 *
 * @param boolean $arrParam["GRAVACAO"] permissões para GRAVAÇÃO do usuário
 * @param boolean $arrParam["ALTERACAO"] permissões para ALTERAÇÃO do usuário
 * @param boolean $arrParam["EXCLUSAO"] permissões para EXCLUSÃO do usuário
 *
 * @example setaPermissao($f->arrPermissao, $p->usu_permissao);
 * @author TIAGO MARTINS MARTINS
 * @return void
 * @access public
 */
function setaPermissao(&$arrPermissao, $arrParam = array())
{
	$arrPermissao["GRAVACAO"] = false;
	$arrPermissao["ALTERACAO"] = false;
	$arrPermissao["EXCLUSAO"] = false;

	if(isset($arrParam["GRAVACAO"])){
		$arrPermissao["GRAVACAO"] = $arrParam["GRAVACAO"];
	}

	if(isset($arrParam["ALTERACAO"])){
		$arrPermissao["ALTERACAO"] = $arrParam["ALTERACAO"];
	}

	if(isset($arrParam["EXCLUSAO"])){
		$arrPermissao["EXCLUSAO"] = $arrParam["EXCLUSAO"];
	}
}

/**
 * Efetua um commit se $boolConfirmaCommit == true ou
 * rollback para $boolConfirmaCommit == false
 *
 * @param object $objQry objeto de consulta no banco de dados
 * @param boolean $boolConfirmaCommit confirmação de commit default false
 *
 * @example concluiTransacao($this->objQry, $boolConfirmaCommit);
 * @author TIAGO MARTINS MARTINS
 * @return void
 * @access public
 */
function concluiTransacao($objQry, $boolConfirmaCommit = false)
{
	if($boolConfirmaCommit){
		$objQry->commit();
	} else {
		$objQry->rollback();
	}
}//concluiTransacao
?>