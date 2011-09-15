<?PHP
/**
 * Classe trelatoriojanelaDeRequerimento
 * Gera uma tabela com várias possibilidades
 * de formato.
 * @author Rudinei Dias
 * @date
 *
 * MODIFICADA EM 30/04/2003
 * REF: ADAPTACÃO COM OS METODOS DO package_db
 */
//require_once "negocio/financeiro/negImpressaoDOC.class.php";
require_once "comum/banco/package_bd.class.php";

class trelatorio {
    //NOVAS Propriedades públicas
    /*
    var $descriptor = "function trelatorio(&\$DB) <BR>
        function setFont(\$type = 'DETAIL', \$face = 'Tahoma, Arial', \$size = 10, \$color ='#000000') <BR>";
        */
    var $conn;
    var $qry;

    //Propriedades públicas
    var $titulo; //Título do Relatório
    var $mostrar_borda;
    var $mostrar_grade;
    var $banco;
    var $usuario;
    var $senha;
    var $sql;
    var $col_estilo;
    var $col_titulo;
    var $col_negrito;
    var $col_nome_sql;
    var $col_indiv_tamanho;
    var $col_alin;
    var $col_tamanho;
    var $controle_nivel;
    var $numerador_esq;
    var $numerador_dir;
    var $table_alin;
    var $pagina_link;
    var $pagina_link_param_ref;
    var $pagina_link_params_nome;
    var $pagina_link_params_coluna;
    var $fixar_numero;
    var $cor_titulo;
    var $cor_titulo_fonte;
    var $cor_tabela_borda;
    var $cor_linha1;
    var $cor_linha2;
    var $cor_linha_destaque;
    var $cor_background;
    var $pagina_link_abre_nova_janela;
    var $pagina_link_nova_janela_pos_x;
    var $pagina_link_nova_janela_pos_y;
    var $pagina_link_nova_janela_width;
    var $pagina_link_nova_janela_height;
    var $pagina_link_nova_janela_resizeable;
    var $pagina_link_nova_janela_scrollbars;
    var $pagina_link_nova_janela_barra_menus;
    var $bb_calc_mod11;
    var $bb_calc_mod11_col_name;
    var $bb_calc_mod11_col_calc;
    var $mensagem_retorno;
    var $margem_esquerda;
    var $padraoNumerico;
    var $totalizadorUltimaLinha;
    var $mostrarTotalizador;
    var $instituicao;
    var $usarTQuery = false;
    var $globalTarget = array();
    var $retornaRelatorio;      // atributo para especificar se a classe tRelatorio retorna a tabela (true) ou dá um echo (false)
                                //nos campos conforme vão surgindo
    var $colDestaque;
    //propriedades privadas
    var $width;
    var $num_par;
    var $lastLineInBold;
    var $spanTitleInColumn;
    var $columnHeaderFontAttributtes = array();
    var $columnDetailFontAttributtes = array();
    var $showFonteDados = true;
    var $intervaloCabecalho = "";
    var $substituirPorBR = true;
    var $_CODE;
    var $mostrarLegenda; //texto que será inserido na última linha antes do rodapé.
    var $timer;
    var $timer2;
    var $caminhoImagens;

	/**
	 * Construtor da classe
	 *
	 * @param object $DB
	 * @return void
	 * @access public
	 */
    function trelatorio(&$DB)
    {
        //Propriedades públicas
        $this->conn = $DB;
        $this->qry = new genericQuery($this->conn);
        $this->titulo = "Sem Titulo";
        $this->mostrar_borda = false;
        $this->mostrar_grade = false;
        $this->banco = "";
        $this->usuario = "";
        $this->senha = "";
        $this->sql = "";
        $this->col_estilo = "";
        $this->col_titulo = "";
        $this->col_negrito = "";
        $this->col_nome_sql = "";
        $this->col_indiv_tamanho = 0;
        $this->col_alin="";
        $this->col_tamanho = "";
        $this->controle_nivel=0;
        $this->numerador_esq=false;
        $this->numerador_dir=false;
        $this->pagina_link="";
        $this->pagina_link_param_ref="";
        $this->pagina_link_params_nome="";
        $this->pagina_link_params_coluna="";
        $this->table_alin="CENTER";
        $this->fixar_numero=0;
        $this->caminhoImagens = "";

        GLOBAL $SGU_INTERNET;

        $this->cor_titulo="4D658A";
        $this->cor_titulo_fonte="FFFFFF";
        $this->cor_tabela_borda="003399";
        $this->cor_nome_col="F0F4F9";
        $this->cor_linha1="EFEFEF";
        $this->cor_linha2="FFFFFF";
        $this->cor_linha_destaque="e8cc94";//E4E4FF
        $this->cor_background="F0F4F9"; //"CECDBD"; //"D9D1C6";

        $this->instituicao = "";

        GLOBAL $SISCONF;
        $caminhoImagens = $SISCONF['SIS']['COMUM']['IMAGENS'];
        $this->caminhoImagens = $caminhoImagens;


        $this->pagina_link_abre_nova_janela=false;
        $this->pagina_link_nova_janela_pos_x=5;
        $this->pagina_link_nova_janela_pos_y=5;
        $this->pagina_link_nova_janela_width=600;
        $this->pagina_link_nova_janela_height=300;
        $this->pagina_link_nova_janela_resizeable="no";
        $this->pagina_link_nova_janela_scrollbars="yes";
        $this->pagina_link_nova_janela_barra_menus="no";
        $this->bb_calc_mod11 = false;
        $this->bb_calc_mod11_col_name= "";
        $this->bb_calc_mod11_col_calc= 0;
        $this->mensagem_retorno="";
        $this->margem_esquerda = false;
        $this->lastLineInBold = false;
        $this->spanTitleInColumn = array();
        $this->substituirPorBR = true;
        //propriedades privadas
        $this->width="";
        $this->num_par=0;

        $this->padraoNumerico=1;    //Padrão 1: 999.999.999,00 (BRASILEIRO)
                                    //Padrão 1: 999,999,999.00 (AMERICANO)
        $this->mostrarTotalizador = false;
        $this->totalizadorUltimaLinha = false;

        $this->retornaRelatorio = false;
        $this->_CODE = "";
        $this->mostrarLegenda = "";

        $this->timer = new timer();
        $this->timer2 = new timer();
    }
	
    /**
     * Seta a legenda do relatório
     *
     * @param string $texto
     * @return void
     * @access public
     */
    function mostrarLegenda($texto="")
    {
        $this->mostrarLegenda = trim($texto);
    }
	
    /**
     * Se $this->usarTQuery = true, obriga o processamento a
     *  usar o método TQuery para processar consultas
     *
     * @param bool $use
     * @return void
     * @acccess public
     */
    function setTransactionUse($use = true)
    {
        $this->usarTQuery = $use;
    }
	
    function setTarget($column = 0, $target = "null")
    {
        $target = trim($target);
        $column = (int) $column;
        if ((is_numeric($column)) && (trim($target)!="null")){
            $this->globalTarget[$column] = " target= \"$target\" ";
        }
    }

    function setFonteDados($apresentar=true)
    {
        $this->showFonteDados = $apresentar;
    }

    function setNomeColunaDestaque( $colDestaque )
    {
        $this->colDestaque=$colDestaque;
    }

    function setFont($type = "DETAIL", $face = "Tahoma, Arial", $size = 10, $color = "#000000")
    {
        $type = strtoupper($type);
        if ($type == "HEADER") {
            $this->columnHeaderFontAttributtes['OPEN']  = "<font style='".
                                    "font-family: ".$face."; ".
                                    "font-size: ".$size."pt; ".
                                    "color: ".$color." '>";
            $this->columnHeaderFontAttributtes['CLOSE'] = "</font>";
        }
        if ($type == "DETAIL") {
            $this->columnDetailFontAttributtes['OPEN']  = "<font style='".
                                    "font-family: ".$face."; ".
                                    "font-size: ".$size."pt; ".
                                    "color: ".$color." '>";
            $this->columnDetailFontAttributtes['CLOSE'] = "</font>";
        }
    }

	/**
	 * Formata a ultima linha do relatorio para negrito.
	 *  bolVal: Define se a ultima linha estara em negrito ou nao.
	 *
	 * @param bool $bolValue
	 * @return void
	 * @access public
	 */
    function setUltimaLinhaNegrito($bolValue = true)
    {
        if (is_bool($bolValue)) {
            $this->lastLineInBold = $bolValue;
        }else{
            $this->lastLineInBold = false;
        }

    }
	/**
	 * Cria titulos acima dos titulos da coluna com span
	 * para mais de uma coluna.
	 * 
	 * @param int colInicial Coluna inicial do span considerando de 0 a n - 1;
	 * @param int colFinal Coluna final do span considerando colInicial + nro. de colunas do span;
	 * @param string Título texto a integrar esta celula.
	 * @param int tamanhoFonte Define o tamanho da fonte (de 1 a 7);
	 * @param bool bold Transforma o texto em negrito.
	 * @return void
	 * @access public
	 */
    function setTituloComSpanEmColunas($colInicial, $colFinal, $titulo, $tamanhoFonte = 1, $bold = true)
    {
        if (is_int($colInicial) &&
                is_int($colFinal) &&
                is_int($tamanhoFonte)) {
            $span = ($colFinal - $colInicial) + 1;
            if (($tamanhoFonte > 0) && ($tamanhoFonte < 8)) {
                $tit = "";
                $tit .= "<td #PARAMETER# colspan=$span>";
                if ($bold) $tit .= "<b>";

                if (count($this->columnHeaderFontAttributtes)>0) {
                    $tit .= $this->columnHeaderFontAttributtes['OPEN'].
                            $titulo.
                            $this->columnHeaderFontAttributtes['CLOSE'];
                }else{
                    $tit .= "<font size=$tamanhoFonte face=\"tahoma\">".$titulo."</font>";
                }
                if ($bold) $tit .= "</b>";
                $tit .= "</td>";
                $this->spanTitleInColumn[$colInicial]['COLSPAN'] = $span;
                $this->spanTitleInColumn[$colInicial]['TITULO'] = $tit;
            }else{
                echo "<p>Parâmetro TamanhoFonte de setTituloComSpanEmColunas inválido - (limite 1-7)";
            }
        }else{
            echo "<p>Parâmetro(s) de setTituloComSpanEmColunas inválido(s)";
        }
    }
	
    /**
     * Retorna o título do relatório
     *
     * @param bool $escreverColspan
     * @return string
     * @access public
     */
    function getTitulo($escreverColspan=false)
    {
       if ($this->titulo!=''){
           $colspan='';
           if ($escreverColspan = true){
               $colaMais=0;
               if ($this->numerador_esq==true){
                   $colaMais=$colaMais+1;
               }
               if ($this->numerador_dir==true){
                   $colaMais=$colaMais+1;
               }
               $colspan = ' colspan = '. (count($this->col_titulo)+$colaMais). ' ';

           }
            $retorno = "";

            GLOBAL $SISCONF;
            $codigoImprime = "";

            $codigoImprime = "<img class='naoVisivelImpressao' alt=\"Imprimir\" src=\"".$this->caminhoImagens."btImpressora.png\" onClick=print();>";

            $retorno .= "\n<tr><td align=CENTER bgcolor=#".$this->cor_titulo."
                style=\"{background-color: #".$this->cor_titulo."}\" ".$colspan.">";

            $retorno .= "\n<table border=0 width='100%'><tr><td align=LEFT bgcolor=#".$this->cor_titulo."
                style=\"{background-color: #".$this->cor_titulo."}\" ".$colspan.">";
            $retorno .= "<strong><font size=2 face=\"tahoma\" class=\"titulo_classTable\" color=\"".$this->cor_titulo_fonte."\"".
                " style=\"{cursor: pointer; color: #".$this->cor_titulo_fonte."}\">".$codigoImprime."</font></strong>";
            $retorno .=  "</td></tr>";

            //Mostra o Título do relatório
            $retorno .= "\n<tr><td align=center bgcolor=#".$this->cor_titulo."
                style=\"{background-color: #".$this->cor_titulo."}\" ".$colspan.">";
            $retorno .= "<strong><font size=2 face=\"tahoma\" class=\"titulo_classTable\" color=\"".$this->cor_titulo_fonte."\"".
                " style=\"{color: #".$this->cor_titulo_fonte."}\">".$this->titulo."</font></strong>";
            $retorno .=  "</td></tr>";

            $retorno .= "\n</table>";

            $retorno .=  "</td></tr>";
       }
       return $retorno;
    }
	
    /**
     * Retorna o sub-cabeçalho do relatório
     *
     * @param int $count
     * @return string
     * @access public
     */
    function getSubCabecalho($count=0)
    {
        //Cria os Headers das colunas
        $retorno = "\n<tr>";
        if ($this->numerador_esq){
            $retorno .= "\n<td align=center valign='bottom' cellpadding=1 bgcolor=#".$this->cor_nome_col." ".
                " style=\"{background-color: #".$this->cor_nome_col.";border-style: solid; border-width: 1px; }\">";
            if (count($this->columnHeaderFontAttributtes)>0) {
                $retorno .= "<strong>".$this->columnHeaderFontAttributtes['OPEN'].
                        "Nº".
                        $this->columnHeaderFontAttributtes['CLOSE']."</strong>";
            }else{
                $retorno .= "<strong><font size=1 face=\"tahoma\" class=\"titCol_classTable\">Nº</font></strong>";
            }
            $retorno .= "</td>";
        }

        $dCols = 0;
        for($i=0;$i<$count;$i++){

            $retorno .= "\n<td align=center valign='bottom' cellpadding=1 bgcolor=#".$this->cor_nome_col." ".
                " style=\"{background-color: #".$this->cor_nome_col."; border-style: solid; border-width: 1px;}\">";

            if (count($this->columnHeaderFontAttributtes)>0) {
                $retorno .= "<strong>".$this->columnHeaderFontAttributtes['OPEN'].
                        $this->col_titulo[$i].
                        $this->columnHeaderFontAttributtes['CLOSE']."</strong>";
            }else{
                $retorno .= "<strong><font size=1 face=\"tahoma\" class=\"titCol_classTable\">".$this->col_titulo[$i]."</font></strong>";
            }

            $retorno .= "</td>";
            $dCols++;
        }
        if ($this->numerador_dir){
            $retorno .= "\n<td align=center valign='bottom' cellpadding=1 bgcolor=#".$this->cor_nome_col." ".
                " style=\"{background-color: #".$this->cor_nome_col."; border-style: solid; border-width: 1px;}\">";
            if (count($this->columnHeaderFontAttributtes)>0) {
                $retorno .= "<strong>".$this->columnHeaderFontAttributtes['OPEN'].
                        "Nº".
                        $this->columnHeaderFontAttributtes['CLOSE']."</strong>";
            }else{
                $retorno .= "<strong><font size=1 face=\"tahoma\" class=\"titCol_classTable\">Nº</font></strong>";
            }
            $retorno .= "</td>";
        }
        $retorno .= "</tr>";

        return $retorno;
    }

    function getSubTitulosColspan($count)
    {
        $retorno = '';
        if (count($this->spanTitleInColumn)>0) {

            $retorno = "\n<TR>";
            if ($this->numerador_esq){
                $retorno .= "\n<TD ALIGN=CENTER CELLPADDING=1 BGCOLOR=#".$this->cor_nome_col." ".
                    " style=\"{background-color: #".$this->cor_nome_col."}\">";
                $retorno .= "</TD>";
            }

            for ($i=0; $i < $count; $i++){

                $tmp = $this->spanTitleInColumn[$i];
                if (is_array($tmp)) {
                    $xCSpan = $tmp['TITULO'];

                    $xCSparams = "ALIGN=CENTER CELLPADDING=1 BGCOLOR=#".$this->cor_nome_col." ".
                            " style=\"{background-color: #".$this->cor_nome_col."; border-style: solid; border-width: 1px;}\"";
                    $xCSpan = str_replace("#PARAMETER#", $xCSparams, $xCSpan);
                    $retorno .= $xCSpan;
                    for ($control = 1; $control < $tmp['COLSPAN']; $control++) $i++;
                }else{
                    $retorno .= "\n<TD ALIGN=CENTER CELLPADDING=1 BGCOLOR=#".$this->cor_nome_col." ".
                            " style=\"{background-color: #".$this->cor_nome_col."; border-style: solid; border-width: 1px;}\">";
                    $retorno .= "</TD>";
                }

            }

            if ($this->numerador_dir){
                $retorno .= "\n<TD ALIGN=CENTER CELLPADDING=1 BGCOLOR=#".$this->cor_nome_col." ".
                    " style=\"{background-color: #".$this->cor_nome_col."}\">";
                $retorno .= "</TD>";
            }
            $retorno .= "</TR>";


        }
        return $retorno;
    }

    function setRetornaRelatorio( $op )
    {
        $this->retornaRelatorio = $op;
    }

    function gera()
    {
        $this->timer->start();

        $tabela = "";

        //Testando se existem dados a selecionar
        //****************************************************************
        $vazio="";
        $segue=true;

        if ($this->pagina_link_abre_nova_janela==true){
            $tabela = "\n<script language \"JavaScript\">\n
                function doOpenNewWindow(str) {
                x = window.open(str,\"\",\"resizable=".$this->pagina_link_nova_janela_resizeable.",width=".$this->pagina_link_nova_janela_width.",height=".$this->pagina_link_nova_janela_height.",screenX=1, screenY=1,status=yes,menubar=".$this->pagina_link_nova_janela_barra_menus.",scrollbars=".$this->pagina_link_nova_janela_scrollbars."\");
                x.moveTo(".$this->pagina_link_nova_janela_pos_x.",".$this->pagina_link_nova_janela_pos_y.");
                x.focus();
                }\n
                </script>\n";
        }

        if ($this->pagina_link!=""){
            $m_col=0;
            while(list($key, $val) = each($this->pagina_link_params_nome)){$m_col++;}

            $this->num_par=$m_col;
            $m_col=0;
            while(list($key, $val) = each($this->pagina_link_params_coluna)){
                $m_col++;
            }

            if ($this->num_par!=$m_col){
                $vazio = "<br>Parâmetros de link diferem entre si.";
                $segue=false;
            }

            $m_col=0;

        }

        if ($this->col_tamanho==""){$this->width="WIDTH=100%";
        }else{$this->width="WIDTH=".$this->col_tamanho;}

        $colHeader = "";
        if ($this->controle_nivel>3){
            $tabela .= "<P ALIGN=CENTER><FONT SIZE=2>Operação não permitida:<BR> - Nível de controle excedido > 3</FONT></P>";
        }else{

            $count = 0;
            while(list($key, $val) = each($this->col_titulo)) ++$count;

            if ($this->intervaloCabecalho == "") {
                if ($this->mostrar_borda){
                    $tabela .= "<TABLE CLASS='REPORT_MAIN_BORDER' BORDER=0 CELLSPACING=0 CELLPADDING=3 ALIGN=".$this->table_alin."
                        ".$this->width."  style=\"{background-color: #".$this->cor_tabela_borda.";border-style: solid; border-width: 1px;}\"".
                        " BGCOLOR=#".$this->cor_tabela_borda.">";
                }else{
                    $tabela .= "\n<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=3 ALIGN=".$this->table_alin."
                        ".$this->width." style=\"{background-color: #".$this->cor_titulo."}\">";
                }

                $titulo = $this->getTitulo();

                $tabela .= "\n<TR><TD style=\"{background-color: #".$this->cor_background."}\">";

                $vStyleBack = "";

                if ($this->cor_background!=""){

                    $vStyleBack="style=\"{background-color: #".$this->cor_background."}\"";

                }

                if ($this->mostrar_grade){
                    $tabela .= "\n<TABLE BORDER=1 CELLPADDING=1 ".$this->width." $vStyleBack style='border-style: solid; border-width: 1px;'>";
                }else{
                    $tabela .= "\n<TABLE BORDER=0 CELLPADDING=1 ".$this->width." $vStyleBack>";
                }

                $tabela .= $titulo;
                $dCols = 0;

                $colHeader .= $this->getSubCabecalho($count);

                //Cria os spans de Colunas
                $colHeadsSpanned = "";
                $colHeadsSpanned = $this->getSubTitulosColspan($count);
                $tabela .= $colHeadsSpanned;
                $tabela .= $colHeader;
            }

            if (is_array($this->sql)== true) {
                if ((count($this->sql)==0)&&($segue==false)) {
                    $vazio="A consulta não pode ser executada!$vazio";
                }else{
                    $tabela .= $this->processaConsulta($count);
                }
            } else {
                if ( (trim($this->sql)=='') || ($segue==false) ) {
                    $vazio="A consulta não pode ser executada!$vazio";
                }else{
                    $tabela .= $this->processaConsulta($count);
                }
            }

            if ($this->intervaloCabecalho == "") {
                $tabela .= "</TABLE>";
                $tabela .= "</TD></TR>";

                if(trim($vazio)!=''){$tabela .= "\n<TR><TD ALIGN=CENTER>$vazio</TD></TR>";}


                if (trim($this->mensagem_retorno)!="") {
                    $tabela .= "\n<TR><TD BGCOLOR=#FFFFFF><P ALIGN=CENTER><FONT SIZE=2 FACE=VERDANA><STRONG>".$this->mensagem_retorno."</STRONG></FONT></P></TD></TR>";
                }

                $fonteDados = "<FONT SIZE=1 class=\"rodape_classTable\"><I>fonte:</I> SGC (".date('d/m/Y H:i').")</FONT>.";

                if ($this->showFonteDados === true) {
                    $tabela .= "\n<TR><TD ALIGN=RIGHT BGCOLOR=#".$this->cor_nome_col."
                        style=\"{background-color: #".$this->cor_nome_col."}\">$fonteDados</TD>";
                    $tabela .= "</TR>";
                }

                $tabela .= "</TABLE>";
            }
        }

        GLOBAL $SISCONF;

        $this->timer->stop();
        $sqlTime = bcsub($this->timer2->total,0,4);
        $generationTime = bcsub($this->timer->total,0,4);
        $classTable = bcsub($this->timer->total,$this->timer2->total,4);
        $elapsedTime = "";

        if ($SISCONF['SIS']['METRICS']['ACTIVE']==true) {
            $elapsedTime = "<BR><CENTER><B class='naoVisivelImpressao'>Relatório gerado em: $generationTime seg. SQL($sqlTime) TB($classTable)</B></CENTER>";
        }

        $tabela .= $elapsedTime;
        if ( $this->retornaRelatorio == true ) {
            return $tabela;
        } else {
            echo $tabela;
        }

    }

    function processaConsulta($count)
    {
        //Executa a conexão
        $this->timer2->start();
        $this->qry->TQuery($this->sql);
        $this->timer2->stop();

        $tabela = "";
        $cab = "";
        $z = "";
        $target = "";
        $rod = "";

        $z=" style=\"{background-color: #".$this->cor_linha2."}\"";

        $ctrl = array ('','','');
        $show = array (true,true,true);
        $num=0;

        settype($fixo,"integer");
        $fixo=$this->fixar_numero;

        $controla=false;
        if ($this->fixar_numero>0){$controla=true;}

        $lineConsulta = "\n";
        $ibold = "#ibold#";
        $fbold = "#fbold#";

        if ( $this->intervaloCabecalho != "" ) {
            /*-----------------------------Cabeçalho---------------------------*/
            if ($this->mostrar_borda){

                $cab = "<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=3 ALIGN=".$this->table_alin."
                    ".$this->width."  style=\"{background-color: #".$this->cor_tabela_borda."}\"".
                    " BGCOLOR=#".$this->cor_tabela_borda.">";

            }else{

                $cab .=  "\n<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=3 ALIGN=".$this->table_alin."
                        ".$this->width." style=\"{background-color: #".$this->cor_titulo."}\">";

            }

            $cab .=  "\n<TR CLASS='REPORT_CELL_BORDER'><TD style=\"{background-color: #".$this->cor_background."}\">";

            $vStyleBack = "";

            if ($this->cor_background!=""){
                $vStyleBack=" CLASS='REPORT_CELL_BORDER' ";
            }

            if ($this->mostrar_grade){
                $cab .=  "\n<TABLE BORDER=1 CELLSPACING=0 CELLPADDING=1 ".$this->width." $vStyleBack>";
            }else{
                $cab .=  "\n<TABLE BORDER=0 CELLSPACING=0 CELLPADDING=1 ".$this->width." $vStyleBack>";
            }
            $cab .= $this->getTitulo(true);
            $dCols = 0;

            $cab .= $this->getSubTitulosColspan($count);
              $cab .= $this->getSubCabecalho($count);

            /*-----------------------Rodapé------------------------*/

            if ($this->cor_background!=""){
                $vStyleBack=" CLASS='REPORT_CELL_BORDER' ";
            }

            $rod =  "</TABLE>";

            if ($this->mostrarLegenda != "") {
                $rod .= "<TR><TD><FONT SIZE=1>".$this->mostrarLegenda."</FONT></TD></TR>";
            }

            $rod .=  "</TD></TR>";
            $fonteDados .= "<FONT SIZE=1><I>fonte:</I> ".$this->instituicao." - SGL (".date('d/m/Y H:i').")</FONT>.";

            if ($this->showFonteDados === true) {

                $rod .=  "\n<TR $vStyleBack ><TD ALIGN=RIGHT BGCOLOR=#".$this->cor_nome_col." style=\"{background-color: #".$this->cor_nome_col."}\">$fonteDados</TD>";
                $rod .=  "</TR>";

            }

            $rod .=  "</TABLE>";

            $rod .="<p STYLE=\"page-break-before:always\"></p>";

        }

        while($dyn = $this->qry->fetchrow()){
            $lineConsulta = str_replace("#ibold#","",$lineConsulta);
            $lineConsulta = str_replace("#fbold#","",$lineConsulta);

            if ( $num % ($this->intervaloCabecalho+1) == 0)  {
                $tabela .= $cab;
            }
            $partedoRelatorio='meio';
            $tabela .= $lineConsulta;

            $num++;
            if (($controla==true)&&($num>$fixo)){

            }else{
                $lineConsulta = "\n<TR $z>";
                if ($this->controle_nivel>2){
                    if ($ctrl[2] <> strtoupper(strtoupper($dyn[$this->col_nome_sql[2]]))){
                        $show[2] = true;
                    }else{
                        $show[2] = false;
                    }
                    $ctrl[2]=strtoupper(strtoupper($dyn[$this->col_nome_sql[2]]));
                }

                if ($this->controle_nivel>1){
                    if ($ctrl[1] <> strtoupper(strtoupper($dyn[$this->col_nome_sql[1]]))){
                        $show[1] = true;
                        $show[2] = true;
                    }else{
                        $show[1] = false;
                    }
                    $ctrl[1]=strtoupper(strtoupper($dyn[$this->col_nome_sql[1]]));
                }
                if ($this->controle_nivel>0){
                    if ($ctrl[0] <> strtoupper(strtoupper($dyn[$this->col_nome_sql[0]]))){
                        $show[0] = true;
                        $show[1] = true;
                        $show[2] = true;
                    }else{
                        $show[0] = false;
                    }
                    $ctrl[0]=strtoupper(strtoupper($dyn[$this->col_nome_sql[0]]));
                }

                if ( isset($dyn[$this->colDestaque]) ) {
                    if ( $dyn[$this->colDestaque] != ""  ){
						$z=" style=\"{background-color: #".$this->cor_linha_destaque."}\"";
                    }
                }

                //mostra o sequencial da Esquerda
                if ($this->numerador_esq){
                    $lineConsulta .= "<TD ALIGN=RIGHT VALIGN=TOP CELLPADDING=1>";
                        if (count($this->columnDetailFontAttributtes)>0) {
                            $lineConsulta .= $this->columnDetailFontAttributtes['OPEN'].$num.$this->columnDetailFontAttributtes['CLOSE'];
                        }else{
                            $lineConsulta .= "<FONT SIZE=1 FACE=\"tahoma\">$num</FONT>";
                        }
                    $lineConsulta .= "</TD>";
                }

                //lê todas as colunas e coloca na tabela
                for($i=0;$i<$count;$i++){
                    $campo=$dyn[$this->col_nome_sql[$i]];
                    if ($this->substituirPorBR == true){
                        $campo = str_replace("\n","<BR>",$campo);
                    }
                    if ($this->bb_calc_mod11==true){
                        if($i==$this->bb_calc_mod11_col_calc){
                            if(trim($dyn[$this->bb_calc_mod11_col_name])=="001"){
                                $campo=$campo."-".impressaoDOC::DAC($campo);
                            }
                        }
                    }

                    $pl_pagina="";
                    if (($this->pagina_link != "") && ($this->pagina_link_param_ref == $this->col_titulo[$i])){
                        if ($this->pagina_link_abre_nova_janela==true){
                            $pl_pagina="'javascript:doOpenNewWindow(\"";
                        }

                        $pl_pagina.=$this->pagina_link."?";
                        $pl_params="";
                        $pl_join="";
                        $m_col=0;

                        for ($m_col=0;$m_col<$this->num_par;$m_col++){
                            $pl_params="$pl_params"."$pl_join";
                            $pl_params="$pl_params".$this->pagina_link_params_nome[$m_col];
                            if ($this->pagina_link_params_coluna[$m_col]!=""){
                                $pl_params="$pl_params=".str_replace("/",".",strtoupper($dyn[$this->pagina_link_params_coluna[$m_col]]));
                                $pl_join="&";
                            }

                        }

                        if ($this->pagina_link_abre_nova_janela==true){
                            $pl_params.="\")'";
                        }

                    }

                    if ( isset($this->col_negrito[$i]) ) {
                        if (strtoupper($this->col_negrito[$i])==true) {$campo="<STRONG>".$campo."</STRONG>";}
                    }

                    if ( isset($this->col_estilo[$i]) ) {
                        if (strtoupper($this->col_estilo[$i])=='PROPERCASE') {
                            $campo=mb_convert_case(mb_strtolower($campo),MB_CASE_TITLE);
                        }elseif (strtoupper($this->col_estilo[$i])=='UCASE') {
                            $campo=mb_strtoupper($campo);
                        }elseif (strtoupper($this->col_estilo[$i])=='LCASE') {
                            $campo=mb_strtolower($campo);
                        }
                    }

                    $valign="LEFT";
                    if ( isset($this->col_alin[$i]) ) {
                        if (strtoupper($this->col_alin[$i])=='LEFT') {$valign="LEFT";}
                        if (strtoupper($this->col_alin[$i])=='RIGHT') {$valign="RIGHT";}
                        if (strtoupper($this->col_alin[$i])=='CENTER') {$valign="CENTER";}
                    }

                    if ( isset($this->globalTarget[0]) ) $target=$this->globalTarget[0];

                    if ($pl_pagina!=""){
                        if ($this->pagina_link_abre_nova_janela==true){
                            $campo="<A ".$target." HREF=".$pl_pagina."".$pl_params.">$campo</A>";
                        }else{
                            $campo="<A ".$target." HREF=\"".$pl_pagina."".$pl_params."\">$campo</A>";
                        }
                    }

                    $colwidth="";
                    if (isset($this->col_indiv_tamanho[$i])){
                        if ($this->col_indiv_tamanho[$i]>0){
                            $colwidth=" WIDTH=".$this->col_indiv_tamanho[$i];
                        }
                    }

                    if ($i < $this->controle_nivel){
                        if ($show[$i]){
                            $lineConsulta .= "\n<TD  ALIGN=$valign VALIGN=TOP CELLPADDING=1 $colwidth>\n";

                            if (count($this->columnDetailFontAttributtes)>0) {
                                $lineConsulta .= $this->columnDetailFontAttributtes['OPEN'].
                                        $ibold."$campo".$fbold.
                                        $this->columnDetailFontAttributtes['CLOSE'];
                            }else{
                                $lineConsulta .= "<FONT SIZE=1 FACE=\"tahoma\" class=\"class_table\">\n".$ibold;
                                $lineConsulta .= "$campo";
                                $lineConsulta .= $fbold."</FONT>";
                            }
                            $lineConsulta .= "</TD>";

                        }else{
                            $lineConsulta .= "<TD  CLASS='REPORT_CELL_BORDER' ></TD>";
                        }
                    }else{

                        $lineConsulta .= "\n<TD  ALIGN=$valign VALIGN=TOP CELLPADDING=1 $colwidth>\n";

                        if (count($this->columnDetailFontAttributtes)>0) {
                            $lineConsulta .= $this->columnDetailFontAttributtes['OPEN'].
                                    $ibold."$campo".$fbold.
                                    $this->columnDetailFontAttributtes['CLOSE'];
                        }else{
                            $lineConsulta .= "<FONT SIZE=1 FACE=\"tahoma\" class=\"class_table\">\n".$ibold;
                            $lineConsulta .= $campo;
                            $lineConsulta .= $fbold."</FONT>";
                        }
                        $lineConsulta .= "</TD>";
                    }
                }

                //mostra o sequencial da Direita
                if ($this->numerador_dir){
                    $lineConsulta .= "<TD ALIGN=RIGHT VALIGN=TOP CELLPADDING=1 $z>";
                        if (count($this->columnDetailFontAttributtes)>0) {
                            $lineConsulta .= $this->columnDetailFontAttributtes['OPEN'].$num.$this->columnDetailFontAttributtes['CLOSE'];
                        }else{
                            $lineConsulta .= $ibold;
                            $lineConsulta .= "<FONT SIZE=1 FACE=\"tahoma\">$num</FONT>";
                            $lineConsulta .= $fbold;
                        }
                    $lineConsulta .= "</TD>";
                }
                if ( isset($dyn[$this->colDestaque]) ) {
                    if ($dyn[$this->colDestaque]!=""  ){
                        $z = $style;
                    }
                }

				if ( $z ==" style=\"{background-color: #".$this->cor_linha2."}\"") {
					$z =" style=\"{background-color: #".$this->cor_linha1."}\"";
                } else {
                    $z=" style=\"{background-color: #".$this->cor_linha2."}\"";
                }

                $lineConsulta .= "</TR>\n";
            }

            if ( $num % ($this->intervaloCabecalho+1) == 0 )  {
                $tabela .= $rod;
                $partedoRelatorio = 'rodape';
            }
        }

        if ($this->lastLineInBold) {
            $lineConsulta = str_replace("#ibold#","<B>",$lineConsulta);
            $lineConsulta = str_replace("#fbold#","</B>",$lineConsulta);
        }else{
            $lineConsulta = str_replace("#ibold#","",$lineConsulta);
            $lineConsulta = str_replace("#fbold#","",$lineConsulta);
        }


        if ($num % ($this->intervaloCabecalho+1) != 0 ){
            $tabela .= $lineConsulta;
            $tabela .= $rod;
            $partedoRelatorio = 'rodape';
        }else{
            $tabela .= $cab;
            if (($num<1) && ($this->intervaloCabecalho!="")){
                $tabela .= "\n<TR><TD colspan=".count($this->col_titulo)." BGCOLOR=#FFFFFF><P ALIGN=CENTER><FONT SIZE=2 FACE=VERDANA><STRONG> - Nada a Listar -  </STRONG></FONT></P></TD></TR>";
            }else{
                $tabela .= $lineConsulta;
            }
            $tabela .= $rod;
        }

        if($num<1){
            $this->mensagem_retorno=" - Nada a Listar - ";
        }else{
            $this->mensagem_retorno="";
        }

        if (($this->mostrarLegenda != "") && (trim($this->intervaloCabecalho) == "") && ($num>=1))
        {
            $this->mensagem_retorno = "<FONT style='font-size:9; font-weight:normal;'>".$this->mostrarLegenda."</FONT>";
        }
        
        return $tabela;
    }
}
?>