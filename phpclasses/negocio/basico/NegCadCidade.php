<?php
require_once "comum/util/package_utils.class.php";
require_once "comum/util/mlform.class.php";
require_once "negocio/basico/NegCadEstado.php";

/**#####################################################################
# PROGRAMA: cadCidade
# Local: modulos/usuario/cadCidade.php
# Data cria��o: 29/05/2009
# Programador: GELSIMAR MACHADO
# Objetivo: Efetuar cadastro de cidades no sistema.
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
class NegCadCidade
{
	/**
	 * Array com a requisi��o do programa
	 *
	 * @var array
	 */
	
	var $arrRequest = array();
	
    /**
     * Propriedade Padr�o
     * Armazena o Objeto de conex�o com o banco de dados
     * @var object
     */
	
	var $objConn;
	
    /**
     * Propriedade Padr�o
     * Armazena o Objeto de consulta que referencia para $this->objConn
     * @var object
     */
	
	var $objQry;
	
	/**
	 * Propriedade Padr�o
	 * Armazena os erros do programa
	 * @var array
	 */
	
	var $arrErro;
	
	/**
	 * Propriedade da Classe
	 * Armazena os avisos emitido pelo programa
	 * @var array
	 */
	
	var $arrAviso;
	
	/**
	 * Armazena as permiss�es do usu�rio
	 *
	 * @var array
	 */
	var $arrPermissao;

	/**
	 * Controla intera��es solicitadas no programa
	 *
	 * @var string
	 */
	var $strAcao;
	
	/**
	 * campo reservado para algum especial, n�o planejado na especifica��o do programa
	 *
	 * @var string
	 */
	var $campoCuringa;
	
	/**
	 * guarda o valor do bot�o que oi pressionado
	 *
	 * @var string
	 */
	var $strBotao;
	
	/**
	 * nome da cidade
	 *
	 * @var string
	 */
	var $strCidadeNome;
	
	/**
	 * c�digo da cidade selecionada
	 *
	 * @var integer
	 */
	var $intCidadeId;
	
	/**
	 * c�digo do estado relativo a cidade
	 *
	 * @var integer
	 */
	var $intEstadoId;
	
	/**
	 * Construtor da classe
	 *
	 * @param object $db
	 * @param array $requested
	 * @return void
	 * @access public
	 */
	function NegCadCidade(&$db, $requested)
	{
		$this->objConn = $db;
		$this->objQry = new genericQuery($this->objConn);
		$this->arrRequest = $requested;
		$this->limpaPropriedade();
		$this->setaPropriedade();
	}
	
	/**
	 * Limpa as propriedades da classe
	 *
	 * @return void
	 * @access public
	 */
	function limpaPropriedade()
	{
		$this->arrPermissao = array();
		$this->arrErro = array();
		$this->arrAviso = array();
		$this->strAcao = "";
		$this->campoCuringa = "";
		$this->strBotao = "";
		$this->strCidadeNome = "";
		$this->intCidadeId = "";
		$this->strBotao = "";
		$this->intEstadoId = "";
	}
	
	/**
	 * Seta as Propriedades da classe
	 * 
	 * @return void
	 * @access public
	 */
	function setaPropriedade()
	{
		$this->arrRequest = formataRequests( $this->arrRequest );
		
		if(isset($this->arrRequest['campoCuringa'])){$this->campoCuringa = $this->arrRequest['campoCuringa'];}
		if(isset($this->arrRequest['strCidadeNome'])){$this->strCidadeNome = $this->arrRequest['strCidadeNome'];}
		if(isset($this->arrRequest['intCidadeId'])){$this->intCidadeId = $this->arrRequest['intCidadeId'];}
		if(isset($this->arrRequest['intEstadoId'])){$this->intEstadoId = str_replace("x", "", $this->arrRequest['intEstadoId']);}
		//dump($this->arrRequest);
	}
	
	/**
	 * valida as propriedades antes de manipular a tabela de cidade
	 *
	 * @return boolean
	 */
	function validaPropriedades()
	{
		$ret = true;
		$objNumeric = new Numeric();
		$objEstado = new NegCadEstado( $this->objConn, array() );
		
		if( trim( $this->strCidadeNome ) == "" ){
			array_push($this->arrErro, 'Voc� deve informar o Nome da Cidade!' );
			$ret = false;
		}
		
		if( !( $objNumeric->IsInteger( $this->intEstadoId ) ) ){
			array_push($this->arrErro, 'Voc� deve informar o Estado!' );
			$ret = false;
		}else{
			$nome = $objEstado->getNomeEstado( $this->intEstadoId );
			if( trim( $nome ) == "" ){
				array_push($this->arrErro, 'Estado inv�lido!' );
				$ret = false;
			}
		}

		return $ret;
	}
	
	/**
	 * busca todos os estados cadastrados no sistema
	 *
	 * @return array
	 */
	function retornaTodosEstados()
	{		
		$arrayParametros["chave"] = "x";
		$arrayParametros["colunaNome"] = "EST_NOME";		
		
		$objEstado = new NegCadEstado( $this->objConn, array() );
		$array = $objEstado->retornaTodosEstados($arrayParametros);

		return $array;
	}
	
	/**
	 * Carrega os dados referentes a uma cidade
	 *
	 */
	function carregarDadosCidade()
	{
		if( trim( $this->intCidadeId ) != "" ){
			$sql = "SELECT c.cid_nome || '/' || e.est_uf AS cidade_nome, e.id_estado, c.cid_nome"
					."\n FROM cidade c, estado e"
					."\n WHERE c.estado_id = e.id_estado"
					."\n AND c.id_cidade = ".$this->intCidadeId;
	
			$this->objQry->TQuery($sql);
	        if($linha = $this->objQry->fetchrow()){
	        	$this->strCidadeNome = $linha["CIDADE_NOME"];
	        	$this->intEstadoId = $linha["ID_ESTADO"];
	        	$this->strCidadeNome = $linha["CID_NOME"];
	        }
		}
	}
	
	/**
	 * Retorna os dados relativo ao relat�rio de cidades cadastradas
	 *
	 * @return array
	 */
	function retornaDadosRelatorio()
	{
		GLOBAL $SISCONF, $PHP_SELF;
		
		$objCnpj = new cnpj();
		$endImg = $SISCONF['SIS']['COMUM']['IMAGENS'];
		
		$array = array();
		array_push($array, array("ACAO", "COD", "CIDADE"));
		
		$sql = "SELECT c.cid_nome || '/'|| e.est_uf AS cid_nome, c.id_cidade"
				."\n	FROM cidade c, estado e"
				."\n	WHERE c.estado_id = e.id_estado"
				."\n	ORDER BY c.cid_nome";
				
		$this->objQry->TQuery($sql,false);
		while($row = $this->objQry->fetchrow() ) {
			$imgInc = new mlImageField("excluirCidade", $endImg."excluir.gif", "Excluir Cidade", "", "", $PHP_SELF
					."?strAcao=Excluir&intCidadeId=".$row["ID_CIDADE"]);
			$excluir = $imgInc->getCode();
			$imgInc = new mlImageField("alterarCidade", $endImg."update.gif", "Alterar Cidade", "", "", $PHP_SELF
					."?strAcao=Alterar&intCidadeId=".$row["ID_CIDADE"]);
			$atualizar = $imgInc->getCode();

			array_push($array, array($excluir.$atualizar, $row["ID_CIDADE"], $row["CID_NOME"] ) );
		}

		return $array;
	}
	
	/**
	 * fun��o para cadastrar uma nova cidade
	 *
	 * @return boolean
	 */
	function inserirCidade()
	{
		GLOBAL $_SESSION;
		
		if( $this->arrPermissao['GRAVACAO'] ){
			if( $this->validaPropriedades() ){
				
				$cid_nome = $this->strCidadeNome;
				$cid_nome = formataString( $cid_nome );
				
		  		$usu_id_alt = $_SESSION['userid'];
		  		$usu_id_inc = $_SESSION['userid'];
		  		$cid_data_inc = "now()";
		  		$cid_data_alt = "now()";
		  		
				$sql = "INSERT INTO cidade ( id_cidade, cid_nome,estado_id"
						."\n, usu_id_alt, usu_id_inc, cid_data_inc, cid_data_alt )"
						."\n values ( ( SELECT COALESCE( MAX( id_cidade ),0 )+1 FROM cidade )"
						."\n, ".$cid_nome.", ".$this->intEstadoId
						."\n, ".$usu_id_alt.", ".$usu_id_inc.", ".$cid_data_inc.", ".$cid_data_alt." )";
				
				$this->objQry->TQuery($sql);
				if ( $this->objQry->errors != "" ) {
					array_push($this->arrErro, $this->objQry->errors);
					return false;
				}else{
					return true;
				}
						
			}else{
				return false;
			}
		}else{
			array_push($this->arrErro, "Voc� n�o possui permiss�o para esta opera��o!");
			return false;
		}
	}//function
	
	/**
	 * fun��o para atualizar os dados de uma cidade
	 *
	 * @return boolean
	 */
	function atualizarCidade()
	{
		GLOBAL $_SESSION;
		
		if( $this->arrPermissao['ALTERACAO'] ){
			if( $this->validaPropriedades() ){
				if( trim( $this->intCidadeId ) == "" ){
					array_push( $this->arrErro, "Voc� deve selecionar uma Cidade!" );
					return false;
				}
				
				$cid_nome = $this->strCidadeNome;
				$cid_nome = formataString( $cid_nome );
				
		  		$usu_id_alt = $_SESSION['userid'];
		  		$cid_data_alt = "now()";
		  		
				$sql = "UPDATE cidade SET cid_nome = ".$cid_nome
							."\n, estado_id = ".$this->intEstadoId
							."\n, usu_id_alt = ".$usu_id_alt
							."\n, cid_data_alt = ".$cid_data_alt
							."\n WHERE id_cidade = ".$this->intCidadeId;
				
				$this->objQry->TQuery($sql);
				if ( $this->objQry->errors != "" ) {
					array_push($this->arrErro, $this->objQry->errors);
					return false;
				}else{
					return true;
				}
				
			}else{
				return false;
			}
		}else{
			array_push($this->arrErro, "Voc� n�o possui permiss�o para esta opera��o!");
			return false;
		}
	}//function
	
	/**
	 * fun��o para excluir uma determinada cidade
	 *
	 * @return boolean
	 */
	function excluirCidade()
	{
		
		if( $this->arrPermissao['EXCLUSAO'] ){
			
			$objNumeric = new Numeric();
			
			if( trim( $this->intCidadeId ) == "" ){
				array_push($this->arrErro, 'Voc� deve informar a Cidade' );
				return false;
			}elseif(!( $objNumeric->IsInteger( $this->intCidadeId ) ) ) {
				array_push($this->arrErro, 'Cidade inv�lida!' );
				return false;
			}
			
			if( $this->verificaDependeciasCidade() ){
				return false;
			}
			
			$sql = "DELETE FROM cidade"
					."\n WHERE id_cidade = ".$this->intCidadeId;
			
			$this->objQry->TQuery($sql);
			if ( $this->objQry->errors != "" ) {
				array_push($this->arrErro, $this->objQry->errors);
				return false;
			}else{
				return true;
			}
			
			return true;
		}else{
			array_push($this->arrErro, "Voc� n�o possui permiss�o para esta opera��o!");
			return false;
		}
		
	}//function
	
	/**
	 * retornaCidadePorEstado
	 * Busca todas as cidades pela id do estado informada
	 * e retorna verdadeiro caso encontre alguma
	 * @param array $arrParam['intIdEstado'];
	 * @return bool $boolRetorno
	 * @access public
	 */
	
	function retornaCidadePorEstado( $arrParam )
	{
		$boolRetorno = false;
		if ( !( empty( $arrParam ) ) ) {
			if ( isset( $arrParam['intIdEstado'] ) ) {
				$intIdEstado = $arrParam['intIdEstado'];

				$strSql = "SELECT 1 "
					. "\n FROM cidade "
					. "\n WHERE estado_id = " . $intIdEstado;
			
				#dump ( $strSql );
			
				$this->objQry->TQuery( $strSql, false );
		
				if ( $arrLinha = $this->objQry->fetchrow() ) {
					$boolRetorno = true;
				}
			}
		}
		return $boolRetorno;	
	}//function
	
	/**
	 * retorna o nome de uma cidade
	 *
	 * @param integer $idCidade
	 */
	function getNomeCidade( $idCidade )
	{
		$nomeCidade = "";
		
		$objNumeric = new Numeric();	
		if( ( $objNumeric->IsInteger( $idCidade ) ) ){
			$sql = "SELECT c.cid_nome || '/' || e.est_uf AS cid_nome"
					."\n FROM cidade c, estado e"
					."\n WHERE c.estado_id = e.id_estado"
					."\n AND id_cidade = " . $idCidade;
			$this->objQry->TQuery($sql);
	        if($linha = $this->objQry->fetchrow()){
				$nomeCidade = $linha["CID_NOME"];
	        }
		}
		return $nomeCidade;
	}//function
	
	/**
	 * verifica se existe alguma depend�ncia do registro de cidade
	 *
	 * @return booelan
	 */
	function verificaDependeciasCidade()
	{
		$ret = false;
		
		$sql = "SELECT 1"
				."\n FROM inscricao"
				."\n WHERE cidade_id = ".$this->intCidadeId;
				
		$this->objQry->TQuery( $sql, false );

		if ( $arrLinha = $this->objQry->fetchrow() ) {
			array_push( $this->arrErro, "Existe(m) Inscri��o(�es) vinculada(s) a esta Cidade!" );
			$ret = true;
		}
		
		$sql = "SELECT 1"
				."\n FROM instituicao"
				."\n WHERE cidade_id = ".$this->intCidadeId;
				
		$this->objQry->TQuery( $sql, false );

		if ( $arrLinha = $this->objQry->fetchrow() ) {
			array_push( $this->arrErro, "Existe(m) Institui��o(�es) vinculada(s) a esta Cidade!" );
			$ret = true;
		}
		
		$sql = "SELECT 1"
				."\n FROM local_prova"
				."\n WHERE cidade_id = ".$this->intCidadeId;
				
		$this->objQry->TQuery( $sql, false );

		if ( $arrLinha = $this->objQry->fetchrow() ) {
			array_push( $this->arrErro, "Existe(m) Local(is) de Prova vinculada(s) a esta Cidade!" );
			$ret = true;
		}
		
		return $ret;
	}
	
	/**
	 * retorna um sql para o relat�rio de busca de cidades
	 *
	 * @param inteiro $chavePesquisa
	 * @return string
	 */
	function getSQLCidadePorNome($chavePesquisa)
	{
		$sql = "";
		$objNumeric = new Numeric();
		
		if( $chavePesquisa != "" ){
			$chavePesquisa = str_replace("'", "''", $chavePesquisa);
			
			if( $objNumeric->IsNumeric( $chavePesquisa ) ){
				$where = "c.id_cidade = ".$chavePesquisa;
			}else{
				$where = "UPPER(c.cid_nome) LIKE '%".mb_strtoupper( $chavePesquisa )."%'";
			}
			
			$campoLink = "'<a href=\"#\" onClick=\"retornaPesq('''||c.id_cidade||''', '''||REPLACE(c.cid_nome||'/'||E.EST_UF,'''','\\\''')||'''"
							.");\">'||c.cid_nome||'/'||e.est_uf||'</a>' AS cid_nome, c.id_cidade";
            $sql = " SELECT DISTINCT ".$campoLink
	                ."\n FROM cidade c, estado e "
	                ."\n WHERE c.estado_id = e.id_estado"
	                ."\n AND ".$where;
		}

		return $sql;
	}
    
	/**
	 * Seta as permiss�es do usu�rio no programa
	 *
	 * @param string $arrParam['GRAVACAO']
	 * @param string $arrParam['ALTERACAO']
	 * @param string $arrParam['EXCLUSAO']
	 * @return void
	 * @access public
	 */
	function setaPermissao($arrParam = array())
	{
		$this->arrPermissao['GRAVACAO'] = false;
		$this->arrPermissao['ALTERACAO'] = false;
		$this->arrPermissao['EXCLUSAO'] = false;
		
		if(isset($arrParam['GRAVACAO'])){
			$this->arrPermissao['GRAVACAO'] = $arrParam['GRAVACAO'];
		}
		if(isset($arrParam['ALTERACAO'])){
			$this->arrPermissao['ALTERACAO'] = $arrParam['ALTERACAO'];
		}
		if(isset($arrParam['EXCLUSAO'])){
			$this->arrPermissao['EXCLUSAO'] = $arrParam['EXCLUSAO'];
		}
	}
}
?>