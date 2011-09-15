<?php
require_once "comum/tela/package_form.class.php";
require_once "comum/banco/package_bd.class.php";

/*
 * Classe que permite a montagem de um lista de opcoes a partir de um SQL
 * em um banco de dados.
 */
class DBComboBox extends ComboBox {
    var $descriptor = "function DBComboBox(&\$DB, \$name, \$value)";
    var $conn;              // Conexao com o banco de dados.
    var $keyColumn;         // Nome da coluna que sera a chave.
    var $valueColumn;       // Nome da coluna que sera o valoe.
    var $sql;               // Sql para buscar os itens.
    var $value;             // Item selecionado.

    var $useCustomItem;     // Identifica se sera inserido um item personalizado
                            // como primeiro item da combo.
    var $customItemKey;     //
    var $customItemValue;   //

    // Constutor.
    function DBComboBox(&$DB, $name, $value) {
        $this->conn = &$DB;

        $this->name = $name;
        $this->setValue( $value );
        $this->setSQL( "" );
        $this->setKeyColumn( "" );

        $this->setUseCustomItem( false );
        $this->setCustomItemKey( -1 );
        $this->setCustomItemValue( "" );
        $this->setAgruparColumn(false);
        $this->setMultiploValue(false);
        $this->setSize("");
    }

    // Propriedade SQL.
    function setSQL( $sql ) { $this->sql = $sql; }
    function getSQL() { return $this->sql; }

    // Propriedade KeyColumn.
    function setKeyColumn( $column ) { $this->keyColumn = $column; }
    function getKeyColumn() { return $this->keyColumn; }
    
    //propriedade agrupar
	function setAgruparColumn( $agrupar ) { $this->agrupar = $agrupar; }
    function getAgruparColumn() { return $this->agrupar; }

    // Propriedade ValueColumn.
    function setValueColumn( $column ) { $this->valueColumn = $column; }
    function getValueColumn() { return $this->valueColumn; }

    // Propriedade UseCustomItem.
    function setUseCustomItem( $value ) { $this->useCustomItem = $value; }
    function getUseCustomItem() { return $this->useCustomItem; }

    // Propriedade CustomItemKey.
    function setCustomItemKey( $value ) { $this->customItemKey = $value; }
    function getCustomItemKey() { return $this->customItemKey; }

    // Propriedade CustomItemValue.
    function setCustomItemValue( $value ) { $this->customItemValue = $value; }
    function getCustomItemValue() { return $this->customItemValue; }
    
	// Propriedade multiplo.
    function setMultiploValue( $value ) { $this->multiplo = $value; }
    function getMultiploItemValue() { return $this->multiplo; }

    // Propriedade value - Item selecionado.
    function setValue( $value ) {
    	if(is_array($value)){
    		$this->value = $value;
    		foreach($this->value as $key => $content){
    			$this->value[$key] = "X" . $content;
    		}
    	} else {
    		$this->value = "X" . $value;
    	}
    }
    function getValue() { return substr($this->value, 1); }
    
    // Propriedade js - Item selecionado.
    function setJs( $js ) { $this->js = $js; }
    function getJs() { return $this->js; }
    
    // Propriedade disable - Item selecionado.
    function setDisable( $disable ) { $this->disable = $disable; }
    function getDisable() { return $this->disable; }
    
  // Propriedade disable - Item selecionado.
    function setSize( $value ) { $this->size = $value; }
    function getSize() { return $this->size; }

    // Retorna um item especifico da combo.
    function getItem( $key ) { return $this->options["X" . $key]; }

    // Monta a lista de items.
    function build() {
        $query =  new genericQuery( $this->conn );

        $sql = $this->getSQL();
        $query->TQuery( $sql );

        $retval = array();

        // Adiciona um item personalizado na primeira posicao da lista.
        if ( trim($this->useCustomItem) != "" ){
            $key = "X" . $this->customItemKey;
            $value = $this->customItemValue;
            $retval[$key] = $value;
        }
        
        if( $this->getAgruparColumn() ){
	        while( $row = $query->fetchrow() ) {
	            $key          = "X" . $row[mb_strtoupper($this->keyColumn)];
	            $nivel 		  = $row["NIVEL"];
	            $value        = $row[mb_strtoupper($this->valueColumn)];
	            $retval[$nivel][$key] = $value;
	        }
        }else{
        	while( $row = $query->fetchrow() ) {
	            $key          = "X" . $row[mb_strtoupper($this->keyColumn)];
	            $value        = $row[mb_strtoupper($this->valueColumn)];
	            $retval[$key] = $value;
	        }
        }
        $this->options = $retval;
    }
}

/**
 * Monta uma combo com os grupos de usuários para selecionar
 *
 */
class ComboGrupoUsuario extends DBComboBox {
	/**
	 * Obtém a assinatura do construtor
	 *
	 * @var string
	 */
    var $descriptor = "function ComboGrupoUsuario(&\$DB, \$name, \$value, \$selecione=\"\", \$js=\"\", \$disable=false) ";
	
    /**
     * Construtor da classe
     *
     * @param object $DB
     * @param string $name
     * @param array $value
     * @param string $selecione
     * @return void
     * @access public
     */
    function ComboGrupoUsuario($DB, $name, $value, $selecione="", $js="", $disable=false) 
    {
        $this->name = $name;
        $this->conn = $DB;
        $this->setValue( $value );

        $sql = "SELECT id_usuario, TRANSLATE(UPPER(usu_nome),'ÄÃÀÁÂÕÓÖÒÔÉÊËÍÌÏÜÚÙÇ','AAAAAOOOOOEEEIIIUUUC') AS nome" 
				        ."\n FROM usuario "
				        ."\n WHERE usu_tipo = 'G' "
				        ."\n ORDER BY nome";
				       
        $this->setSQL( $sql );
				        
        $this->setKeyColumn( "id_usuario" );
        $this->setValueColumn( "nome" );
        $this->setUseCustomItem( true );
        $this->setCustomItemKey( "" );
        if (trim($selecione)==""){
            $this->setCustomItemValue( "Selecione" );
        }else{
            $this->setCustomItemValue( $selecione );
        }
        $this->setJs($js);
        $this->setdisable($disable);
        $this->build();
    }
}

/**
 * Monta uma combo com os usuários para selecionar
 *
 */
class ComboUsuario extends DBComboBox {
	/**
	 * Obtém a assinatura do construtor
	 *
	 * @var string
	 */
    var $descriptor = "function ComboUsuario(&\$DB, \$name, \$value, \$selecione=\"\", \$js=\"\", \$disable=false) ";
	
    /**
     * Construtor da classe
     *
     * @param object $DB
     * @param string $name
     * @param array|string $value
     * @param string $selecione
     * @return void
     * @access public
     */
    function ComboUsuario($DB, $name, $value, $selecione="", $js="", $disable=false) 
    {
        $this->name = $name;
        $this->conn = $DB;
        $this->setValue( $value );

        $this->setSQL( "SELECT id_usuario, (usu_nome) AS nome" 
				        ."\n FROM usuario "
				        ."\n WHERE usu_tipo = 'I' "
				        ."\n ORDER BY nome" );

        $this->setKeyColumn( "id_usuario" );
        $this->setValueColumn( "nome" );
        $this->setUseCustomItem( true );
        $this->setCustomItemKey( "" );
        if (trim($selecione)==""){
            $this->setCustomItemValue( "Selecione" );
        }else{
            $this->setCustomItemValue( $selecione );
        }
        $this->setJs($js);
        $this->setdisable($disable);
        $this->build();
    }
}

/**
 * Monta uma combo com os níveis de vagas para selecionar
 *
 */
class ComboNivel extends DBComboBox {
	/**
	 * Obtém a assinatura do construtor
	 *
	 * @var string
	 */
    var $descriptor = "function ComboNivel(&\$DB, \$name, \$value, \$selecione=\"\", \$js=\"\", \$disable=false) ";
	
    /**
     * Construtor da classe
     *
     * @param object $DB
     * @param string $name
     * @param array $value
     * @param string $selecione
     * @return void
     * @access public
     */
    function ComboNivel($DB, $name, $value, $selecione="", $js="", $disable=false) 
    {
        $this->name = $name;
        $this->conn = $DB;
        $this->setValue( $value );

	  /*	
        $this->setSQL( "SELECT id_nivel, TRANSLATE(UPPER(nvl_descr),'ÄÃÀÁÂÕÓÖÒÔÉÊËÍÌÏÜÚÙÇ','AAAAAOOOOOEEEIIIUUUC') AS nome" 
				        ."\n FROM nivel "
				        ."\n ORDER BY nome" );
        $this->setKeyColumn( "id_nivel" );
	*/

	$this->setSQL( "SELECT id_nivel, UPPER(nvl_descr) AS nome" 
				        ."\n FROM nivel "
				        ."\n ORDER BY nome" );
        $this->setKeyColumn( "id_nivel" );




        $this->setValueColumn( "nome" );
        $this->setUseCustomItem( true );
        $this->setCustomItemKey( "" );
        if (trim($selecione)==""){
            $this->setCustomItemValue( "Selecione" );
        }else{
            $this->setCustomItemValue( $selecione );
        }
        $this->setJs($js);
        $this->setdisable($disable);
        $this->build();
    }
}

class ComboDdd extends DBComboBox {
	function __construct($arrayParametros) 
    {
    	$this->name = $arrayParametros['name'];
    	$this->setValue( $arrayParametros['value'] );
    	
    	$arrValores = array("" => "", "51" => "51");
    	$this->options = $arrValores;
    }//function __construct() 
}//class ComboDdd extends DBComboBox {
/**
 * Monta uma combo com os cargos cadastrados
 *
 */
class ComboCargo extends DBComboBox {
	
    /**
     * Construtor da classe
     *
     * @param object $arrayParametros['DB']
     * @param string $arrayParametros['name']
     * @param string $arrayParametros['value']
     * @param boolean $arrayParametros['disable']
     * @param string $arrayParametros['js']
     * @param integer $arrayParametros['editalId']
     * @param boolean $arrayParametros['multiplo']
     * @return void
     * @access public
     */
    function ComboCargo($arrayParametros) 
    {
    	$intEditalId = ( !( isset( $arrayParametros['editalId'] ) ) ? "" : $arrayParametros['editalId'] );
    	
    	$objNumeric = new Numeric();
	    	
    	if( !($objNumeric->IsInteger($intEditalId)) ){
    		$intEditalId = "";
    	}
    	
    	if( trim($intEditalId) != "" ){
	        $this->name = $arrayParametros['name'];
	        $this->conn = $arrayParametros['DB'];
	        $this->setValue( $arrayParametros['value'] );
	        $js = ( !( isset( $arrayParametros['js'] ) ) ? "" : $arrayParametros['js'] );
	        $disable = ( !( isset( $arrayParametros['disable'] ) ) ? false : $arrayParametros['disable'] );
	        $multiplo = ( !( isset( $arrayParametros['multiplo'] ) ) ? true : $arrayParametros['multiplo'] );
	    	$selecione = ( !( isset( $arrayParametros['selecione'] ) ) ? "Selecione" : $arrayParametros['selecione'] );
	        
	    	
	    	
	    	$sql = "SELECT c.crg_descr, c.id_cargo, n.id_nivel || ' - ' || n.nvl_descr AS nivel"
		        				."\n FROM cargo c, nivel n"
		        				."\n WHERE c.nivel_id = n.id_nivel" 
		        				."\n AND c.edital_id = ".$intEditalId 
		        				."\n ORDER BY nivel, c.crg_descr";

		    $this->setSQL( $sql );	
	        
	        $this->setKeyColumn( "ID_CARGO" );
	        $this->setValueColumn( "CRG_DESCR" );
    		if($multiplo){
	        	$this->setUseCustomItem( false );
	        	$this->setCustomItemValue( "" );
				$this->setSize("5");
			} else {
				$this->setSize("1");
				$this->setUseCustomItem( true );
		        $this->setCustomItemValue( $selecione );
			}
	        $this->setCustomItemKey( "" );
	        $this->setJs($js);
	        $this->setdisable($disable);
	        $this->setAgruparColumn(true);
	        $this->setMultiploValue($multiplo);
	        $this->build();
    	}else{
    		$this->options = array();
    	}
    }
}






/**
 * Monta uma combo com os cargos liberados para cadastro via web 
 * SÓ MOSTRA OS CARGOS QUE FORAM LIBERADOS PARA INSCRIÇÃO VIA WEB
 *
 */
 
 class ComboCargoLiberadoWeb extends DBComboBox {
	
    function ComboCargoLiberadoWeb($arrayParametros) 
    {
    	$intEditalId = ( !( isset( $arrayParametros['editalId'] ) ) ? "" : $arrayParametros['editalId'] );
    	
    	$objNumeric = new Numeric();
	    	
    	if( !($objNumeric->IsInteger($intEditalId)) ){
    		$intEditalId = "";
    	}
    	
    	if( trim($intEditalId) != "" ){
	        $this->name = $arrayParametros['name'];
	        $this->conn = $arrayParametros['DB'];
	        $this->setValue( $arrayParametros['value'] );
	        $js = ( !( isset( $arrayParametros['js'] ) ) ? "" : $arrayParametros['js'] );
	        $disable = ( !( isset( $arrayParametros['disable'] ) ) ? false : $arrayParametros['disable'] );
	        $multiplo = ( !( isset( $arrayParametros['multiplo'] ) ) ? true : $arrayParametros['multiplo'] );
	    	$selecione = ( !( isset( $arrayParametros['selecione'] ) ) ? "Selecione" : $arrayParametros['selecione'] );
	        
	    	
	    	
	    	$sql = "SELECT c.crg_descr, c.id_cargo, n.id_nivel || ' - ' || n.nvl_descr AS nivel"
		        				."\n FROM cargo c, nivel n"
		        				."\n WHERE c.nivel_id = n.id_nivel" 
								."\n AND c.crg_permite_inscr_web = 't'"
		        				."\n AND c.edital_id = ".$intEditalId 
		        				."\n ORDER BY nivel, c.crg_descr";

		    $this->setSQL( $sql );	
	        
	        $this->setKeyColumn( "ID_CARGO" );
	        $this->setValueColumn( "CRG_DESCR" );
    		if($multiplo){
	        	$this->setUseCustomItem( false );
	        	$this->setCustomItemValue( "" );
				$this->setSize("5");
			} else {
				$this->setSize("1");
				$this->setUseCustomItem( true );
		        $this->setCustomItemValue( $selecione );
			}
	        $this->setCustomItemKey( "" );
	        $this->setJs($js);
	        $this->setdisable($disable);
	        $this->setAgruparColumn(true);
	        $this->setMultiploValue($multiplo);
	        $this->build();
    	}else{
    		$this->options = array();
    	}
    }
}






/**
 * Monta uma combo com as titulações cadastrados
 *
 */
class ComboTitulacao extends DBComboBox 
{
    /**
     * Construtor da classe
     *
     * @param object $arrayParametros['DB']
     * @param string $arrayParametros['name']
     * @param string $arrayParametros['value']
     * @param boolean $arrayParametros['disable']
     * @param string $arrayParametros['js']
     * @param integer $arrayParametros['cargoId']
     * @param boolean $arrayParametros['multiplo']
     * @return void
     * @access public
     */
    function ComboTitulacao($arrayParametros)
    {
	
        $this->name = $arrayParametros['name'];
        $this->conn = $arrayParametros['DB'];
        $this->setValue( $arrayParametros['value'] );
        $js = ( !( isset( $arrayParametros['js'] ) ) ? "" : $arrayParametros['js'] );
        $disable = ( !( isset( $arrayParametros['disable'] ) ) ? false : $arrayParametros['disable'] );
        $multiplo = ( !( isset( $arrayParametros['multiplo'] ) ) ? true : $arrayParametros['multiplo'] );
        $selecione = ( !( isset( $arrayParametros['selecione'] ) ) ? "Selecione" : $arrayParametros['selecione'] );
        $cargoId = ( !( isset( $arrayParametros['cargoId'] ) ) ? "" : $arrayParametros['cargoId'] );
		
		/* GAMBI PRA USAR A MESMA FUNÇÃO */
		/*if(($_SESSION["userid"] == "") || ($_SESSION["userid"] == null) || ($_SESSION["userid"] == 1)) // USUARIO WEB
		{
		$altera_sql = '';
		$seta_js = 'onchange="manda_valor_combo_titulacao()"';
		}
		else 
		{
		$altera_sql = 'AND tc.cargo_id = '.$cargoId.'';
		$seta_js = $js;
		}*/
		/* FIM */
        
        $sql = "SELECT id_titulacao, tit_descr"
	        				."\n FROM titulacao"
	        				."\n ORDER BY tit_descr, id_titulacao";
	        				
        if( trim( $cargoId ) != "" ){
        	$sql = "SELECT t.id_titulacao, t.tit_descr"
	        				."\n FROM titulacao t, titulacao_x_cargo tc"
	        				."\n WHERE t.id_titulacao = tc.titulacao_id"
	        				//." $altera_sql"
							."\n AND tc.cargo_id = ".$cargoId
	        				."\n ORDER BY t.tit_descr, t.id_titulacao";
							
							//echo $sql;
							//var_dump($_SESSION["userid"]);
        }
	    
        $this->setSQL( $sql);
		
        $this->setKeyColumn( "ID_TITULACAO" );
        $this->setValueColumn( "TIT_DESCR" );
    	if($multiplo){
        	$this->setUseCustomItem( false );
        	$this->setCustomItemValue( "" );
			$this->setSize("5");
		} else {
			$this->setSize("1");
			$this->setUseCustomItem( true );
	        $this->setCustomItemValue( $selecione );
		}
        $this->setCustomItemKey( "" );
		
        $this->setJs($js);
		//$this->setJs($seta_js);
		
        $this->setdisable($disable);
        $this->setAgruparColumn(false);
        $this->setMultiploValue($multiplo);
        $this->build();
    }
}










class ComboTitulacaoWeb extends DBComboBox 
{
    function ComboTitulacaoWeb($arrayParametros)
    {

        $this->name = $arrayParametros['name'];
        $this->conn = $arrayParametros['DB'];
        $this->setValue( $arrayParametros['value'] );
        $js = ( !( isset( $arrayParametros['js'] ) ) ? "" : $arrayParametros['js'] );
        $disable = ( !( isset( $arrayParametros['disable'] ) ) ? false : $arrayParametros['disable'] );
        $multiplo = ( !( isset( $arrayParametros['multiplo'] ) ) ? true : $arrayParametros['multiplo'] );
        $selecione = ( !( isset( $arrayParametros['selecione'] ) ) ? "Selecione" : $arrayParametros['selecione'] );
        $cargoId = ( !( isset( $arrayParametros['cargoId'] ) ) ? "" : $arrayParametros['cargoId'] );
        
	
        
							
		$sql = "SELECT t.id_titulacao, t.tit_descr
				FROM titulacao t
				ORDER BY t.tit_descr, t.id_titulacao";
							
        
	    
        $this->setSQL( $sql);
		
        $this->setKeyColumn( "ID_TITULACAO" );
        $this->setValueColumn( "TIT_DESCR" );
    	if($multiplo){
        	$this->setUseCustomItem( false );
        	$this->setCustomItemValue( "" );
			$this->setSize("5");
		} else {
			$this->setSize("1");
			$this->setUseCustomItem( true );
	        $this->setCustomItemValue( $selecione );
		}
        $this->setCustomItemKey( "" );
        //$this->setJs($js);
		$this->setJs('onchange="manda_valor_combo_titulacao()"');
        $this->setdisable($disable);
        $this->setAgruparColumn(false);
        $this->setMultiploValue($multiplo);
        $this->build();
    }
}











/**
 * Monta uma combo com as Atividades cadastradas
 *
 */
class ComboAtividade extends DBComboBox 
{
    /**
     * Construtor da classe
     *
     * @param object $arrayParametros['DB']
     * @param string $arrayParametros['name']
     * @param string $arrayParametros['value']
     * @param boolean $arrayParametros['disable']
     * @param string $arrayParametros['js']
     * @param integer $arrayParametros['cargoId']
     * @param boolean $arrayParametros['multiplo']
     * @return void
     * @access public
     */
    function ComboAtividade($arrayParametros)
    {
        $this->name = $arrayParametros['name'];
        $this->conn = $arrayParametros['DB'];
        $this->setValue( $arrayParametros['value'] );
        $js = ( !( isset( $arrayParametros['js'] ) ) ? "" : $arrayParametros['js'] );
        $disable = ( !( isset( $arrayParametros['disable'] ) ) ? false : $arrayParametros['disable'] );
        $multiplo = ( !( isset( $arrayParametros['multiplo'] ) ) ? true : $arrayParametros['multiplo'] );
        $selecione = ( !( isset( $arrayParametros['selecione'] ) ) ? "Selecione" : $arrayParametros['selecione'] );
        $cargoId = ( !( isset( $arrayParametros['cargoId'] ) ) ? "" : $arrayParametros['cargoId'] );
        
        $sql = "SELECT id_funcao, fun_descr"
	        				."\n FROM funcao"
	        				."\n ORDER BY fun_descr, id_funcao";
	        				
    	if( trim( $cargoId ) != "" ){
        	$sql = "SELECT DISTINCT(f.id_funcao), f.fun_descr"
	        				."\n FROM temp_exp_x_cargo_x_funcao tf, funcao f"
	        				."\n WHERE tf.funcao_id = f.id_funcao"
	        				."\n AND tf.cargo_id = ".$cargoId
	        				."\n ORDER BY f.id_funcao";
        }
        
	    $this->setSQL( $sql );
		
        $this->setKeyColumn( "ID_FUNCAO" );
        $this->setValueColumn( "FUN_DESCR" );
    	if($multiplo){
        	$this->setUseCustomItem( false );
        	$this->setCustomItemValue( "" );
			$this->setSize("5");
		} else {
			$this->setSize("1");
			$this->setUseCustomItem( true );
	        $this->setCustomItemValue( $selecione );
		}
        $this->setCustomItemKey( "" );
        $this->setJs($js);
        $this->setdisable($disable);
        $this->setAgruparColumn(false);
        $this->setMultiploValue($multiplo);
        $this->build();
    }
}

/**
 * Monta uma combo com os Tempo de experiencia cadastradas
 *
 */
class ComboTempoExperiencia extends DBComboBox 
{
    /**
     * Construtor da classe
     *
     * @param object $arrayParametros['DB']
     * @param string $arrayParametros['name']
     * @param string $arrayParametros['value']
     * @param boolean $arrayParametros['disable']
     * @param string $arrayParametros['js']
     * @param integer $arrayParametros['cargoId']
     * @param integer $arrayParametros['atividadeId']
     * @param boolean $arrayParametros['multiplo']
     * @return void
     * @access public
     */
    function ComboTempoExperiencia($arrayParametros)
    {
        $this->name = $arrayParametros['name'];
        $this->conn = $arrayParametros['DB'];
        $this->setValue( $arrayParametros['value'] );
        $js = ( !( isset( $arrayParametros['js'] ) ) ? "" : $arrayParametros['js'] );
        $disable = ( !( isset( $arrayParametros['disable'] ) ) ? false : $arrayParametros['disable'] );
        $multiplo = ( !( isset( $arrayParametros['multiplo'] ) ) ? true : $arrayParametros['multiplo'] );
        $selecione = ( !( isset( $arrayParametros['selecione'] ) ) ? "Selecione" : $arrayParametros['selecione'] );
        $cargoId = ( !( isset( $arrayParametros['cargoId'] ) ) ? "" : $arrayParametros['cargoId'] );
        $atividadeId = ( !( isset( $arrayParametros['atividadeId'] ) ) ? "" : $arrayParametros['atividadeId'] );
        
        $sql = "SELECT id_tempo_experiencia, tpe_descr"
	        				."\n FROM tempo_experiencia"
	        				."\n ORDER BY tpe_descr, id_tempo_experiencia";
	        				
    	if( ( trim( $cargoId ) != "" ) || ( trim( $atividadeId ) != "" ) ){
    		
    		$sql = "SELECT DISTINCT(t.id_tempo_experiencia), t.tpe_descr"
		        				."\n FROM temp_exp_x_cargo_x_funcao tf, tempo_experiencia t"
		        				."\n WHERE tf.tempo_experiencia_id = t.id_tempo_experiencia";	
		        				
    		if( trim( $cargoId ) != "" ){
	        	$sql .= "\n AND tf.cargo_id = ".$cargoId;
    		}
    		
    		if( trim( $atividadeId ) != "" ){
    			$sql .= "\n AND tf.funcao_id = ".$atividadeId;
    		}
    		
    		$sql .= "\n ORDER BY t.tpe_descr, t.id_tempo_experiencia";
        }
        
	    $this->setSQL( $sql );
		
        $this->setKeyColumn( "ID_TEMPO_EXPERIENCIA" );
        $this->setValueColumn( "TPE_DESCR" );
        
    	if($multiplo){
        	$this->setUseCustomItem( false );
        	$this->setCustomItemValue( "" );
			$this->setSize("5");
		} else {
			$this->setSize("1");
			$this->setUseCustomItem( true );
	        $this->setCustomItemValue( $selecione );
		}
		$this->setCustomItemKey( "" );
        $this->setJs($js);
        $this->setdisable($disable);
        $this->setAgruparColumn(false);
        $this->setMultiploValue($multiplo);
        $this->build();
    }
}

/**
 * Monta uma combo com os Grupos de Questão cadastradas
 *
 */
class ComboGrupoQuestao extends DBComboBox 
{
    /**
     * Construtor da classe
     *
     * @param object $arrayParametros['DB']
     * @param string $arrayParametros['name']
     * @param string $arrayParametros['value']
     * @param boolean $arrayParametros['disable']
     * @param string $arrayParametros['js']
     * @param integer $arrayParametros['cargoId']
     * @param integer $arrayParametros['atividadeId']
     * @param boolean $arrayParametros['multiplo']
     * @param array $arrayParametros['cargo']
     * @return void
     * @access public
     */
    function ComboGrupoQuestao($arrayParametros)
    {
        $this->name = $arrayParametros['name'];
        $this->conn = $arrayParametros['DB'];
        $this->setValue( $arrayParametros['value'] );
        $js = ( !( isset( $arrayParametros['js'] ) ) ? "" : $arrayParametros['js'] );
        $disable = ( !( isset( $arrayParametros['disable'] ) ) ? false : $arrayParametros['disable'] );
        $multiplo = ( !( isset( $arrayParametros['multiplo'] ) ) ? false : $arrayParametros['multiplo'] );
        $selecione = ( !( isset( $arrayParametros['selecione'] ) ) ? "Selecione" : $arrayParametros['selecione'] );
        $cargo = ( isset( $arrayParametros['cargo'] ) ? $arrayParametros['cargo'] : "" );
        
        $fromComp = "";
        $whereComp = "";
        if( trim( $cargo != "" ) ){
        	$idCargo = "";
        	foreach( $cargo as $chave => $value ){
        		if( $idCargo == "" ){
        			$idCargo .= $value;
        		}else{
        			$idCargo .= ", ".$value;
        		}
        	}
        	
        	$fromComp = ", cargo_x_grupo_questao cgq";
        	$whereComp = "\n WHERE cgq.cargo_id IN ( ".$idCargo." )"
        					."\n AND gq.id_grupo_questao = cgq.grupo_questao_id";
        }
        
        $sql = "SELECT gq.id_grupo_questao, gq.grq_descr"
        		."\n FROM grupo_questao gq".$fromComp
        		.$whereComp
        		."\n ORDER BY gq.grq_descr, gq.id_grupo_questao";

	    $this->setSQL( $sql );
		
        $this->setKeyColumn( "ID_GRUPO_QUESTAO" );
        $this->setValueColumn( "GRQ_DESCR" );
        
    	if($multiplo){
        	$this->setUseCustomItem( false );
        	$this->setCustomItemValue( "" );
			$this->setSize("5");
		} else {
			$this->setSize("1");
			$this->setUseCustomItem( true );
	        $this->setCustomItemValue( $selecione );
		}
		$this->setCustomItemKey( "" );
        $this->setJs($js);
        $this->setdisable($disable);
        $this->setAgruparColumn(false);
        $this->setMultiploValue($multiplo);
        $this->build();
    }
}

/**
 * retorna uma combo de salas por local
 *
 */
class ComboSala extends ComboBox {

	/**
     * Construtor da classe
     *
     * @param object $arrayParametros['DB']
     * @param string $arrayParametros['name']
     * @param string $arrayParametros['label']
     * @param string $arrayParametros['value']
     * @param boolean $arrayParametros['disable']
     * @param integer $arrayParametros['local']
     * @param string $arrayParametros['id']
     * @param string $arrayParametros['js']
     * @return void
     * @access public
     */
    function ComboSala($arrayParametros) {
        $name = $arrayParametros['name'];
        $label = ( !( isset( $arrayParametros['label'] ) ) ? "" : $arrayParametros['label'] );
        $objConn = $arrayParametros['DB'];
        $localId = ( !( isset( $arrayParametros['local'] ) ) ? "" : $arrayParametros['local'] );
        $strCpfCandidato = ( !( isset( $arrayParametros['cpf'] ) ) ? "" : $arrayParametros['cpf'] );
        $intIdEdital = ( !( isset( $arrayParametros['edital'] ) ) ? "" : $arrayParametros['edital'] );
        $intIdCargo = ( !( isset( $arrayParametros['cargo'] ) ) ? "" : $arrayParametros['cargo'] );
        $id = ( !( isset( $arrayParametros['id'] ) ) ? "i_".$name : $arrayParametros['id'] );
        $value = ( !( isset( $arrayParametros['value'] ) ) ? "X" : "X".$arrayParametros['value'] );
        $js = ( !( isset( $arrayParametros['js'] ) ) ? "" : $arrayParametros['js'] );
        $disable = ( !( isset( $arrayParametros['disable'] ) ) ? false : $arrayParametros['disable'] );
        $selecione = ( !( isset( $arrayParametros['selecione'] ) ) ? "Selecione" : $arrayParametros['selecione'] );
        $objQry = new genericQuery($objConn);

        $array = array();
        
	if(trim($intIdEdital) != "" && trim($localId) != "" ){
        	$sql = "SELECT s.id_sala, s.sla_descr || ' - ' || s.sla_lotacao AS sala_lotacao"
				."\n FROM sala s, sala_x_cargo sc, cargo c"
				."\n WHERE c.edital_id = ".$intIdEdital
				."\n	AND sc.cargo_id = c.id_cargo"
				."\n	AND s.id_sala = sc.sala_id"
				."\n	AND s.local_prova_id = ".$localId;
			

		

	        
        }	

	elseif( trim($localId) != "" ){
	        $sql = "SELECT s.id_sala, s.sla_descr || ' - ' || s.sla_lotacao AS sala_lotacao"
        			."\n FROM sala s"
        			."\n WHERE s.local_prova_id = ".$localId 
        			."\n ORDER BY s.sla_descr, s.sla_lotacao";
	        
        }elseif(trim($strCpfCandidato) != ""){
        	$sql = "SELECT s.id_sala, s.sla_descr || ' - ' || s.sla_lotacao AS sala_lotacao"
				."\n FROM sala s, sala_x_cargo sc, inscricao i"
				."\n WHERE i.candidato_cpf = '".$strCpfCandidato."'"
				."\n	AND sc.cargo_id = i.cargo_id"
				."\n	AND s.id_sala = sc.sala_id";
	        
        } elseif(trim($intIdEdital) != "" && trim($intIdCargo) != "") {
        	$sql = "SELECT s.id_sala, s.sla_descr || ' - ' || s.sla_lotacao AS sala_lotacao"
				."\n FROM sala s, sala_x_cargo sc, cargo c"
				."\n WHERE c.edital_id = ".$intIdEdital
				."\n	AND c.id_cargo = ".$intIdCargo
				."\n	AND sc.cargo_id = c.id_cargo"
				."\n	AND s.id_sala = sc.sala_id";
	        
        } elseif(trim($intIdEdital) != ""){
        	$sql = "SELECT s.id_sala, s.sla_descr || ' - ' || s.sla_lotacao AS sala_lotacao"
				."\n FROM sala s, sala_x_cargo sc, cargo c"
				."\n WHERE c.edital_id = ".$intIdEdital
				."\n	AND sc.cargo_id = c.id_cargo"
				."\n	AND s.id_sala = sc.sala_id";
		

	        
        } elseif(trim($intIdCargo) != ""){
        	$sql = "SELECT s.id_sala, s.sla_descr || ' - ' || s.sla_lotacao AS sala_lotacao"
				."\n FROM sala s, sala_x_cargo sc, cargo c"
				."\n WHERE c.id_cargo = ".$intIdCargo
				."\n	AND sc.cargo_id = c.id_cargo"
				."\n	AND s.id_sala = sc.sala_id";
	        
        } else {
        	$sql = "SELECT s.id_sala, s.sla_descr || ' - ' || s.sla_lotacao AS sala_lotacao"
				."\n FROM sala s";
        }

		       

    	$objQry->TQuery($sql);
        while($linha = $objQry->fetchrow()){
        	$chave = "X".$linha["ID_SALA"];
        	$array[$chave] = $linha["SALA_LOTACAO"];
        }
        
        $this->FormField($label, $name, $value);
        $this->disable = $disable;
        $this->js = $js;
        $this->options = $array;
        @reset($this->options);
        $o = @current($this->options);
        $this->id = $id;
        $this->agrupar = false;
        $this->multiplo = false;
    	//verifica se eh necessario colocar o selecione
        if (!empty($selecione)) {
            // array multi-dimensional (result from db query)
            if (is_array($o)) {
                $default = array('', $selecione);
                $this->options = array_merge(array($default), $this->options);
            } else {
                // simple array
                $default = array('' => $selecione);
                $this->options = array_merge($default, $this->options);
            }
        }
		$this->getCode();
    }
}

/**
 * retorna uma combo de editais
 *
 */
class ComboEdital extends ComboBox {

	/**
     * Construtor da classe
     *
     * @param object $arrayParametros['DB']
     * @param string $arrayParametros['name']
     * @param string $arrayParametros['label']
     * @param string $arrayParametros['value']
     * @param boolean $arrayParametros['disable']
     * @param string $arrayParametros['id']
     * @param string $arrayParametros['js']
     * @param boolean $arrayParametros['ativo']
     * @return void
     * @access public
     */
    function ComboEdital($arrayParametros) {
        $name = (isset($arrayParametros['name']))?$arrayParametros['name']:"";
        $label = (isset($arrayParametros['label']))?$arrayParametros['label']:"";
        $objConn = $arrayParametros['DB'];
        $id = (isset($arrayParametros['id']))?$arrayParametros['id']:"i_".$name;
        $value = (isset($arrayParametros['value']))?$arrayParametros['value']:"";
        $ativo = (isset($arrayParametros['ativo']))?$arrayParametros['ativo']:true;
        $js = ( !( isset( $arrayParametros['js'] ) ) ? "" : $arrayParametros['js'] );
        $disable = (isset($arrayParametros['disable']))?$arrayParametros['disable']:false;
        $selecione = ( !( isset( $arrayParametros['selecione'] ) ) ? "Selecione" : $arrayParametros['selecione'] );
        $objQry = new genericQuery($objConn);

        $array = array();
        $sql = "SELECT e.id_edital, e.edi_num_edital|| ' - ' || i.inst_descr AS edital_descr"
        				."\n FROM edital e, instituicao i"
        				."\n WHERE e.instituicao_cnpj = i.cnpj_instituicao";
        if($ativo){
        	$sql .= "\n AND TO_DATE( TO_CHAR( edi_data_contestacao, 'YYYY-MM-DD' ),'YYYY-MM-DD' ) >=" 
        				."\n TO_DATE( TO_CHAR( now(), 'YYYY-MM-DD' ),'YYYY-MM-DD' )";
        }
        $sql .= "\n ORDER BY edi_num_edital, edi_descr";
       
        $objQry->TQuery($sql);
        while($linha = $objQry->fetchrow()){
        	$chave = "X".$linha["ID_EDITAL"];
        	$array[$chave] = $linha["EDITAL_DESCR"];
        }
        
        $this->FormField($label, $name, "X".$value);
    
        $this->disable = $disable;
        $this->js = $js;
        $this->options = $array;
        @reset($this->options);
        $o = @current($this->options);
        $this->id = $id;
        $this->agrupar = false;
        $this->multiplo = false;  
        
    	if (!empty($selecione)) {
            // array multi-dimensional (result from db query)
            if (is_array($o)) {
                $default = array('', $selecione);
                $this->options = array_merge(array($default), $this->options);
            } else {
                // simple array
                $default = array('' => $selecione);
                $this->options = array_merge($default, $this->options);
            }
        }
        
		$this->getCode();
    }
}


/**
 * retorna uma combo de provas
 *
 */
class ComboProva extends ComboBox {
	/**
     * Construtor da classe
     *
     * @param object $arrayParametros['DB']
     * @param string $arrayParametros['name']
     * @param string $arrayParametros['label']
     * @param string $arrayParametros['value']
     * @param boolean $arrayParametros['disable']
     * @param string $arrayParametros['id']
     * @param string $arrayParametros['js']
     * @return void
     * @access public
     */
    function ComboProva($arrayParametros) {
        $name 		= (isset($arrayParametros['name']))?$arrayParametros['name']:"";
        $label 		= (isset($arrayParametros['label']))?$arrayParametros['label']:"";
        $editalId 	= (isset($arrayParametros['editalId']))?$arrayParametros['editalId']:"";
        $cargoId 	= (isset($arrayParametros['cargoId']))?$arrayParametros['cargoId']:"";
        $objConn 	= $arrayParametros['DB'];
        $id 		= (isset($arrayParametros['id']))?$arrayParametros['id']:"i_".$name;
        $value 		= (isset($arrayParametros['value']))?$arrayParametros['value']:"";
        $js 		= ( !( isset( $arrayParametros['js'] ) ) ? "" : $arrayParametros['js'] );
        $disable 	= (isset($arrayParametros['disable']))?$arrayParametros['disable']:false;
        $selecione 	= ( !( isset( $arrayParametros['selecione'] ) ) ? "Selecione" : $arrayParametros['selecione'] );
        $objQry 	= new genericQuery($objConn);

        $array = array();
        $sql = "SELECT cp.prova_descricao, cp.prova_sequencia"
        				."\n FROM cargo_x_prova cp"
						."\n WHERE 1 = 1";
						
		if($editalId != ""){
			$sql .= "\n AND cp.edital_id = ".$editalId;
		}
		
		if($cargoId != ""){
			$sql .= "\n AND cp.id_cargo = ".$cargoId;
		}
		
        $sql .= "\n ORDER BY  cp.prova_sequencia";
       
        $objQry->TQuery($sql);
        while($linha = $objQry->fetchrow()){
        	$chave 			= "X".$linha["PROVA_SEQUENCIA"];
        	$array[$chave] 	= $linha["PROVA_DESCRICAO"];
        }
        
        $this->FormField($label, $name, "X".$value);
    
        $this->disable = $disable;
        $this->js = $js;
        $this->options = $array;
        @reset($this->options);
        $o = @current($this->options);
        $this->id = $id;
        $this->agrupar = false;
        $this->multiplo = false;  
        
    	if (!empty($selecione)) {
            // array multi-dimensional (result from db query)
            if (is_array($o)) {
                $default = array('', $selecione);
                $this->options = array_merge(array($default), $this->options);
            } else {
                // simple array
                $default = array('' => $selecione);
                $this->options = array_merge($default, $this->options);
            }
        }
        
		$this->getCode();
    }// function ComboProva($arrayParametros) {
}

/**
 * retorna uma combo de locais de prova
 *
 */
class ComboLocalProva extends DBComboBox {

	/**
     * Construtor da classe
     *
     * @param object $arrayParametros['DB']
     * @param string $arrayParametros['name']
     * @param string $arrayParametros['label']
     * @param string $arrayParametros['value']
     * @param boolean $arrayParametros['disable']
     * @param string $arrayParametros['id']
     * @param string $arrayParametros['js']
     * @param boolean $arrayParametros['ativo']
     * @return void
     * @access public
     */
    function ComboLocalProva($arrayParametros) {
    	
        $this->name = $arrayParametros['name'];
        $this->conn = $arrayParametros['DB'];
        $this->setValue( $arrayParametros['value'] );
        $js = ( !( isset( $arrayParametros['js'] ) ) ? "" : $arrayParametros['js'] );
        $disable = ( !( isset( $arrayParametros['disable'] ) ) ? false : $arrayParametros['disable'] );
        $multiplo = ( !( isset( $arrayParametros['multiplo'] ) ) ? true : $arrayParametros['multiplo'] );
    	$selecione = ( !( isset( $arrayParametros['selecione'] ) ) ? "Selecione" : $arrayParametros['selecione'] );
        $edital = intval($arrayParametros['edital']);

        //se recebeu um edital por parametro lista apenas os locais para aquele edital
        if( $edital > 0 ){
            $sql = "
                    SELECT DISTINCT(lc.id_local_prova), lc.lcl_descr
                    FROM sala_x_cargo AS sc
                    INNER JOIN sala AS s
                    ON sc.sala_id = s.id_sala
                    INNER JOIN local_prova AS lc
                    ON lc.id_local_prova = s.local_prova_id
                    WHERE sc.edital_id = ".$edital."
                    ORDER BY lc.lcl_descr";

        }
        else{
            $sql = "SELECT id_local_prova, lcl_descr"
        		."\n FROM local_prova"
        		."\n ORDER BY lcl_descr";

        }

    	

	    $this->setSQL( $sql );

        $this->setKeyColumn( "ID_LOCAL_PROVA" );
        $this->setValueColumn( "LCL_DESCR" );
        
    	if($multiplo){
        	$this->setUseCustomItem( false );
        	$this->setCustomItemValue( "" );
			$this->setSize("5");
		} else {
			$this->setSize("1");
			if ( isset( $arrayParametros['selecione'] ) ) {
				$this->setUseCustomItem( true );
			    $this->setCustomItemValue( $selecione );
			}
		}
		
        $this->setCustomItemKey( "" );
        $this->setJs($js);
        $this->setdisable($disable);
        $this->setAgruparColumn(false);
        $this->setMultiploValue($multiplo);
        $this->build();
    }
}

/**
 * retorna uma combo de critério de desempate
 *
 */
class ComboCriterioDesempate extends ComboBox 
{
	/**
     * Construtor da classe
     *
     * @param object $arrayParametros['DB']
     * @param int $arrayParametros['name']
     * @param string $arrayParametros['label']
     * @param string $arrayParametros['value']
     * @param int $arrayParametros['edital_id']
     * @param boolean $arrayParametros['disable']
     * @param string $arrayParametros['id']
     * @param string $arrayParametros['js']
     * @param string $arrayParametros['selecione']
     * @return ComboCriterioDesempate
     * @access public
     */
    function ComboCriterioDesempate($arrayParametros) 
    {
        $objConn = $arrayParametros['DB'];
        $name = (isset($arrayParametros['name']))?$arrayParametros['name']:"";
        $label = (isset($arrayParametros['label']))?$arrayParametros['label']:"";
        $id = (isset($arrayParametros['id']))?$arrayParametros['id']:"i_".$name;
        $value = (isset($arrayParametros['value']))?$arrayParametros['value']:"";
        $js = ( !( isset( $arrayParametros['js'] ) ) ? "" : $arrayParametros['js'] );
        $intIdEdital = (isset($arrayParametros['edital_id']))?$arrayParametros['edital_id']:"";
        $disable = (isset($arrayParametros['disable']))?$arrayParametros['disable']:false;
        $selecione = ( !( isset( $arrayParametros['selecione'] ) ) ? "Selecione" : $arrayParametros['selecione'] );
        $objQry = new genericQuery($objConn);
        
        $arrCriterio = array();
        if(trim($intIdEdital) != ""){
	        $sql = "SELECT id_tipo_criterio, tipo_criterio_descr"
	        		."\n FROM tipos_criterio_desempate";
	        
	        $objQry->TQuery($sql);
	        while($row = $objQry->fetchrow()){
	        	$arrCriterio["X".$row["ID_TIPO_CRITERIO"]] = $row["TIPO_CRITERIO_DESCR"];
	        }
        }
        
        $this->FormField($label, $name, $value);
        $this->disable = $disable;
        $this->js = $js;
        $this->options = $arrCriterio;
        @reset($this->options);
        $o = @current($this->options);
        $this->id = $id;
        $this->agrupar = false;
        $this->multiplo = false;  
        
    	if (!empty($selecione)) {
            // array multi-dimensional (result from db query)
            if (is_array($o)) {
                $default = array('', $selecione);
                $this->options = array_merge(array($default), $this->options);
            } else {
                // simple array
                $default = array('' => $selecione);
                $this->options = array_merge($default, $this->options);
            }
        }
        
		$this->getCode();
    }//ComboCriterioDesempate
}


/**
 * Monta uma combo de maior e menor
 *
 */
class ComboMaiorMenor extends ComboBox 
{
	/**
     * Construtor da classe
     *
     * @param int $arrayParametros['name']
     * @param string $arrayParametros['label']
     * @param string $arrayParametros['value']
     * @param boolean $arrayParametros['disable']
     * @param string $arrayParametros['id']
     * @param string $arrayParametros['js']
     * @param string $arrayParametros['selecione']
     * @return ComboMaiorMenor
     * @access public
     */
    function ComboMaiorMenor($arrayParametros) 
    {
        $name = (isset($arrayParametros['name']))?$arrayParametros['name']:"";
        $label = (isset($arrayParametros['label']))?$arrayParametros['label']:"";
        $id = (isset($arrayParametros['id']))?$arrayParametros['id']:"i_".$name;
        $value = (isset($arrayParametros['value']))?$arrayParametros['value']:"";
        $js = ( !( isset( $arrayParametros['js'] ) ) ? "" : $arrayParametros['js'] );
        $disable = (isset($arrayParametros['disable']))?$arrayParametros['disable']:false;
        $selecione = ( !( isset( $arrayParametros['selecione'] ) ) ? "Selecione" : $arrayParametros['selecione'] );
        
        $array["DESC"] = "MAIOR";
        $array["ASC"] = "MENOR";
        
        $this->FormField($label, $name, $value);
        $this->disable = $disable;
        $this->js = $js;
        $this->options = $array;
        @reset($this->options);
        $o = @current($this->options);
        $this->id = $id;
        $this->agrupar = false;
        $this->multiplo = false;  
        
    	if (!empty($selecione)) {
            // array multi-dimensional (result from db query)
            if (is_array($o)) {
                $default = array('', $selecione);
                $this->options = array_merge(array($default), $this->options);
            } else {
                // simple array
                $default = array('' => $selecione);
                $this->options = array_merge($default, $this->options);
            }
        }
        
		$this->getCode();
    }//ComboMaiorMenor
}

/**
 * retorna uma combo de estados
 *
 */
class ComboEstado extends ComboBox 
{

	/**
     * Construtor da classe
     *
     * @param object $arrayParametros['DB']
     * @param string $arrayParametros['name']
     * @param string $arrayParametros['label']
     * @param string $arrayParametros['value']
     * @param boolean $arrayParametros['disable']
     * @param string $arrayParametros['id']
     * @param string $arrayParametros['js']
     * @param boolean $arrayParametros['selecione']
     * @return void
     * @access public
     */
    function ComboEstado($arrayParametros) 
    {
        $name = (isset($arrayParametros['name']))?$arrayParametros['name']:"";
        $label = (isset($arrayParametros['label']))?$arrayParametros['label']:"";
        $objConn = $arrayParametros['DB'];
        $id = (isset($arrayParametros['id']))?$arrayParametros['id']:"i_".$name;
        $value = (isset($arrayParametros['value']))?$arrayParametros['value']:"";
        $js = ( !( isset( $arrayParametros['js'] ) ) ? "" : $arrayParametros['js'] );
        $disable = (isset($arrayParametros['disable']))?$arrayParametros['disable']:false;
        $selecione = ( !( isset( $arrayParametros['selecione'] ) ) ? "Selecione" : $arrayParametros['selecione'] );
        $objQry = new genericQuery($objConn);

        $array = array();
        $sql = "SELECT id_estado, est_nome "
        		."\n FROM estado"
        		."\n ORDER BY est_nome";
        	
        $objQry->TQuery($sql);
        while($linha = $objQry->fetchrow()){
        	$chave = "X".$linha["ID_ESTADO"];
        	$array[$chave] = $linha["EST_NOME"];
        }
        
        $this->FormField($label, $name, "X".$value);
        $this->disable = $disable;
        $this->js = $js;
        $this->options = $array;
        @reset($this->options);
        $o = @current($this->options);
        $this->id = $id;
        $this->agrupar = false;
        $this->multiplo = false;  
        
    	if (!empty($selecione)) {
            // array multi-dimensional (result from db query)
            if (is_array($o)) {
                $default = array('', $selecione);
                $this->options = array_merge(array($default), $this->options);
            } else {
                // simple array
                $default = array('' => $selecione);
                $this->options = array_merge($default, $this->options);
            }
        }
        
		$this->getCode();
    }//ComboEstado
}

/**
 * retorna uma combo de estados
 *
 */
class ComboCidade extends ComboBox 
{

	/**
     * Construtor da classe
     *
     * @param object $arrayParametros['DB']
     * @param string $arrayParametros['name']
     * @param string $arrayParametros['label']
     * @param string $arrayParametros['value']
     * @param boolean $arrayParametros['disable']
     * @param string $arrayParametros['id']
     * @param string $arrayParametros['js']
     * @param string $arrayParametros['id_estado']
     * @param boolean $arrayParametros['selecione']
     * @return void
     * @access public
     */
    function ComboCidade($arrayParametros) 
    {
        $name = (isset($arrayParametros['name'])) ? $arrayParametros['name'] : "";
        $label = (isset($arrayParametros['label'])) ? $arrayParametros['label'] : "";
        $objConn = $arrayParametros['DB'];
        $id = (isset($arrayParametros['id'])) ? $arrayParametros['id'] : "i_".$name;
        $value = (isset($arrayParametros['value'])) ? $arrayParametros['value'] : "";
        $js = ( !( isset( $arrayParametros['js'] ) ) ? "" : $arrayParametros['js'] );
        $disable = (isset($arrayParametros['disable'])) ? $arrayParametros['disable'] : false;
        $intIdEstado = (isset($arrayParametros['id_estado'])) ? $arrayParametros['id_estado'] : "";
        $selecione = ( !( isset( $arrayParametros['selecione'] ) ) ? "Selecione" : $arrayParametros['selecione'] );
        $objQry = new genericQuery($objConn);

        $array = array();
        if( trim($intIdEstado) != ""){
	        $sql = "SELECT id_cidade, cid_nome "
	        		."\n FROM cidade"
	        		."\n WHERE estado_id = ".$intIdEstado
	        		."\n ORDER BY cid_nome";
	        	
	        $objQry->TQuery($sql);
	        while($linha = $objQry->fetchrow()){
	        	$chave = "X".$linha["ID_CIDADE"];
	        	$array[$chave] = $linha["CID_NOME"];
	        }
        }
        
        $this->FormField($label, $name, "X".$value);
        $this->disable = $disable;
        $this->js = $js;
        $this->options = $array;
        @reset($this->options);
        $o = @current($this->options);
        $this->id = $id;
        $this->agrupar = false;
        $this->multiplo = false;  
        
    	if (!empty($selecione)) {
            // array multi-dimensional (result from db query)
            if (is_array($o)) {
                $default = array('', $selecione);
                $this->options = array_merge(array($default), $this->options);
            } else {
                // simple array
                $default = array('' => $selecione);
                $this->options = array_merge($default, $this->options);
            }
        }
        
		$this->getCode();
    }//ComboEstado
}

/**
 * retorna uma combo de bancos
 *
 */
class ComboBanco extends ComboBox 
{

	/**
     * Construtor da classe
     *
     * @param object $arrayParametros['DB']
     * @param string $arrayParametros['name']
     * @param string $arrayParametros['label']
     * @param string $arrayParametros['value']
     * @param boolean $arrayParametros['disable']
     * @param string $arrayParametros['id']
     * @param string $arrayParametros['js']
     * @param string $arrayParametros['id_banco']
     * @param boolean $arrayParametros['selecione']
     * @return void
     * @access public
     */
    function ComboBanco($arrayParametros) 
    {
        $name = (isset($arrayParametros['name'])) ? $arrayParametros['name'] : "";
        $label = (isset($arrayParametros['label'])) ? $arrayParametros['label'] : "";
        $objConn = $arrayParametros['DB'];
        $id = (isset($arrayParametros['id'])) ? $arrayParametros['id'] : "i_".$name;
        $value = (isset($arrayParametros['value'])) ? $arrayParametros['value'] : "";
        $js = ( !( isset( $arrayParametros['js'] ) ) ? "" : $arrayParametros['js'] );
        $disable = (isset($arrayParametros['disable'])) ? $arrayParametros['disable'] : false;
        $selecione = ( !( isset( $arrayParametros['selecione'] ) ) ? "Selecione" : $arrayParametros['selecione'] );
        $objQry = new genericQuery($objConn);

        $array = array();

        $sql = "SELECT id_banco, bco_desc "
        		."\n FROM banco"
        		."\n ORDER BY bco_desc";
        
        $objQry->TQuery($sql);
        
        while($linha = $objQry->fetchrow()){
        	$chave = "X".$linha["ID_BANCO"];
        	$array[$chave] = $linha["BCO_DESC"] . " (" . $linha["ID_BANCO"] . ")";
        }
        
        $this->FormField($label, $name, "X".$value);
        $this->disable = $disable;
        $this->js = $js;
        $this->options = $array;
        @reset($this->options);
        $o = @current($this->options);
        $this->id = $id;
        $this->agrupar = false;
        $this->multiplo = false;  
        
    	if (!empty($selecione)) {
            // array multi-dimensional (result from db query)
            if (is_array($o)) {
                $default = array('', $selecione);
                $this->options = array_merge(array($default), $this->options);
            } else {
                // simple array
                $default = array('' => $selecione);
                $this->options = array_merge($default, $this->options);
            }
        }
        
		$this->getCode();
    }//ComboBanco
}

/**
*   showBotoes
*
*   Cria botão centralizado
*   @param string $arrparametro['titulo'] Título do botão
*   @param string $arrparametro['link']   Link do botão
*   @param string $arrparametro['name']   Nome do botão
*   @param string $arrparametro['size']   Tamanho do botão
*   @param string $arrparametro['id']   Id do botão
*   @param string $arrparametro['invisivelImpressao']   Botão Vísivel para Impressão
*   @return string
*   @access public
*/
function showBotoes($arrParametro)
{
	$strTitulo = (isset($arrParametro['titulo']) ? $arrParametro['titulo'] : "");
	$strName = (isset($arrParametro['name']) ? $arrParametro['name'] : "bt");
	$strLink = (isset($arrParametro['link']) ? $arrParametro['link'] : "");
	$strSize = (isset($arrParametro['size']) ? $arrParametro['size'] : "");
	$strId = (isset($arrParametro['id']) ? $arrParametro['id'] : "");
	$boolInvisivelImpressao = (isset($arrParametro['invisivelImpressao']) ? $arrParametro['invisivelImpressao'] : true);
	
	$objFormBotao = new FormButton($strTitulo, $strName, $strLink, $strSize, $strId);
    $objToolBotao = new ToolButton();
    $objToolBotao->AddButton( new Text("<center>") );
    $objToolBotao->AddButton( $objFormBotao );
    $objToolBotao->AddButton( new Text("</center>") );
    $strRetorno = "<style type=\"text/css\">"
		        ."\n	@media print {"
		        ."\n		.naoVisivelImpressao {"
		        ."\n			display: none;"
		        ."\n		}"
		        ."\n	}"
		        ."\n</style>";
    
    $strRetorno .= "\n<br /><table border=0 align=center";
    
    if($boolInvisivelImpressao){
    	$strRetorno .= " class='naoVisivelImpressao'";
    }
    
    $strRetorno .= " >"
     			."\n	<tr>"
       			."\n		<td class=mostraCorFundo> "
       			."\n			".$objToolBotao->getCode()
       			."\n		</td>"
           		."\n	</tr>"
           		."\n</table>";
    return $strRetorno;
}
?>