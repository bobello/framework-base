<?php

   /**
    * Pacote para gerenciamento de abas
    *
    * PHP versions 4
    *
    * @author Diego Mancilha
    * @version 2006.09.04
    */

   /**
    * Mensagem
    *
    * Classe para tratamento de Mensagens
    * @package package_abas
    * @author Diego Mancilha
    */
    class Mensagem
    {
        /**
         * Para armazenamento de uma descrição do que se
         * trata as mensagens
         * @var string
         * @access private
         */
        var $titulo;

        /**
         * Para armazenamento das mensagens.
         * @var array
         * @access private
         */
        var $mensagens;

        /**
         * Para armazenamento dos tipos de mensagens.
         * @var array
         * @access private
         */
        var $tipos;

        /**
         * Para controle da quantidade de mensagens por tipo
         * @var array
         * @access private
         */
        var $qtdPorTipo;

        /**
           * Define a largura da caixa de mensagem
           * @var integer
           * @access private
           */
        var $largura;

        /**
           * Define a altura da caixa de mensagem
           * @var integer
           * @access private
           */
        var $altura;

        /**
         * Mensagem
         *
         * Método construtor. Inicializa o array de mensagens
         * @param string titulo
         * @return void
         * @access public
         */
        function Mensagem($titulo="")
        {
            $this->titulo = $titulo;
            $this->mensagens = array();
            $this->qtdPorTipo = array();
            $this->inicializaTipos();
            $this->largura = "400";
            $this->altura = "200";
        }

        /**
         * inicializaTipos
         *
         * Inicializa os tipos de mensagens aceitos
         * @return void
         * @access public
         */
        function inicializaTipos()
        {
            $this->tipos['erro'] = 'Erro(s): ';
            $this->tipos['aviso'] = 'Aviso(s): ';

            foreach ($this->tipos as $chaveTipo => $descricao) {
                $this->qtdPorTipo[$chaveTipo] = 0;
            }
            $this->qtdPorTipo['geral'] = 0;
        }

        /**
         * setLargura
         *
         * Configura a largura da caixa de mensagens
         * @param largura string
         * @return void
         * @access public
         */
         function setLargura($largura)
         {
            $this->largura = $largura;
         }

         /**
         * setAltura
         *
         * Configura a altura da caixa de mensagens
         * @param largura string
         * @return void
         * @access public
         */
         function setAltura($altura)
         {
            $this->altura = $altura;
         }

        /**
         * adicionaMensagem
         *
         * Adiciona uma mensagem ao array
         * @param string msg
         * @param string tipo
         * @return void
         * @access public
         */
        function adicionaMensagem($msg,$tipo)
        {
            if (isset($this->tipos[$tipo])) {
                if (!isset($this->mensagens[$tipo])) {
                    $this->mensagens[$tipo] = array();
                    $this->qtdPorTipo[$tipo] = $this->qtdPorTipo[$tipo] + 1;
                    $this->qtdPorTipo['geral'] = $this->qtdPorTipo['geral'] + 1;
                }
                $this->mensagens[$tipo][] = $msg;
            } else {
                $this->adicionaMensagem('Você está tentando adicionar um tipo de mensagem que não é válido!','erro');
            }
        }


        /**
         * retornaQtdMensagens
         *
         * Retorna a quantidade de mensagens armazenadas
         * caso o tipo seja especificado será retornado somente
         * a quantidade para o tipo informado
         * @param string tipo
         * @return void
         * @access public
         */
        function retornaQtdMensagens($tipo='geral')
        {
            return $this->qtdPorTipo[$tipo];
        }


        /**
         * retornaMensagens
         *
         * Se o parâmetro formatoArray for especificado como true ele irá
         * simplesmente retornar o array de erros, caso contrário irá varrer
         * o array concatenando os valores em uma única string que será retornada.
         * Caso o tipos não seja passado será retornado todos os tipos.
         * @param string tipo
         * @param boolean formatoArray
         * @return mixed
         * @access public
         */
        function retornaMensagens($tipo="", $formatoArray=false)
        {
            if (trim($tipo) == '') {
                if ($formatoArray===true) {
                    return $this->mensagens;
                } else {
                    $retorno = "";
                    foreach ($this->tipos as $chaveTipo => $descricao) {
                        $retorno .= $this->retornaMensagens($chaveTipo, false);
                    }
                    return $retorno;
                }
            } else {
                if ($formatoArray===false) {
                    $textoErro = "";
                    if (isset($this->mensagens[$tipo])) {
                        if (trim($this->titulo) != '') {
                            $textoErro = '<b>'.$this->titulo.' ['.$this->tipos[$tipo].']</b><br>';
                        } else {
                            $textoErro = '<b>'.$this->tipos[$tipo].'</b><br>';
                        }
                        foreach ($this->mensagens[$tipo] as $id => $descricao) {
                            $textoErro .= "<br>".$descricao;
                        }
                        $textoErro .= "<br>";
                    }
                    return $textoErro;
                } else {
                    return $this->mensagens[$tipo];
                }
            }
        }

        /**
         * exibeMensagem
         *
         * Exibe as mensagens armazenadas conforme o tipo informado
         * @param string tipo Tipo da mensagem
         * @param string id Identificador para a div
         * @param string estilo
         * @return string
         * @access public
         */
        function exibeMensagem($tipo='', $id='', $estilo='')
        {
            $mensagem = $this->retornaMensagens($tipo);
            if (trim($estilo) == '') {
                $estilo = " style='background-color:white;"
                                 ." top:30%; left:30%; "
                                 ." position: absolute;"
                                 ." border: 1px solid black;"
                                 ." width: ".$this->largura."px; height:".$this->altura."px;"
                                 ." overflow:auto;'";
            } else {
                $estilo = " ".$estilo;
            }

            if (trim($id) == '') {
                $id = 'id'.mt_rand(1, 40000);
                $idStr = " id='".$id."'";
            } else {
                $idStr = " id='".$id."'";
            }

            $js = "\n<script language=\"javascript\">"
                ."\n    function centraliza(largura){"
                ."\n        y = parseInt((screen.width - largura)/2);"
                ."\n        document.getElementById('".$id."').style.left = y;"
                ."\n    }"
                ."\n    centraliza(".$this->largura.")"
                ."\n</script>";

            echo "\n<div ".$estilo.$idStr.">";
            echo "\n    <table border=0 cellpadding=0 cellspacing=0 style='width: 100%;'>";
            echo "\n        <tr>";
            echo "\n            <td style='text-align: center;'>";
            echo "\n            <br><input class='BUTTON' name='fecha' value='Fechar' type='button' onClick=\"getElementById('".$id."').style.display = 'none';\"><br>";
            echo "\n            </td>";
            echo "\n        </tr>";
            echo "\n        <tr>";
            echo "\n            <td style='text-align: justify; padding: 2em;'>";
            echo "\n            ".$mensagem;
            echo "\n            </td>";
            echo "\n        </tr>";
            echo "\n    </table>";
            echo "\n</div>"
            .$js;

        }
    }


   /**
    * Aba
    *
    * Classe para tratamento de Abas
    * @package package_abas
    * @author Diego Mancilha
    */
    class Aba
    {
        /**
         * Nome da Aba
         * @var string
         * @access private
         */
        var $nome;

        /**
         * Código em HTML da Aba
         * @var string
         * @access private
         */
        var $codigoHTML;

        /**
         * Contém a lista de elementos pertencentes a aba.
         * @var array
         * @access private
         */
        var $listaElementos;

        /**
         * Para controlar o número de elementos pertencentes a aba.
         * @var integer
         * @access private
         */
        var $numeroElementos;

        /**
         * Para controlar o número de colunas por linha
         * @var array
         * @access private
         */
        var $controleElementos;

        /**
         * Para controlar as posicoes ocupadas na matrix (linha,coluna).
         * @var array
         * @access private
         */
        var $posicoes;

        /**
         * Para controlar o número máximo de linhas.
         * @var integer
         * @access private
         */
        var $maximoLinhas;

        /**
         * Para controlar o número máximo de colunas.
         * @var integer
         * @access private
         */
        var $maximoColunas;

        /**
         * Lista dos tipos possíveis para um elemento
         * @var array
         * @access private
         */
        var $listaTipos;

        /**
         * Define o tipo de adicao de elementos na Aba
         * @var string
         * @access private
         */
        var $tipoAdicao;

        /**
         * Para armazenamento das mensagens
         * @var mensagem
         * @access private
         */
        var $objetoMensagens;

        /**
         * Para armazenamento do estilo a ser aplicado
         * @var string
         * @access private
         */
        var $estilo;


        /**
         * Para indicar se será possível ou não que o conteúdo da
         * aba seja impresso.
         * @var boolean
         * @access private
         */
        var $permiteImpressao;


        /**
         * Aba
         *
         * Método construtor da Aba.
         * @param string nome Nome da Aba
         * @param integer linhas Quantidade de linhas
         * @param integer colunas Quantidade de colunas
         * @param string estilo Estilo geral da Aba
         * @param string permiteImpressao Indicação de que o conteúdo poderá ou não ser impresso
         * @return void
         * @access public
         */
        function Aba($nome, $linhas=0, $colunas=0, $estilo="", $permiteImpressao=false)
        {
            $this->nome = $nome;

            if (($linhas > 0) || ($colunas > 0)) {
                $this->tipoAdicao = 'comPosicionamento';

                if ($linhas > 0) {
                    $this->maximoLinhas = $linhas;
                }

                if ($colunas > 0) {
                    $this->maximoColunas = $colunas;
                }
            } else {
                $this->maximoLinhas = 0;
                $this->maximoColunas = 0;
            }

            /**
             * Inicializando a lista de tipos aceitos
             * O valor atribuido ao tipo é nome do método
             * a ser chamado para obtenção do código do objeto
             */
            $this->listaTipos['packageForm'] = 'getCode';
            $this->listaTipos['mindLight'] = 'getCode';
            $this->listaTipos['text/html'] = '';

            $this->listaElementos = array();
            $this->numeroElementos = 0;
            $this->controleElementos = array();
            $this->posicoes = array();
            $this->objetoMensagens = new Mensagem('Erros Relacionados a aba: '.$this->nome);
            $this->codigoHTML = "";
            $this->tipoAdicao = '';
            $this->setaPermiteImpressao($permiteImpressao);

            if (trim($estilo) != "") {
                $this->estilo = $estilo;
            } else {
                $this->estilo = "style='width: 100%; height: 100%;'";
            }
        }

        /**
         * adicionaElemento
         *
         * Adiciona um elemento a aba. Utilizando este método NÃO PODE
         * ser informado a posição do elemento. Caso haja necessidade disto
         * deve ser utilizado o método posicionaElemento.
         * @param mixed elemento Elemento a ser adicionado
         * @param string tipoElemento Tipo do elemento a ser adicionado.
         * @return void
         * @access public
         */
        function adicionaElemento($elemento, $tipoElemento)
        {
            if (!isset($this->listaTipos[$tipoElemento])) {
                $this->objetoMensagens->adicionaMensagem("O tipo de elemento <b>".$tipoElemento."</b> não é aceito!",'erro');
            }

            if (trim($this->tipoAdicao) == '') {
                $this->tipoAdicao = 'semPosicionamento';
            }

            if ($this->tipoAdicao == 'semPosicionamento') {
                if (isset($this->listaTipos[$tipoElemento])) {
                    $this->listaElementos[$this->numeroElementos]['elemento'] = $elemento;
                    $this->listaElementos[$this->numeroElementos]['tipo'] = $tipoElemento;
                    $this->numeroElementos = $this->numeroElementos + 1;
                }
            } else {
                $this->objetoMensagens->adicionaMensagem("Para uma mesma aba deve-se utilizar somente um método de adicao: adicionaElemento ou posicionaElemento",'erro');
            }
        }

        /**
         * posicionaElemento
         *
         * Posiciona um elemento na aba. Utilizando este método DEVE
         * ser informado a posição do elemento. Caso não haja necessidade disto
         * deve ser utilizado o método adicionaElemento.
         * @param integer linha Linha na qual o elemento será adicionado
         * @param integer coluna Coluna na qual o elemento será adicionado
         * @param mixed elemento Elemento a ser adicionado
         * @param string tipoElemento Tipo do elemento a ser adicionado.
         * @param string estilo Estilo a ser aplicado para o elemento
         * @param integer rowspan Número de linhas a serem expandidas
         * @param integer colspan Número de colunas a serem expandidas
         * @return void
         * @access public
         */
        function posicionaElemento($linha, $coluna, $elemento, $tipoElemento, $estilo="", $rowspan=0, $colspan=0)
        {
            if (!isset($this->listaTipos[$tipoElemento])) {
                $this->objetoMensagens->adicionaMensagem("O tipo de elemento <b>".$tipoElemento."</b> não é aceito!",'erro');
            }

            if (trim($estilo) == "") {
                $estilo = 'style="{text-align: left; vertical-align: top;}"';
            }

            if (trim($this->tipoAdicao) == '') {
                $this->tipoAdicao = 'comPosicionamento';
            }

            if ($this->tipoAdicao == 'comPosicionamento') {
                if (isset($this->listaTipos[$tipoElemento])) {

                    if ($colspan > 0) {
                        $numColunas = $colspan;
                    } else {
                        $numColunas = 1;
                    }

                    if ($rowspan > 0) {
                        for ($i=0; $i<$rowspan; $i++) {
                            $lin = $linha + $i;
                            if (!isset($this->controleElementos[$lin])) {
                                $this->controleElementos[$lin] = 0;
                            }
                        }
                    }

                    if (!isset($this->controleElementos[$linha])) {
                        $this->controleElementos[$linha] = $numColunas;
                    } else {
                        $this->controleElementos[$linha] = $this->controleElementos[$linha] + $numColunas;
                    }

                    $this->listaElementos[$linha][$coluna]['elemento'] = $elemento;
                    $this->listaElementos[$linha][$coluna]['tipo'] = $tipoElemento;
                    $this->listaElementos[$linha][$coluna]['rowspan'] = $rowspan;
                    $this->listaElementos[$linha][$coluna]['colspan'] = $colspan;
                    $this->listaElementos[$linha][$coluna]['estilo'] = $estilo;

                    $this->numeroElementos = $this->numeroElementos + 1;
                }
            } else {
                $this->objetoMensagens->adicionaMensagem("Para uma mesma aba deve-se utilizar somente um método de adicao: adicionaElemento ou posicionaElemento",'erro');
            }
        }

        /**
         * setaPermiteImpressao
         *
         * Seta a propriedade permiteImpressao, que indica se o conteúdo da
         * aba poderá ou não ser impresso.
         * @param boolean valor
         * @return void
         * @access public
         */
        function setaPermiteImpressao($valor=true)
        {
            $this->permiteImpressao = $valor;
        }

        /**
         * setaMaximos
         *
         * Seta as propriedades que contém o máximo de linhas e colunas da tabela
         * a ser escrita
         * @return void
         * @access private
         */
        function setaMaximos()
        {
            /**
             * Se não foi informado na incialização
             */
            if ($this->maximoLinhas == 0) {
                $this->maximoLinhas = count($this->controleElementos);
            }

            /**
             * Se não foi informado na incialização
             */
            if ($this->maximoColunas == 0) {
                foreach ($this->controleElementos as $linha => $qtdColunas) {
                    if ( $qtdColunas > $this->maximoColunas) {
                        $this->maximoColunas = $qtdColunas;
                    }
                }
            }
        }

        /**
         * setLarguraCaixaMensagens
         *
         * Configura a largura da caixa de mensagens
         * @param largura string
         * @return void
         * @access public
         */
        function setLarguraCaixaMensagens($largura)
        {
            $this->objetoMensagens->setLargura($largura);
        }

        /**
         * setAlturaCaixaMensagens
         *
         * Configura a altura da caixa de mensagens
         * @param altura string
         * @return void
         * @access public
         */
         function setAlturaCaixaMensagens($altura)
         {
            $this->objetoMensagens->setAltura($altura);
         }

        /**
         * geraCodigoComPosicionamento
         *
         * Gera o código html da aba. O código será
         * armazenado na propriedade codigoHTML.
         * Este método é responsável pela geração de código
         * para o tipo de Adicao 'comPosicionamento'
         * @return boolean
         * @access private
         */
        function geraCodigoComPosicionamento()
        {
            $this->setaMaximos();
            $this->inicializaPosicoesOcupadas();
            $retorno = true;
            if (($this->maximoLinhas > 0) || ($this->maximoLinhas > 0)) {

                $classeImpressao = "";
                if ($this->permiteImpressao == false){
                    $classeImpressao = " class='naoVisivelImpressao'";
                }

                $codHtml = "";
                $codHtml .= "\n <div ".$this->estilo.$classeImpressao.">";
                $codHtml .= "\n <table align='center' border=0 cellpadding=2 cellspacing=2 style='width: 100%; height:100%;'>";

                /**
                 * Varrendo Linhas
                 */
                for($linha=1; $linha<=$this->maximoLinhas; $linha++) {

                    $codHtml .= "\n\t <tr>";
                    //Varrendo Colunas
                    for($coluna=1; $coluna<=$this->maximoColunas; $coluna++) {
                        /**
                         * Determinando a chave da posição que será utilizada
                         * marcar as posições que o elemento irá ocupar
                         */
                        $chavePosicao = '('.$linha.','.$coluna.')';
                        if (isset($this->listaElementos[$linha][$coluna])) {
                            //Pegando o conteúdo a ser exibido
                            $dados = $this->listaElementos[$linha][$coluna];
                            extract($dados);
                            $conteudo = "";

                            if ($tipo != "text/html") {
                                $codigoExecuta = '$conteudo .= $elemento->'.$this->listaTipos[$tipo].'();';
                                eval($codigoExecuta);
                            } else {
                                $conteudo =  $elemento;
                            }
                            // Marcando posicao como ocupada
                            $this->ocupaPosicao($linha, $coluna, $chavePosicao);

                            //Verificando propriedades definidas para a célula
                            $atributosCel = "";
                            if ($colspan>0) {
                                $atributosCel .= " colspan=".$colspan;
                                // Começa em 1 pois a (linha,coluna) já foi marcada antes
                                for ($i=1; $i<$colspan; $i++) {
                                    $col = $coluna + $i;
                                    $this->ocupaPosicao($linha, $col, $chavePosicao);
                                }
                            }

                            if ($rowspan>0) {
                                $atributosCel .= " rowspan=".$rowspan;
                                // Começa em 1 pois a (linha,coluna) já foi marcada antes
                                for ($i=1; $i<$rowspan; $i++) {
                                    $lin = $linha + $i;
                                    if ($colspan>0) {
                                        // Começa em 0 pois refere-se as linhas abaixo da atual
                                        for ($j=0; $j<$colspan; $j++) {
                                            $col = $coluna + $j;
                                            $this->ocupaPosicao($lin, $col, $chavePosicao);
                                        }
                                    } else {
                                        $this->ocupaPosicao($lin, $coluna, $chavePosicao);
                                    }
                                }
                            }

                            if (trim($estilo) != "") {
                                $estilo = " ".$estilo;
                            }

                            $codHtml .= "\n\t\t <td".$atributosCel.$estilo." class='cadastro'>";
                            $codHtml .= "\n\t\t\t ".$conteudo;
                            $codHtml .= "\n\t\t </td>";

                            unset($elemento, $tipo, $rowspan, $colspan, $estilo);
                        } else {
                            if ($this->verificaPosicao($linha,$coluna) == '(0,0)') {
                                $this->ocupaPosicao($linha, $coluna, '(V,V)');
                                $codHtml .= "\n\t\t <td class=mostraCorFundo>";
                                $codHtml .= "\n\t\t\t ";
                                $codHtml .= "\n\t\t </td>";
                            }
                        }
                    }
                    $codHtml .= "\n\t </tr>";
                }
                $codHtml .= "\n </table>";
                $codHtml .= "\n </div>";
                $this->codigoHTML = $codHtml;
                if (trim($this->objetoMensagens->retornaMensagens('erro')) != '') {
                    $this->codigoHTML = "";
                    $retorno = false;
                }
            }
            return $retorno;
        }


        /**
         * geraCodigoSemPosicionamento
         *
         * Gera o código html da aba. O código será
         * armazenado na propriedade codigoHTML.
         * Este método é responsável pela geração de código
         * para o tipo de Adicao 'semPosicionamento'
         * @return boolean
         * @access private
         */
        function geraCodigoSemPosicionamento()
        {
            $retorno = true;
            if ($this->numeroElementos > 0) {

                $classeImpressao = "";
                if ($this->permiteImpressao == false){
                    $classeImpressao = " class='naoVisivelImpressao'";
                }

                $this->codigoHTML = "";
                $this->codigoHTML .= "\n <div ".$this->estilo.$classeImpressao.">";

                foreach ($this->listaElementos as $elementoAtual => $dados) {
                    extract($dados);
                    if ($tipo != "text/html") {
                        $codigoExecuta = '$this->codigoHTML .= $elemento->'.$this->listaTipos[$tipo].'();';
                        eval($codigoExecuta);
                    } else {
                        $this->codigoHTML .=  $elemento;
                    }
                    unset($elemento,$tipo);
                }
                $this->codigoHTML .= "\n </div>";
            } else {
                $this->objetoMensagens->adicionaMensagem("Não foram adicionados elementos a aba!",'erro');
                $retorno =  false;
            }
            return $retorno;
        }


        /**
         * geraCodigo
         *
         * Chama o método responsável pela geração do código da aba.
         * O método a ser chamado irá depender do tipo de adicao de elementos.
         * O código gerado fica armazenado na propriedade codigoHTML.
         * @return boolean
         * @access public
         */
        function geraCodigo()
        {
            $erros = $this->objetoMensagens->retornaMensagens('erro');
            $retorno = false;
            if (trim($erros) != "") {
                return false;
            } else {
                switch($this->tipoAdicao) {
                case 'comPosicionamento':
                    $retorno = $this->geraCodigoComPosicionamento();
                    break;
                case 'semPosicionamento':
                default:
                    $retorno = $this->geraCodigoSemPosicionamento();
                    break;
                }
            }
            return $retorno;
        }

        /**
         * inicializaPosicoesOcupadas
         *
         * Inicializa o array de posicoes ocupadas sendo todas livres
         * Este array é para controle interno.
         * @return void
         * @access private
         */
        function inicializaPosicoesOcupadas()
        {
            for($linha=1; $linha<=$this->maximoLinhas; $linha++) {
                for($coluna=1; $coluna<=$this->maximoColunas; $coluna++) {
                        $this->posicoes[$linha][$coluna] = '(0,0)';
                }
            }
        }

        /**
         * verificaPosicao
         *
         * Retornado a chave (linha,coluna) que está ocupando a posição.
         * Essa chave refere-se a propriedade listaElementos, que contém
         * todos os elementos da Aba. Caso a chave retornada seja (0,0)
         * indica que a posição está livre.
         * @param integer linha Linha a ser verificada
         * @param integer coluna Coluna a ser verificada
         * @return string
         * @access private
         */
        function verificaPosicao($linha, $coluna)
        {
            $chave = $this->posicoes[$linha][$coluna];
            return $chave;
        }

        /**
         * ocupaPosicao
         *
         * Verifica se a posição está livre, caso esteja, esta será marcada
         * com a chave passada e será retornado true. Caso contrário será
         * adicionado uma mensagem ao array de erros e retornado false.
         * @param integer linha Linha a ser marcada
         * @param integer coluna Coluna a ser marcada
         * @param string chave Indica qual posição irá ocupar este espaço
         * @return boolean
         * @access private
         */
        function ocupaPosicao($linha, $coluna, $chave)
        {
            $chaveOcupante = $this->verificaPosicao($linha,$coluna);
            $posicaoAOcupar = '('.$linha.','.$coluna.')';
            if ($chaveOcupante == '(0,0)') {
                $this->posicoes[$linha][$coluna] = $chave;
                return true;
            } else {
                $mensagem = "A chave ".$chave." esta tentando ocupar a posicão ".$posicaoAOcupar
                           ." já ocupada pela chave ".$chaveOcupante."!";

                $this->posicoes[$linha][$coluna] = $chaveOcupante.' + '.$chave;
                $this->objetoMensagens->adicionaMensagem($mensagem,'erro');
                return false;
            }
        }

        /**
         * retornaCodigo
         *
         * Retorna o código da Aba
         * @return string
         * @access public
         */
        function retornaCodigo()
        {
            if ($this->geraCodigo() === true) {
                return $this->codigoHTML;
            } else {
                $this->objetoMensagens->exibeMensagem('erro');
                return "";
            }
        }
    }

   /**
    * GerenciadorAbas
    *
    * Classe para o gerenciamento de várias Abas. Também pode ser utilizada como
    * um formulário normal
    * @package package_abas
    * @author Diego Mancilha
    */
    class GerenciadorAbas
    {
        /**
         * Nome do Formulário
         * @var string
         * @access private
         */
        var $nome;

        /**
         * Título do Formulário
         * @var string
         * @access private
         */
        var $titulo;

        /**
         * Legenda a ser exibida no formulário
         * @var string
         * @access private
         */
        var $legenda;

        /**
         * Indica se a legenda deve ou não ser exibida no formulário
         * @var boolean
         * @access private
         */
        var $exibeLegenda;

        /**
         * Largura do Formulário
         * @var integer
         * @access private
         */
        var $largura;

        /**
         * Altura do Formulário
         * @var integer
         * @access private
         */
        var $altura;

        /**
         * Quantidade de abas do Formulário
         * @var integer
         * @access private
         */
        var $qtdAbas;

        /**
         * Propriedade action do formulário
         * @var string
         * @access private
         */
        var $metodo;

        /**
         * Contém a url para a qual o formulário será enviado
         * @var string
         * @access private
         */
        var $url;

        /**
         * Armazena o código HTML a ser exibido.
         * @var string
         * @access private
         */
        var $codigoHTML;

        /**
         * Propriedade para armazenar o estilo
         * @var string
         * @access private
         */
        var $estilo;

        /**
         * Propriedade para armazenar as propriedades de cada botão
         * @var array
         * @access private
         */
        var $botao;

        /**
         * Define o tamanho padrão de fonte para ser utilizado nos botões
         * @var integer
         * @access private
         */
        var $tamanhoFonteBotao;

        /**
         * Define se deve ou não ser exibido a Barra de botões
         * @var boolean
         * @access private
         */
        var $exibeBarraBotoes;

        /**
         * Define se deve ou não ser gerado um formulário no momento
         * em que a geração do código é processada. O padrão é true.
         * @var boolean
         * @access private
         */
        var $geraFormulario;

        /**
         * Contém a lista de abas adicionadas ao formulário
         * @var array
         * @access private
         */
        var $listaAbas;

        /**
         * Contém o nome da aba Padrão a ser exibida
         * @var string
         * @access private
         */
        var $abaPadrao;

        /**
         * Contém o caminho onde as figuras para exibição dos botões
         * serão encontradas
         * @var string
         * @access private
         */
        var $caminhoFiguras;

        /**
         * Indica se o formulário irá ou não fazer upload de arquivos
         * @var boolean
         * @access private
         */
        var $enviaArquivo;

        /**
         * Para armazenamento das mensagens
         * @var mensagem
         * @access private
         */
        var $objetoMensagens;

        /**
         * GerenciadorAbas
         *
         * Método construtor do gerenciador de Abas.
         * @param string titulo
         * @param string url
         * @param string nome
         * @param string metodo
         * @return void
         * @access public
         */
        function GerenciadorAbas($titulo, $url, $nome='', $metodo='POST')
        {
            $this->nome = $nome;

            if (trim($this->nome) == '') {
                $this->nome = 'frm'.mt_rand(1, 40000);
            }

            $this->titulo = $titulo;
            $this->url = $url;
            $this->metodo = $metodo;

            $this->setaLegenda();
            $this->setaDimensoes();
            $this->qtdAbas = 0;

            $this->codigoHTML = '';
            $this->msg = '';
            $this->estilo = '';
            $this->setaEnvioDeArquivos(false);

            $this->setaExibicaoBarraBotoes(true);
            $this->listaAbas = array();

            GLOBAL $SISCONF;
            $this->setaCaminhoFiguras($SISCONF['SIS']['COMUM']['IMAGENS']);

            $this->setaAbaPadrao('');
            $this->tamanhoFonteBotao = 8;
            $this->inicializaBotoes();
            $this->setaGeraFormulario(true);

            $this->objetoMensagens = new Mensagem();
        }

        /**
         * setaEnvioDeArquivos
         *
         * Seta a propriedade enviaArquivo
         * @param boolean valor
         * @return void
         * @access public
         */
        function setaEnvioDeArquivos($valor=true)
        {
            $this->enviaArquivo = $valor;
        }

        /**
         * setaGeraFormulario
         *
         * Seta a propriedade geraFormulario
         * @param boolean valor
         * @return void
         * @access public
         */
        function setaGeraFormulario($valor=true)
        {
            $this->geraFormulario = $valor;
        }

        /**
         * setaDimensoes
         *
         * Seta as dimensões do gerenciador(formulario ou tela).
         * @param integer largura
         * @param integer altura
         * @return void
         * @access public
         */
        function setaDimensoes($largura=800, $altura=600)
        {
            $this->largura = $largura;
            $this->altura = $altura;
        }

        /**
         * setaCaminhoFiguras
         *
         * Seta o caminho onde as figuras estarão armazenadas
         * @param string caminho
         * @return void
         * @access public
         */
        function setaCaminhoFiguras($caminho)
        {
            $this->caminhoFiguras = $caminho;
        }

        /**
         * setaLegenda
         *
         * Seta o caminho onde as figuras estarão armazenadas
         * @param boolean exibe Indica se deve ou não ser exibida a legenda
         * @param string legenda Texto a ser atribuido
         * @return void
         * @access public
         */
        function setaLegenda($exibe=true, $legenda = '')
        {
            if (trim($legenda) == '') {
                $legenda = "<p style='color: red;'>* Dados Obrigatórios</p>";
            }
            $this->legenda = $legenda;
            $this->exibeLegenda = $exibe;
        }

        /**
         * inicializaBotoes
         *
         * Inicializa o array de botões
         * @param boolean exibe Indica se deve ou não ser exibida o botão
         * @param integer alturaBotao Altura do botão a ser exibido
         * @param integer larguraBotao Largura do botão a ser exibido
         * @return void
         * @access public
         */
        function inicializaBotoes($exibe=true, $alturaBotao=0, $larguraBotao=0)
        {
            if ($alturaBotao == 0) {
                $alturaBotao = 25;
            }

            if ($larguraBotao == 0) {
                $larguraBotao = 90;
            }

            $this->botao['novo']['texto'] = 'Novo';
            $this->botao['novo']['tamanhoFonte'] = $this->tamanhoFonteBotao;
            $this->botao['novo']['nomeInput'] = 'botao';
            $this->botao['novo']['nomeFigura'] = 'btnNovo.gif';
            $this->botao['novo']['tipoBotao'] = 'submit';
            $this->botao['novo']['evento'] = '';
            $this->botao['novo']['exibe'] = $exibe;
            $this->botao['novo']['alturaBotao'] = $alturaBotao;
            $this->botao['novo']['larguraBotao'] = $larguraBotao;

            $this->botao['gravar']['texto'] = 'Salvar';
            $this->botao['gravar']['tamanhoFonte'] = $this->tamanhoFonteBotao;
            $this->botao['gravar']['nomeInput'] = 'botao';
            $this->botao['gravar']['nomeFigura'] = 'btSalvar.gif';
            $this->botao['gravar']['tipoBotao'] = 'submit';
            $this->botao['gravar']['evento'] = '';
            $this->botao['gravar']['exibe'] = $exibe;
            $this->botao['gravar']['alturaBotao'] = $alturaBotao;
            $this->botao['gravar']['larguraBotao'] = $larguraBotao;

            $this->botao['excluir']['texto'] = 'Excluir';
            $this->botao['excluir']['tamanhoFonte'] = $this->tamanhoFonteBotao;
            $this->botao['excluir']['nomeInput'] = 'botao';
            $this->botao['excluir']['nomeFigura'] = 'btExcluir.gif';
            $this->botao['excluir']['tipoBotao'] = 'submit';
            $this->botao['excluir']['evento'] = '';
            $this->botao['excluir']['exibe'] = $exibe;
            $this->botao['excluir']['alturaBotao'] = $alturaBotao;
            $this->botao['excluir']['larguraBotao'] = $larguraBotao;

            $this->botao['imprimir']['texto'] = 'Imprimir';
            $this->botao['imprimir']['tamanhoFonte'] = $this->tamanhoFonteBotao;
            $this->botao['imprimir']['nomeInput'] = 'botao';
            $this->botao['imprimir']['nomeFigura'] = 'btImprime.gif';
            $this->botao['imprimir']['tipoBotao'] = 'button';
            $this->botao['imprimir']['evento'] = 'onClick=print();';
            $this->botao['imprimir']['exibe'] = $exibe;
            $this->botao['imprimir']['alturaBotao'] = $alturaBotao;
            $this->botao['imprimir']['larguraBotao'] = $larguraBotao;

            $this->botao['aviso']['texto'] = 'Aviso';
            $this->botao['aviso']['tamanhoFonte'] = $this->tamanhoFonteBotao;
            $this->botao['aviso']['nomeInput'] = 'botao';
            $this->botao['aviso']['nomeFigura'] = 'btAviso.gif';
            $this->botao['aviso']['tipoBotao'] = 'button';
            $this->botao['aviso']['evento'] = "onClick=exibeMensagens('msg".$this->nome."');";
            $this->botao['aviso']['exibe'] = $exibe;
            $this->botao['aviso']['alturaBotao'] = $alturaBotao;
            $this->botao['aviso']['larguraBotao'] = $larguraBotao;

            $this->botao['pesquisa']['texto'] = 'Pesquisa';
            $this->botao['pesquisa']['tamanhoFonte'] = $this->tamanhoFonteBotao;
            $this->botao['pesquisa']['nomeInput'] = 'botao';
            $this->botao['pesquisa']['nomeFigura'] = 'btPesquisa.gif';
            $this->botao['pesquisa']['tipoBotao'] = 'button';
            $this->botao['pesquisa']['evento'] = 'onClick=alert("Pesquisar");';
            $this->botao['pesquisa']['exibe'] = $exibe;
            $this->botao['pesquisa']['alturaBotao'] = $alturaBotao;
            $this->botao['pesquisa']['larguraBotao'] = $larguraBotao;

            $this->botao['voltar']['texto'] = 'Voltar';
            $this->botao['voltar']['tamanhoFonte'] = $this->tamanhoFonteBotao;
            $this->botao['voltar']['nomeInput'] = 'botao';
            $this->botao['voltar']['nomeFigura'] = 'btVoltar.gif';
            $this->botao['voltar']['tipoBotao'] = 'button';
            $this->botao['voltar']['evento'] = 'onClick=history.back();';
            $this->botao['voltar']['exibe'] = $exibe;
            $this->botao['voltar']['alturaBotao'] = $alturaBotao;
            $this->botao['voltar']['larguraBotao'] = $larguraBotao;

            $this->botao['sim']['texto'] = 'Sim';
            $this->botao['sim']['tamanhoFonte'] = $this->tamanhoFonteBotao;
            $this->botao['sim']['nomeInput'] = 'botao';
            $this->botao['sim']['nomeFigura'] = '';
            $this->botao['sim']['tipoBotao'] = 'submit';
            $this->botao['sim']['evento'] = '';
            $this->botao['sim']['exibe'] = false;
            $this->botao['sim']['alturaBotao'] = $alturaBotao;
            $this->botao['sim']['larguraBotao'] = $larguraBotao;

            $this->botao['nao']['texto'] = 'Não';
            $this->botao['nao']['tamanhoFonte'] = $this->tamanhoFonteBotao;
            $this->botao['nao']['nomeInput'] = 'botao';
            $this->botao['nao']['nomeFigura'] = '';
            $this->botao['nao']['tipoBotao'] = 'submit';
            $this->botao['nao']['evento'] = '';
            $this->botao['nao']['exibe'] = false;
            $this->botao['nao']['alturaBotao'] = $alturaBotao;
            $this->botao['nao']['larguraBotao'] = $larguraBotao;

        }

        /**
         * adicionaAba
         *
         * Adiciona uma Aba a Lista de Abas
         * @param string nomeAba Nome da Aba
         * @param string tituloAba Título a ser exibido
         * @param integer linhas Quantidade de linhas
         * @param integer colunas Quantidade de colunas
         * @param string estilo Estilo a ser aplicado
         * @param boolean permiteImpressao Indicação se poderá ou não ser impresso o seu conteúdo
         * @return void
         * @access public
         */
        function adicionaAba($nomeAba, $tituloAba='', $linhas=0, $colunas=0, $estilo="", $permiteImpressao=false)
        {
            if (!isset($this->listaAbas[$nomeAba])) {
                $this->listaAbas[$nomeAba]['titulo'] = $tituloAba;
                $this->listaAbas[$nomeAba]['objeto'] = new Aba($nomeAba, $linhas, $colunas, $estilo, $permiteImpressao);
                $this->listaAbas[$nomeAba]['habilitada'] = true;
                $this->qtdAbas = $this->qtdAbas + 1;

                if (trim($this->abaPadrao) == '') {
                    $this->setaAbaPadrao($nomeAba);
                }
            }
        }

        /**
         * adicionaMensagens
         *
         * Adiciona um array de mensagens ao form
         * @param array msgArray
         * @param string tipo
         * @return void
         * @access public
         */
        function adicionaMensagens($msgArray,$tipo) {
            foreach ($msgArray as $id => $msg) {
                $this->objetoMensagens->adicionaMensagem($msg,$tipo);
            }
        }

        /**
         * setLarguraCaixaMensagens
         *
         * Configura a largura da caixa de mensagens
         * @param largura string
         * @return void
         * @access public
         */
         function setLarguraCaixaMensagens($largura)
         {
            $this->objetoMensagens->setLargura($largura);
         }

         /**
         * setAlturaCaixaMensagens
         *
         * Configura a altura da caixa de mensagens
         * @param altura string
         * @return void
         * @access public
         */
         function setAlturaCaixaMensagens($altura)
         {
            $this->objetoMensagens->setAltura($altura);
         }

        /**
         * adicionaBotao
         *
         * Adiciona um botão ao array de botões
         * @param string texto Texto do botão
         * @param string nomeFigura Nome do arquivo com a figura
         * @param string nomeInput Nome do objeto input do formulário
         * @param string tipoBotao Tipo do botão [reset|submit|button]
         * @param string evento Evento a ser disparado
         * @param boolean exibe Flag indicando se deve ser exibido
         * @param integer tamanhoFonte Tamanho da fonte a ser aplicada no texto do botão
         * @param integer alturaBotao Altura do botão a ser exibido
         * @param integer larguraBotao Largura do botão a ser exibido
         * @return void
         * @access public
         */
        function adicionaBotao($texto, $nomeFigura, $nomeInput='botao', $tipoBotao='submit', $evento='', $exibe=true, $tamanhoFonte='', $alturaBotao=0, $larguraBotao=0) {
            if (trim($tamanhoFonte) == '') {
                $tamanhoFonte = $this->tamanhoFonteBotao;
            }

            if ($alturaBotao == 0) {
                $alturaBotao = 25;
            }

            if ($larguraBotao == 0) {
                $larguraBotao = 90;
            }

            $this->botao[$texto]['texto'] = $texto;
            $this->botao[$texto]['tamanhoFonte'] = $tamanhoFonte;
            $this->botao[$texto]['nomeInput'] = $nomeInput;
            $this->botao[$texto]['nomeFigura'] = $nomeFigura;
            $this->botao[$texto]['tipoBotao'] = $tipoBotao;
            $this->botao[$texto]['evento'] = $evento;
            $this->botao[$texto]['exibe'] = $exibe;
            $this->botao[$texto]['alturaBotao'] = $alturaBotao;
            $this->botao[$texto]['larguraBotao'] = $larguraBotao;
        }

        /**
         * habilitaAba
         *
         * Habilita uma aba
         * @param string nomeAba Nome da aba
         * @return void
         * @access public
         */
        function habilitaAba($nomeAba)
        {
            $this->listaAbas[$nomeAba]['habilitada'] = true;
        }

        /**
         * desabilitaAba
         *
         * Desabilita uma aba
         * @param string nomeAba Nome da aba
         * @param string msg Mensagem de aviso da aba desabilitada
         * @return void
         * @access public
         */
        function desabilitaAba($nomeAba,$msg)
        {
            $this->listaAbas[$nomeAba]['habilitada'] = false;

            if($msg == "")
            {
                $this->msg = "Esta aba está desabilitada";
            }
            else
            {
                $this->msg = $msg;
            }
        }

        /**
         * setaAbaPadrao
         *
         * Seta o nome da Aba Padrão
         * @param string nomeAba Nome da aba
         * @return void
         * @access public
         */
        function setaAbaPadrao($nomeAba = '')
        {
            $this->abaPadrao = $nomeAba;
        }

        /**
         * setaExibicaoBotao
         *
         * Seta o botão para ser ou não exibido
         * na exibição da Barra Padrão
         * @param string nomeBotao
         * @param bollean valor
         * @return void
         * @access public
         */
        function setaExibicaoBotao($nomeBotao, $valor)
        {
            if (isset($this->botao[$nomeBotao])) {
                $this->botao[$nomeBotao]['exibe'] = $valor;
            }
        }

        /**
         * setaExibicaoBarraBotoes
         *
         * Seta a propriedade que indica se a barra de botões
         * deve ou não ser exibida.
         * @param bollean valor
         * @return void
         * @access public
         */
        function setaExibicaoBarraBotoes($valor=true)
        {
            $this->exibeBarraBotoes = $valor;
        }

        /**
         * setaTipoBotao
         *
         * Seta o tipo do botão
         * @param string nomeBotao
         * @param string tipo
         * @param string evento
         * @return void
         * @access public
         */
        function setaTipoBotao($nomeBotao, $tipo, $evento)
        {
            if (isset($this->botao[$nomeBotao])) {
                $this->botao[$nomeBotao]['tipoBotao'] = $tipo;
                $this->botao[$nomeBotao]['evento'] = $evento;
            }
        }

        /**
         * adicionaElemento
         *
         * Adiciona um elemento a aba. Utilizando este método NÃO PODE
         * ser informado a posição do elemento. Caso haja necessidade disto
         * deve ser utilizado o método posicionaElemento.
         * @param string nomeAba Nome da aba onde o elemento será adicionado
         * @param mixed elemento Elemento a ser adicionado.
         * @param string tipoElemento Tipo do elemento a ser adicionado
         * @param string tituloAba Título da aba
         * @return void
         * @access public
         */
        function adicionaElemento($nomeAba, $elemento, $tipoElemento='packageForm', $tituloAba="")
        {
            $this->adicionaAba($nomeAba, $tituloAba);
            $this->listaAbas[$nomeAba]['objeto']->adicionaElemento($elemento, $tipoElemento);
        }

        /**
         * posicionaElemento
         *
         * Posiciona um elemento na aba. Utilizando este método DEVE
         * ser informado a posição do elemento. Caso não haja necessidade disto
         * deve ser utilizado o método adicionaElemento.
         * @param string nomeAba Nome da aba onde na qual o elemento será adicionado
         * @param integer linha Linha na qual o elemento será adicionado
         * @param integer coluna Coluna na qual o elemento será adicionado
         * @param mixed elemento Elemento a ser adicionado
         * @param string tipoElemento Tipo do elemento a ser adicionado.
         * @param string estilo Estilo a ser aplicado
         * @param integer rowspan Número de linhas a serem expandidas
         * @param integer colspan Número de colunas a serem expandidas
         * @param string  tituloAba Título a ser dado para Aba
         * @return void
         * @access public
         */
        function posicionaElemento($nomeAba, $linha, $coluna, $elemento, $tipoElemento='packageForm', $estilo="", $rowspan=0, $colspan=0, $tituloAba="")
        {
            $this->adicionaAba($nomeAba, $tituloAba);
            $this->listaAbas[$nomeAba]['objeto']->posicionaElemento($linha, $coluna, $elemento, $tipoElemento, $estilo, $rowspan, $colspan);
        }

        /**
         * exibe
         *
         * Gera o código html e o exibe
         * @return void
         * @access public
         */
        function exibe()
        {
            //dump($this->listaAbas);
            $this->geraCodigo();
            echo $this->codigoHTML;
        }

        /**
         * geraCodigo
         *
         * Apenas gera o código html.
         * @return void
         * @access private
         */
        function geraCodigo()
        {
            // Definindo algumas variáveis para controle interno
            $arrayCodigoAba = array();
            $codigoTDs = "";
            $htmlTitulo = "<p style='font-size: 14px; text-align: center; vertical-align: middle;'><b><label class='xptitle'>".$this->titulo."</label></b></p>";
            $estiloCelulaTitulo = "style='vertical-align: middle; text-align: center;'";

            $alturaLinhaTitulo = 25;
            $alturaLinhaLegenda = 25;

            if ($this->legenda === false) {
                $alturaLinhaLegenda = 0;
            }

            if (trim($this->titulo) == "") {
                $alturaLinhaTitulo = 0;
            }

            if ($this->qtdAbas > 1) {
                $alturaLinhaAba = 25;
                $modoDisplay = 'none';
                $codigoJavaScriptFuncoes = $this->retornaFuncoesJavaScript('funcoes'); // Gerando o código Javascript
                $codigoJavaScriptFuncoes .= "\n".$this->retornaFuncoesJavaScript('avisos'); // Gerando o código Javascript
                $codigoJavaScriptFinal = $this->retornaFuncoesJavaScript('final'); // Gerando o código Javascript
            } else {
                $alturaLinhaAba = 0;
                $modoDisplay = 'inline';
                $codigoJavaScriptFuncoes = $this->retornaFuncoesJavaScript('avisos'); // Gerando o código Javascript
                $codigoJavaScriptFinal = "";
            }

            if ($this->exibeLegenda == true) {
                $alturaLinhaTitulo = $alturaLinhaTitulo + $alturaLinhaLegenda;
                $htmlTitulo .= $this->legenda;
            }

            if ($this->exibeBarraBotoes == true) {
                $alturaLinhaBarraPadrao = 30;
            } else {
                $alturaLinhaBarraPadrao = 0;
            }

            // Definindo a altura do corpo, onde será exibido o conteúdo
            $alturaCorpo = ($this->altura - $alturaLinhaAba - $alturaLinhaBarraPadrao - $alturaLinhaTitulo);

            // Gerando o código HTML do conteúdo de cada Aba
            foreach ($this->listaAbas as $nomeAbaAtual => $abaAtual) {
                $titulo = $abaAtual['titulo'];
                $objeto = $abaAtual['objeto'];
                $arrayCodigoAba[$nomeAbaAtual] = $objeto->retornaCodigo();
                if ($this->qtdAbas > 1) {
                    $codigoTDs .= "\n            <td class='aba_nao_selecionada' id='".$nomeAbaAtual."'  onClick=\"alternarAbas('".$nomeAbaAtual."','div_".$nomeAbaAtual."')\">".$titulo."</td>";
                }
                unset($titulo,$objeto);
            }

            $this->codigoHTML .= "\n".$codigoJavaScriptFuncoes;

            // Gerando o código HTML do formulário
            $this->codigoHTML .= "\n    <center>";

            $this->url = htmlspecialchars(strip_tags($this->url),ENT_QUOTES) ;

            if ($this->geraFormulario===true) {

                $this->codigoHTML .= "\n    <form class=cadastro";

                if ($this->enviaArquivo === true) {
                    $this->codigoHTML .= " enctype='multipart/form-data'";
                }

                $this->codigoHTML .= " name='".$this->nome."' action='".$this->url."' method='".$this->metodo."'>";
            }

            $this->codigoHTML .= "\n    <table class='cadastro' cellspacing='10' cellpadding='0' border='0' style='border: 1px dotted #999; width: ".$this->largura."px; height: ".$this->altura."px;'>";

            $this->codigoHTML .= "\n        <tr style='width: ".$this->largura."px; height: ".$alturaLinhaTitulo."px;}'>";
            $this->codigoHTML .= "\n            <td colspan='".$this->qtdAbas."' class='cadastro_cabecalho' style='vertical-align: middle; text-align: center;'>".$htmlTitulo."</td>";
            $this->codigoHTML .= "\n        </tr>";

            if ($alturaLinhaAba > 0) {
                $this->codigoHTML .= "\n        <tr style='width: ".$this->largura."px; height: ".$alturaLinhaAba."px;'>";
                $this->codigoHTML .= $codigoTDs;
                $this->codigoHTML .= "\n        </tr>";
            }

            //$this->codigoHTML .= "\n        <tr class='conteudo' style='{width: ".$this->largura."px; height: ".$alturaCorpo."px; overflow: auto;}'>";
            $this->codigoHTML .= "\n        <tr style='width: ".$this->largura."px; height: ".$alturaCorpo."px; overflow: auto;'>";
            //$this->codigoHTML .= "\n            <td class='conteudo' colspan='".$this->qtdAbas."' valign='top'>";
            $this->codigoHTML .= "\n            <td class='mostraCorFundo' colspan='".$this->qtdAbas."' valign='top'>";

            foreach($arrayCodigoAba as $nomeAbaAtual => $codigoAbaHTML) {
                $this->codigoHTML .= "\n                <div id='div_".$nomeAbaAtual."' style='display: ".$modoDisplay."; height: ".$alturaCorpo."px;'>";
                $this->codigoHTML .= $codigoAbaHTML;
                $this->codigoHTML .= "\n                </div>";
            }

            $this->codigoHTML .= "\n            <td>";
            $this->codigoHTML .= "\n        </tr>";

            if ($this->exibeBarraBotoes == true) {

                $this->codigoHTML .= "\n        <tr class='naoVisivelImpressao' style='width: ".$this->largura."px; height: ".$alturaLinhaBarraPadrao."px;'>";
                $this->codigoHTML .= "\n            <td class='tb-conteudo' colspan=".$this->qtdAbas." valign='top' style='border-top: 1px dotted #999; padding:5px; vertical-align: middle;'>";

                foreach ($this->botao as $chaveBotao => $dadosBotao) {
                    extract($dadosBotao);
                    $estiloBotao = "height: ".$alturaBotao."px; width: ".$larguraBotao."px;";
                    if ($exibe === true) {
                        $this->codigoHTML .= "\n <input class='BUTTON_ABAS' name='".$nomeInput."' value='".$texto."' style='font-size: ".$tamanhoFonte."pt; float:left; margin-left:5px; ".$estiloBotao."' type='".$tipoBotao."' ".$evento.">";
                    }
                    unset($texto, $tamanhoFonte, $nomeInput, $nomeFigura, $exibe, $alturaBotao, $larguraBotao);
                }

                $this->codigoHTML .= "\n            </td>";
                $this->codigoHTML .= "\n        </tr>";
            }

            $this->codigoHTML .= "\n    </table>";

            if ($this->geraFormulario===true) {
                $this->codigoHTML .= "\n    </form>";
            }

            if ($this->objetoMensagens->retornaQtdMensagens() > 0) {
                $this->objetoMensagens->exibeMensagem('', "msg".$this->nome, '');
            }

            $this->codigoHTML .= "\n    </center>";
            $this->codigoHTML .= "\n".$codigoJavaScriptFinal;
        }

        /**
         * retornaCodigo
         *
         * Gera o código html e o retorna em uma string
         * @return string
         * @access public
         */
        function retornaCodigo()
        {
            $this->geraCodigo();
            return $this->codigoHTML;
        }

        /**
         * retornaFuncoesJavaScript
         *
         * Retorna o código javaScript utilizado por esta classe
         * @param string tipo Indica o tipo de script a ser retornado
         * @return string
         * @access private
         */
        function retornaFuncoesJavaScript($tipo)
        {
            $codigoJs = "";
            $contador = 0;

            switch ($tipo) {
            case "funcoes":
                $codigoJs .= "\n<script language='JavaScript'>";
                $codigoJs .= "\n    function setaAba(menu,conteudo,habilitada)";
                $codigoJs .= "\n    {";
                $codigoJs .= "\n        this.menu = menu;";
                $codigoJs .= "\n        this.conteudo = conteudo;";
                $codigoJs .= "\n        this.habilitada = habilitada;";
                $codigoJs .= "\n    }";
                $codigoJs .= "\n";
                $codigoJs .= "\n    var listaAbas = new Array();";

                foreach ($this->listaAbas as $nomeAbaAtual => $abaAtual) {
                    if ($this->listaAbas[$nomeAbaAtual]['habilitada'] === true) {
                        $codigoJs .= "\n    listaAbas[".$contador."] = new setaAba('".$nomeAbaAtual."','div_".$nomeAbaAtual."', true);";
                    } else {
                        $codigoJs .= "\n    listaAbas[".$contador."] = new setaAba('".$nomeAbaAtual."','div_".$nomeAbaAtual."', false);";
                    }
                    $contador = $contador + 1;
                }

                $codigoJs .= "\n    function alteraSituacaoAba(idMenu,idConteudo,valor)";
                $codigoJs .= "\n    {";
                $codigoJs .= "\n        var menu;";
                $codigoJs .= "\n        var conteudo;";
                $codigoJs .= "\n        for (i=0; i<listaAbas.length; i++)";
                $codigoJs .= "\n        {";
                $codigoJs .= "\n            menu = document.getElementById(listaAbas[i].menu);";
                $codigoJs .= "\n            conteudo = document.getElementById(listaAbas[i].conteudo)";
                $codigoJs .= "\n            if ((listaAbas[i].menu == idMenu) && (listaAbas[i].conteudo == idConteudo)) {";
                $codigoJs .= "\n                listaAbas[i].habilitada = valor;";
                $codigoJs .= "\n            }";
                $codigoJs .= "\n        }";
                $codigoJs .= "\n    }";
                $codigoJs .= "\n";
                $codigoJs .= "\n    function alternarAbas(idMenu,idConteudo)";
                $codigoJs .= "\n    {";
                $codigoJs .= "\n        var msg = \"".$this->msg."\";";
                $codigoJs .= "\n        var menu;";
                $codigoJs .= "\n        var conteudo;";
                $codigoJs .= "\n        var menuAtual;";
                $codigoJs .= "\n        var conteudoAtual;";
                $codigoJs .= "\n        for (i=0; i<listaAbas.length; i++) { ";
                $codigoJs .= "\n            menu = document.getElementById(listaAbas[i].menu);";
                $codigoJs .= "\n            conteudo = document.getElementById(listaAbas[i].conteudo)";
                $codigoJs .= "\n            if (menu.className == 'aba_selecionada') { ";
                $codigoJs .= "\n                menuAtual = listaAbas[i].menu;";
                $codigoJs .= "\n                conteudoAtual = listaAbas[i].conteudo;";
                $codigoJs .= "\n            }";
                $codigoJs .= "\n        }";
                $codigoJs .= "\n";
                $codigoJs .= "\n        for (i=0; i<listaAbas.length; i++) { ";
                $codigoJs .= "\n        ";
                $codigoJs .= "\n            menu = document.getElementById(listaAbas[i].menu);";
                $codigoJs .= "\n            conteudo = document.getElementById(listaAbas[i].conteudo)";
                $codigoJs .= "\n            if ((listaAbas[i].menu == idMenu) && (listaAbas[i].conteudo == idConteudo)) {";
                $codigoJs .= "\n                if (listaAbas[i].habilitada == true) {";
                $codigoJs .= "\n                    menu.className = 'aba_selecionada';";
                $codigoJs .= "\n                    conteudo.style.display = 'inline';";
                $codigoJs .= "\n                } else {";
                $codigoJs .= "\n                    alert(msg);";
                $codigoJs .= "\n                    menu = document.getElementById(menuAtual);";
                $codigoJs .= "\n                    conteudo = document.getElementById(conteudoAtual)";
                $codigoJs .= "\n                    menu.className = 'aba_selecionada';";
                $codigoJs .= "\n                    conteudo.style.display = 'inline';";
                $codigoJs .= "\n                    return;";
                $codigoJs .= "\n                }";
                $codigoJs .= "\n            } else {";
                $codigoJs .= "\n                menu.className = 'aba_nao_selecionada';";
                $codigoJs .= "\n                conteudo.style.display = 'none';";
                $codigoJs .= "\n            }";
                $codigoJs .= "\n        }";
                $codigoJs .= "\n    }";
                $codigoJs .= "\n</script>";
                break;
            case "avisos":
                $codigoJs .= "\n<script language='javaScript'>";
                $codigoJs .= "\n    function exibeMensagens(idDivMsg)";
                $codigoJs .= "\n    {";
                $codigoJs .= "\n       document.getElementById(idDivMsg).style.display = 'inline';";
                $codigoJs .= "\n    }";
                $codigoJs .= "\n</script>";
                break;
            case "final":
                $codigoJs .= "\n    <script language='javaScript'>";
                $codigoJs .= "\n        alternarAbas('".$this->abaPadrao."','div_".$this->abaPadrao."');";
                $codigoJs .= "\n    </script>";
                break;
            }
            return $codigoJs;
        }
    }

?>