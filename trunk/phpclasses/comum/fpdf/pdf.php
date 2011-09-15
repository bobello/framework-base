<?php
require_once "comum/fpdf/fpdf.php";
require_once "comum/util/package_utils.class.php";
require_once "comum/sessao/configsis.inc.php";

/**
* Pdf
*
* Classe que extende a classe de FDPF para poder criar os métodos do UNILASALLE, com base nos métodos da classe
* existente.
*
* @author Henrique Girardi dos Santos
* @version 10/09/2007
*/

class Pdf extends FPDF
{
    /**
    * Recebe o valor booleano para gerar ou não o cabeçalho;
    * @var boolean
    */
    var $geraCabecalho;

    /**
    * Recebe o valor booleano para gerar ou não o cabeçalho;
    * @var boolean
    */
    var $geraRodape;

    /**
    * Recebe o caminho default de onde será salvo o arquivo
    * @var string
    */
    var $defaultPath;

    /**
    * Recebe o caminho para abrir o arquivo
    * @var string
    */
    var $pathHost;

    /**
    * Recebe o valor do usuario que realizou a impressao do PDF
    * @var string
    */
    var $usuario;

    /**
    * Recebe o nome do arquivo PDF
    * @var string
    */
    var $arquivo;

    /**
    * Recebe a extenção do arquivo a ser gerado
    * @var string
    */
    var $extensao;

    /**
    * Recebe o valor da origem do relatorio.
    * @var string
    */
    var $origemRelatorio;

    /**
    * Recebe o valor da largura do quadro do cabeçalho
    * @var array
    */
    var $larguraQuadroCabecalho;

    /**
    * Recebe o valor da coluna que irá inicial o início do cabeçalho
    * @var array
    */
    var $colunaInicioCabecalho;

    /**
    * Recebe o valor do tipo de cabecalho que será impresso
    * @var string
    */
    var $tipoCabecalho;


    /**
    * Pdf
    *
    * Método construtor da classe. Recebe os mesmos parametros da classe FPDF que possa chamar o construtor dela.
    * @param string $orientation - como deve ser a orientação da pagina PDF (P: Portrait | L: Landscape)
    * @param string $unit - unidade de medida do PDF - PADRÃO USADO NO UNILASALLE: 'pt'
    * @param string $format - formato da pagina para criação do PDF
    * @return void
    */
    function Pdf($orientation='P',$unit='pt',$format='A4')
    {
        $this->FPDF($orientation, $unit, $format);
        $this->_limpaPropriedades();
        $this->_setaPropriedades();
        $this->stringFormat = new StringFormat;
    }

    /**
    * _setaPropriedades
    *
    * Método que seta as propriedades da classe
    * @return void
    */
    function _setaPropriedades()
    {
        GLOBAL $SISCONF;
        $this->defaultPath = $SISCONF['SIS']['RELATORIO_PDF']['PATH'];
        $this->pathHost = $SISCONF['SIS']['RELATORIO_PDF']['HOST'];
        $this->usuario = $SISCONF['SESSAO']['USUARIO']['USUARIO'];
    }

    /**
    * _limpaPropriedades
    *
    * Método que inicializa as propriedades da classe
    * @return void
    */
    function _limpaPropriedades()
    {
        $this->geraCabecalho = false;
        $this->geraRodape = false;
        $this->arquivo = "";
        $this->usuario = "";
        $this->extensao = "pdf";
        $this->origemRelatorio = "";
        $this->erro = array();
        $this->larguraQuadroCabecalho = 0;
        $this->colunaInicioCabecalho = 0;
        $this->tipoCabecalho = "";
    }

    /**
    * setaOrigemRelatorio
    *
    * Método que seta valor para a propriedade 'origemRelatorio'.
    * @param string $origem - recebe o valor da origem do relatório. Por default esse valor vem vazio, indicando que o pdf está sendo gerado pelo sistema interno.
    * @return void
    */
    function setaOrigemRelatorio($origem)
    {
        $this->origemRelatorio = $origem;
    }

    /**
    * setaLarguraQuadroCabecalho
    *
    * Método que seta valor para propriedade 'larguraQuadroCabecalho'.
    * @param int $valor - valor que a propriedade 'larguraQuadroCabecalho' irá receber
    * @return void
    */
    function setaLarguraQuadroCabecalho($valor)
    {
        $this->larguraQuadroCabecalho = $valor;
    }

    /**
    * setaColunaInicioCabecalho
    *
    * Método que seta valor para propriedade 'colunaInicioCabecalho'.
    * @param int $valor - valor que a propriedade 'colunaInicioCabecalho' irá receber
    * @return void
    */
    function setaColunaInicioCabecalho($valor)
    {
        $this->colunaInicioCabecalho = $valor;
    }

    /**
    * setaTipoCabecalho
    *
    * Método que seta valor para propriedade 'tipoCabecalho'.
    * @param int $valor - valor que a propriedade 'colunaInicioCabecalho' irá receber
    * @return void
    */
    function setaTipoCabecalho($tipo)
    {
        $this->tipoCabecalho = $tipo;
    }

    /**
    * setaCabecalho
    *
    * Método que inicializa as propriedades da classe
    * @param array $dadosCabecalho - se o tipoCabecalho == 'ATESTADO', as chaves serão: 'tipo', 'coluna' e 'linha',
    *  senão será 'pagina', titulo1, titulo1_size, titulo2, titulo2_size, titulo3, titulo3_size, tipo, linha
    * @return void
    */
    function setaCabecalho($dadosCabecalho)
    {
            if (!isset($dadosCabecalho["pagina"])) $this->Error("A chave 'pagina' do parâmetro do método <b>setaCabecalho</b> deve ser passada");
            if (!isset($dadosCabecalho["titulo1"])) $this->Error("A chave 'titulo1' do parâmetro do método <b>setaCabecalho</b> deve ser passada");
            if (!isset($dadosCabecalho["titulo1_size"])) $this->Error("A chave 'titulo1_size' do parâmetro do método <b>setaCabecalho</b> deve ser passada");
            if (!isset($dadosCabecalho["titulo2"])) $dadosCabecalho["titulo2"] = "";
            if (!isset($dadosCabecalho["titulo2_size"])) $dadosCabecalho["titulo2_size"] = 0;
            if (!isset($dadosCabecalho["titulo3"])) $dadosCabecalho["titulo3"] = "";
            if (!isset($dadosCabecalho["titulo3_size"])) $dadosCabecalho["titulo3_size"] = 0;
            if (!isset($dadosCabecalho["tipo"])) $dadosCabecalho["tipo"] = "";
            if (!isset($dadosCabecalho["linha"])) $dadosCabecalho["linha"] = 30;

        $this->geraCabecalho = true;
        $this->dadosCabecalho = $dadosCabecalho;
    }
	
    /**
    * Header
    *
    * Método que mostra o cabeçalho no PDF. Ele é montado sobre o método da classe FPDF.
    * @return void
    */
    function Header()
    {
        if ($this->geraCabecalho == true) {
        	$this->cabecalhoPadrao($this->dadosCabecalho);
        }
    }

    /**
    * cabecalhoPadrao
    *
    * Método que monta o cabeçalho padrão dos PDFs.
    * @param array $dadosCabecalho - recebe um array com os dados necessários para montar o cabeçalho. Esse array
    *  é a propriedade da classe 'dadosCabecalho'.
    * @return void
    */
    function cabecalhoPadrao($dadosCabecalho) 
    {
		GLOBAL $SISCONF;

		$local = $SISCONF['SIS']['PATH_IMAGENS']."/";

        $coluna = 15;
        if ( (int) $this->colunaInicioCabecalho > 0 ) $coluna = $this->colunaInicioCabecalho;

        $this->SetLineWidth(0.1);
        $linha = $dadosCabecalho["linha"];

        if ($dadosCabecalho["tipo"] == "landscape") {
            $tamQuadro = 825;
        } else {
            $tamQuadro = 565;
        }

        if ( $this->larguraQuadroCabecalho > 0 ) $tamQuadro = $this->larguraQuadroCabecalho;

        $this->Line($coluna, $linha+60, $tamQuadro, $linha+60);
        $this->Line($coluna, $linha+45, $tamQuadro, $linha+45);
        $this->Line($coluna, $linha+5, $tamQuadro, $linha+5);
        $this->Line($coluna, $linha+60, $coluna, $linha+5);
        //$this->Line($coluna+225, $linha+60, $coluna+225, $linha+5);
        $this->Line($tamQuadro, $linha+60, $tamQuadro, $linha+5);

        if ($dadosCabecalho["titulo1_size"] == 0) $dadosCabecalho["titulo1_size"] = 11;
        if ($dadosCabecalho["titulo2_size"] == 0) $dadosCabecalho["titulo2_size"] = 9;
        if ($dadosCabecalho["titulo3_size"] == 0) $dadosCabecalho["titulo3_size"] = 9;

        $this->SetFont('Times', 'B', $dadosCabecalho["titulo1_size"]);
        $this->Text($coluna+5, $linha+17, $dadosCabecalho["titulo1"]);

        $this->SetFontSize($dadosCabecalho["titulo2_size"]);
        $this->Text($coluna+5, $linha+30, $dadosCabecalho["titulo2"]);

        $this->SetFontSize($dadosCabecalho["titulo3_size"]);
        $this->Text($coluna+5, $linha+40, $dadosCabecalho["titulo3"]);

        $this->SetFont('Times', '', 9);

        $this->Text($coluna+5, $linha+55, "SGC");
        $this->Text($tamQuadro-45, $linha+55, "Pág: ".$this->stringFormat->left($dadosCabecalho["pagina"], 4));

        $this->SetXY($coluna+5, $linha+48);
        $this->Cell(0, 9, date('d/m/Y H:i'), 0, 1, "C");
    }

    /**
    * criaNomeArquivo
    *
    * Método que cria o nome do arquivo PDF que será gerado
    * @return void
    */
    function criaNomeArquivo()
    {
        GLOBAL $SISCONF;
        $this->arquivo = date("YmdHis").$this->usuario.".".$this->extensao;
    }

    /**
    * geraArquivoPDF
    *
    * Método adiciona mensagem ao array de erro.
    * @param boolean $retornaCaminho - parametro que informa se é para retornar o caminho onde é gerado
    *   o arquivo. Por default é FALSE.
    * @return void/string
    */
    function geraArquivoPDF($retornaCaminho=false)
    {
        $this->criaNomeArquivo();
        $this->Output($this->defaultPath.$this->arquivo,'F');
        $this->close();

        if ($retornaCaminho == false) {
            echo "<script language='javascript'>
                    window.open('".$this->pathHost.$this->arquivo."','','');
                </script>";
        } else {
            return $this->pathHost.$this->arquivo;
        }
    }
    
	/**
	 * imprime o cabeçãalho com os dados do edital
	 *
	 * @param float $arrParametros['LINHA']
	 * @param float $arrParametros['ALTURA']
	 * @param float $arrParametros['COLUNA']
	 * @param float $arrParametros['LARGURA']
	 * @param integer $arrParametros['BORDA']
	 * @param object $arrParametros['OBJ_EDITAL']
	 */
	function imprimeCabecalhoEdital( $arrParametros )
	{
		$linha = $arrParametros["LINHA"];
		$altura = $arrParametros["ALTURA"];
		//$coluna = $arrParametros["COLUNA"];
		$coluna = 0;
		$borda = $arrParametros["BORDA"];
		//$larguraPagina = $arrParametros["LARGURA"];
		$larguraPagina = 0;
		$objEdital = $arrParametros["OBJ_EDITAL"];
		
		$objEdital->carregarDadosEdital();
		
		$nomeInstituicao = $objEdital->strInstNome; 
		$cnpj = $objEdital->strInstCnpj;
		$editalNumero = $objEdital->strEditalNumero;
		$editalNome = $objEdital->strEditalNome;
		
		$linha += $altura * 5;
		$fltPosicao = ( $altura * 2 );
		//$this->Rect( $coluna, $linha, 500, $fltPosicao );
		$this->Rect( $coluna, $linha, 0, $fltPosicao );
		
		$linha += 7;
        $rColuna = $coluna + 15;
        $this->SetXY( $rColuna, $linha );
        $this->SetFont("times", "b", 8);


        $posicao = $altura + 3;
        //$rColuna = $coluna + 15;
		$rColuna = $coluna + 45;
        $texto = "Edital: ".$editalNumero." - ".$nomeInstituicao;
        $this->SetXY( $rColuna, $linha );
        $this->MultiCell($larguraPagina, $posicao, $texto, $borda, "L");
	}
     

	/**
	 * imprime o cabeçalho com os dados do cargo
	 *
	 * @param float $arrParametros['LINHA']
	 * @param float $arrParametros['ALTURA']
	 * @param float $arrParametros['COLUNA']
	 * @param float $arrParametros['LARGURA']
	 * @param integer $arrParametros['BORDA']
	 * @param object $arrParametros['OBJ_CARGO']
	 * @param float $arrParametros['LARGURA_RETANGULO']
	 */
	function imprimeCabecalhoCargo( $arrParametros )
	{
		$linha = $arrParametros["LINHA"];
		$altura = $arrParametros["ALTURA"];
		//$coluna = $arrParametros["COLUNA"];
		$coluna = 0;
		$borda = $arrParametros["BORDA"];
		$larguraPagina = ( isset( $arrParametros["LARGURA"] ) ? $arrParametros["LARGURA"] : "500" );
		$objCargo = $arrParametros["OBJ_CARGO"];
		//$larguraRet = ( isset( $arrParametros['LARGURA_RETANGULO'] ) ? $arrParametros['LARGURA_RETANGULO'] : "500" );
		$larguraRet = 0;
		
		$objCargo->setPropriedadeCargo();
		$nomeCargo = $objCargo->strCargoDescr;
		$codCargo = $objCargo->intIdCargo;
		$nivel = $objCargo->retornaNomeNivelCargo();
		
		$linha += $altura * 3;
		$fltPosicao = ( $altura * 2 ) + 6;
		$this->Rect( $coluna, $linha, $larguraRet, $fltPosicao );
		
		//$linha += 7;
		$linha += 3;
        //$rColuna = $coluna + 15;
		$rColuna = $coluna + 45;
        $this->SetXY( $rColuna, $linha );
        $this->SetFont("times", "b", 8);
        
        $posicao = $altura;
        $texto = "Cargo: ".$codCargo." - ".$nomeCargo." - ".$nivel;
        $this->MultiCell($larguraPagina, $posicao, $texto, $borda, "L");
	}
	
	/**
	 * imprime o cabeçalho com os dados do local de prova
	 *
	 * @param float $arrParametros['LINHA']
	 * @param float $arrParametros['ALTURA']
	 * @param float $arrParametros['COLUNA']
	 * @param float $arrParametros['LARGURA']
	 * @param integer $arrParametros['BORDA']
	 * @param object $arrParametros['OBJ_LOCAL']
	 * @param float $arrParametros['LARGURA_RETANGULO']
	 */
	function imprimeCabecalhoLocalProva( $arrParametros )
	{
		$linha = $arrParametros["LINHA"];
		$altura = $arrParametros["ALTURA"];
		//$coluna = $arrParametros["COLUNA"];
		$coluna = 0;
		$borda = $arrParametros["BORDA"];
		$larguraPagina = $arrParametros["LARGURA"];
		$objLocal = $arrParametros["OBJ_LOCAL"];
		//$larguraRet = ( isset( $arrParametros['LARGURA_RETANGULO'] ) ? $arrParametros['LARGURA_RETANGULO'] : "400" );
		$larguraRet = 0;

		$objLocal->carregarDadosLocalProva();
		$nomeLocal = $objLocal->strNomeLocal;
		$enderecoLocal = $objLocal->strEnderecoLocal;
		$localCidade = $objLocal->strCidadeNome;
		
		$linha += $altura * 6;
		$fltPosicao = ( $altura * 2 );
		$this->Rect( $coluna, $linha, $larguraRet, $fltPosicao );
		
		//$linha += 7;
		$linha -= 50;
        //$rColuna = $coluna + 5;
		$rColuna = $coluna + 45;
        $this->SetXY( $rColuna, $linha );
        $this->SetFont("times", "b", 8);
        
        $posicao = $altura + 3;
        $texto = "Local: ".$nomeLocal." - ".$enderecoLocal." - ".$localCidade;
        $this->MultiCell($larguraPagina, $posicao, $texto, $borda, "L");
	}
	
	/**
	 * imprime o cabeçalho com os dados da sala
	 *
	 * @param float $arrParametros['LINHA']
	 * @param float $arrParametros['ALTURA']
	 * @param float $arrParametros['COLUNA']
	 * @param float $arrParametros['LARGURA']
	 * @param integer $arrParametros['BORDA']
	 * @param object $arrParametros['OBJ_SALA']
	 * @param float $arrParametros['LARGURA_RETANGULO']
	 * @param integer $arrParametros['SALA']
	 */
	function imprimeCabecalhoSala( $arrParametros )
	{
		$linha = $arrParametros["LINHA"];
		$altura = $arrParametros["ALTURA"];
		//$coluna = $arrParametros["COLUNA"];
		$coluna = 0;
		$borda = $arrParametros["BORDA"];
		$larguraPagina = $arrParametros["LARGURA"];
		$objSala = $arrParametros["OBJ_SALA"];
		//$larguraRet = ( isset( $arrParametros['LARGURA_RETANGULO'] ) ? $arrParametros['LARGURA_RETANGULO'] : "400" );
		$larguraRet = 0;

		$nome = $objSala->retornaNomeSala( $arrParametros['SALA'] );
		
		$linha += $altura * 6;
		$fltPosicao = ( $altura * 2 );
		$this->Rect( $coluna, $linha, $larguraRet, $fltPosicao );
		
		//$linha += 7;
		$linha -= 30;
        //$rColuna = $coluna + 15;
		$rColuna = $coluna + 13;
        $this->SetXY( $rColuna, $linha );
        $this->SetFont("times", "b", 35);
        
        $posicao = $altura + 3;
        $texto = $nome;
        $this->MultiCell($larguraPagina, $posicao, $texto, $borda, "L");
	}
}
?>