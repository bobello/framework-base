<?php
require_once "comum/tela/package_form.class.php";
require_once "comum/tela/package_form_utils.class.php";
require_once "comum/tela/package_abas.class.php";
require_once "comum/util/class_table.class.php";
require_once "comum/util/package_scripts.class.php";
require_once "negocio/basico/NegCadCidade.php";

/**#####################################################################
# PROGRAMA: cadUsuario
# LOCAL: modulos/usuario/cadCidade.php
# DATA CRIAÇÃO: 29/05/2009
# PROGRAMADOR: GELSIMAR MACHADO
# OBJETIVO: Efetuar o cadastro de cidades no sistema
# DATA DE ENTRADA EM OPERAÇÃO: 28/05/2009
# PROGRAMA:  cadCidade.php
# CLASSES RELACIONADAS
#* -> IntCadCidade.php
#* -> NegCadCidade.php
#
# HISTÓRICO DE MANUTENÇÕES:
# Data:           Alteração:
# Data:           Alteração:
# Data:           Alteração:
#
# @see IntCadCidade.php
# @see NegCadCidade.php
#
######################################################################*/

class IntCadCidade extends NegCadCidade
{
	/**
	 * Propriedade Padrão
	 * Armazena as ações da interface
	 * @var string
	 */
	
	var $strAcao;
	
	/**
	 * IntCadCidade
	 * Construtor da Classe
	 * Referencia para a construtora da classe à qual é extensão para conexão com o banco de dados.
	 * Em seguida limpa suas propriedades para então defini-las.
	 * @param connection $DB
	 * @param array $requests
	 * @return void
	 * @access public
	 */
	
	function IntCadCidade(&$db, $requested)
	{
		$this->NegCadCidade($db,$requested);
		$this->limpaPropriedade();
		$this->setaPropriedade();
	}
	
	/**
	 * limpaPropriedade
	 * Limpa as propriedades da classe
	 * @access public
	 * @return void
	 */
	
	function limpaPropriedade()
	{
		parent::limpaPropriedade();
		$this->strAcao = "";
	}
	
	/**
	 * setaPropriedade
	 * Define as propriedades da classe
	 * @access public
	 * @return void
	 */
	
	function setaPropriedade()
	{
		parent::setaPropriedade();
		if(isset($this->arrRequest['strAcao'])){$this->strAcao = $this->arrRequest['strAcao'];}
		if(isset($this->arrRequest['strBotao'])){$this->strBotao = $this->arrRequest['strBotao'];}

	}
	
	/**
	 * controle
	 * Controla as ações da interface
	 * @access public
	 * @return void
	 */
	
	function controle()
	{
		if( $this->strAcao == "Novo" ){
			$this->limpaPropriedade();
		}elseif( $this->strAcao == "Gravar" ){
			if( $this->strBotao == 'incluir' ){
				if( $this->inserirCidade() ){
					$this->objQry->commit();
					$this->limpaPropriedade();
					array_push( $this->arrAviso, "Dados cadastrados com sucesso!" );
				}else{
					$this->objQry->rollback();
				}
			}elseif( $this->strBotao == 'alterar' ){
				if( $this->atualizarCidade() ){
					$this->objQry->commit();
					array_push( $this->arrAviso, "Dados alterados com sucesso!" );
				}else{
					$this->objQry->rollback();
				}
				$this->strAcao = "Alterar";
				
			}
		}elseif( $this->strAcao == "Alterar" ){
			$this->carregarDadosCidade();
		}elseif( $this->strAcao == "Excluir" ){
			$this->carregarDadosCidade();
		}elseif( $this->strAcao == "Sim" ){
			if( $this->excluirCidade() ){
				$this->objQry->commit();
				$this->limpaPropriedade();
				array_push( $this->arrAviso, "Dados excluídos com sucesso!" );
			}else{
				$arrErro = $this->arrErro;
				$arrAviso = $this->arrAviso;
				
				$this->limpaPropriedade();
				$this->objQry->rollback();
				
				$this->arrErro = $arrErro;
				$this->arrAviso = $arrAviso;
			}
		}elseif( $this->strAcao == "Não" ){
			$this->limpaPropriedade();
		}
		
		$this->formCadCidade();
		$this->relatorioCidades();
	}
	
	/**
	 * formCadCidade
	 * Cria o formulário de cadastro de cidade
	 * @access public
	 * @return void
	 */
	
	function formCadCidade()
	{
		GLOBAL $PHP_SELF, $SISCONF;
		$lock = false;
        
		$arrVoltarMenu['titulo'] = "Voltar para o Menu";
        $arrVoltarMenu['link'] = "window.location.href='".$SISCONF['SIS']['INTRANET']["HOST"]."abertura.php?menu[]=1'";
        echo showBotoes($arrVoltarMenu);
        
        $strTitulo = "Cadastro de cidade";
        if( $this->strAcao == "Alterar" ){
			$strTitulo = "Alteração de cidade";
        }elseif( $this->strAcao == "Excluir" ){        	
        	$strTitulo = "Deseja realmente excluir esta cidade?";
        	$lock = true;
        }
        
        $form = new GerenciadorAbas($strTitulo,  $PHP_SELF, 'formCadCidade');
		$form->setaDimensoes(450, 1 );
		$form->setaExibicaoBarraBotoes(true);
        $form->inicializaBotoes(false);
        $form->setaLegenda(true);
        $form->adicionaAba('primAba','');
        
	 	if( !( empty($this->arrErro) ) ) {
            $form->adicionaMensagens($this->arrErro, 'erro');
            $form->setaExibicaoBotao('aviso', true);
        }

        if( !( empty($this->arrAviso) ) ) {
            $form->adicionaMensagens($this->arrAviso, 'aviso');
            $form->setaExibicaoBotao('aviso', true);
        }   
        
        $this->strBotao = 'incluir';
        
        //se for uma alteração a solicitação do usuário, setar o botão para alteração
        if( $this->strAcao == 'Alterar' ){
        	$this->strBotao = 'alterar';
        }
        
        $coluna = 1;
        $linha = 1;
        
        $html = new Text("Nome da cidade:", true);
        $form->posicionaElemento('primAba', $linha, $coluna, $html);
        
        $coluna++;
        $html = new TextField("strCidadeNome", "strCidadeNome", $this->strCidadeNome, 40, '', 100, $lock);
        $form->posicionaElemento('primAba', $linha, $coluna, $html, "packageForm", "", 0, 2);
        
        $linha++;
        $coluna = 1;
        $html = new Text("Estado:", true);
        $form->posicionaElemento('primAba', $linha, $coluna, $html);
        
        $arrayEstados = $this->retornaTodosEstados();

        $coluna++;
        $html = new ComboBox("intEstadoId", "intEstadoId", "x".$this->intEstadoId, $arrayEstados, 'Selecione', $lock);
        $form->posicionaElemento('primAba', $linha, $coluna, $html, "packageForm", "", 0, 2);
        
		if( $this->strAcao == "Excluir" || $this->strAcao == "Alterar" ){
	     	$linha++;
	        $coluna = 1;
	        $html = new TextHidden("intCidadeId", "intCidadeId", $this->intCidadeId);
	        $form->posicionaElemento('primAba', $linha, $coluna, $html);
        }
        
        $linha++;
        $coluna = 1;
        $html = new TextHidden("strBotao", "strBotao", $this->strBotao);
        $form->posicionaElemento('primAba', $linha, $coluna, $html);   
        
        if( $this->strAcao != "Excluir" ){
			$form->adicionaBotao("Gravar", "", "strAcao", 'submit');
        }else{
        	$form->adicionaBotao("Sim", "", "strAcao", 'submit');
        	$form->adicionaBotao("Não", "", "strAcao", 'submit');
        }
        
		$form->adicionaBotao("Novo", "", "strAcao", 'submit');
        
		$form->exibe();
	}
	
	/**
	 * relatorioCidades
	 * Cria o relatório com as cidades já cadastradas
	 * @access public
	 * @return void
	 */
	
	function relatorioCidades()
	{
		GLOBAL $PHP_SELF;
		
		echo "<br /><br />";
		
		$conMatriz = new confDB();
        $conMatriz->setBancoByAlias('MATRIX');
        $conMatriz->conecta();
        $report = new trelatorio($conMatriz);
        $report->col_tamanho = "340";
		$report->titulo = "Lista de cidades";
        $report->sql = $this->retornaDadosRelatorio();
        $report->col_titulo = array( "Ação", "Código", "Cidade");
        $report->col_nome_sql = array( "ACAO", "COD", "CIDADE");
        $report->col_alin = array( "CENTER", "CENTER", "LEFT");
        $report->col_negrito = array();
        $report->col_indiv_tamanho = array(40, 50, 250);
        $report->pagina_link = $PHP_SELF;
        $report->pagina_link_params_nome = array("");
        $report->pagina_link_params_coluna = array("");
        $report->controle_nivel = 0;
        $report->gera();
	}
}
?>