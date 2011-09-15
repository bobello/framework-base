<?php

/**
*   Pacote para criação de Tabelas em JavaScript
*
*   PHP version 4
*
*   Nota: Essa classe gera um código javascrit,
*   onde que criará uma grid pode pode ser manipulável.
*   Utiliza-se o framework javascript EXTJS, que deve ser
*   chamado para que funcione a grid
*
*   @author Henrique Girardi dos Santos
*   @version 0.5
*
*/

/**
*   arquivosJs
*
*   Classe para escrever os arquivos javascript necessários para a criação da gridJs.
*   @package gridJs
*   @author Henrique Girardi dos Santos
*/
class arquivosJs
{

    /**
    * Variável para armazenar o inicio dos endereços de onde os arquivos estão.
    * @var string
    * @access private
    */
    var $endereco = "";

    /**
    *   arquivosJs
    *
    *   Método construtor. Inicializa a variavel 'endereco'.
    *   <br>Deve ser chamado no arquivo .php. Retorna um array com o caminho dos aquivos.
    *   @return array
    *   @access public
    */
    function arquivosJs()
    {
        global $SISCONF;
        $this->endereco = $SISCONF['SIS']['INTRANET']['HOST'];
    }

    /**
    *   retornaCSS
    *
    *   Cria um array com os endereços arquivos javascript necessários para o funcionamento da gridJs.
    *   <br>Deve ser chamado no arquivo .php. Retorna um array com o caminho dos aquivos.
    *   @return array
    *   @access public
    */
    function retornaJs()
    {
        $js = array(
                $this->endereco."extjs/adapter/yui/yui-utilities.js",
                $this->endereco."extjs/adapter/yui/ext-yui-adapter.js",
                $this->endereco."extjs/ext-all.js",
                );

        return $js;
    }
}


/**
*   arquivosCSS
*
*   Classe para escrever os arquivos CSS necessários para a criação da gridJs.
*   @package gridJs
*   @author Henrique Girardi dos Santos
*/
class arquivosCSS
{

    /**
    * Variável para armazenar o inicio dos endereços de onde os arquivos estão.
    * @var string
    * @access private
    */
    var $endereco = "";


    /**
    *   arquivosCSS
    *
    *   Método construtor. Inicializa a variavel 'endereco'.
    *   <br>Deve ser chamado no arquivo .php. Retorna um array com o caminho dos aquivos.
    *   @return void
    *   @access public
    */
    function arquivosCSS()
    {
        global $SISCONF;
        $this->endereco = $SISCONF['SIS']['INTRANET']['HOST'];
    }

    /**
    *   retornaCSS
    *
    *   Cria um array com os endereços arquivos CSS necessários para o funcionamento da gridJs.
    *   <br>Deve ser chamado no arquivo .php. Retorna um array com o caminho dos aquivos.
    *   @param string
    *   @return array
    *   @access public
    */
    function retornaCSS( $tema="" )
    {
        $css = array( $this->endereco."extjs/resources/css/ext-all.css");

        switch( $tema ) {
        case "aero":
            array_push( $css, $this->endereco."extjs/resources/css/ytheme-aero.css");
            break;

        case "gray":
            array_push( $css, $this->endereco."extjs/resources/css/ytheme-gray.css");
            break;

        case "vista":
            array_push( $css, $this->endereco."extjs/resources/css/ytheme-vista.css");
            break;
        }

        return $css;
    }
}

/**
*   gridJs
*
*   Classe para a criação da tabela em javascript
*   @package gridJs
*   @author Henrique Girardi dos Santos
*/
class gridJs
{
    /**
    * Armazenar o nome da grid
    * @var string
    * @access private
    */
    var $nomeGrid;

    /**
    * Armazenar a url que fará a busca JSON para montar a grid
    * @var string
    * @access private
    */
    var $url;

    /**
    * Armazenar os títulos do cabeçalho da grid
    * @var array
    * @access private
    */
    var $cabecalho = array();

    /**
    * Armazenar os alinhamentos de cada coluna da grid
    * @var array
    * @access private
    */
    var $alinhamento = array();

    /**
    * Armazenar o nome dos campos do SQL para colocar na grid
    * @var array
    * @access private
    */
    var $arrayCamposBanco = array();

    /**
    * Armazenar o valor das larguras de cada coluna da grid
    * @var array
    * @access private
    */
    var $arrayLarguraColuna = array();

    /**
    * Armazenar os ids dos campos que serão parametros da URL da busca do SQL
    * @var array
    * @access private
    */
    var $parametros = array();

    /**
    * Armazenar os nome dos campos que serão parametros da URL da busca do SQL
    * @var array
    * @access private
    */
    var $campoParametros = array();

    /**
    * Armazenar o nome do campo que tera a coluna expandida quando a grid sofrer alteração do tamanho.
    * O campo deve conter o nome do campo como na pesquisa SQL
    * @var string
    * @access private
    */
    var $campoExpandido;

    /**
    * Armazenar a opção de bloquear as colunas
    * @var boolean
    * @access private
    */
    var $desabilitaBloqueioColunas = false;

    /**
    *   gridJs
    *
    *   Método construtor. Inicializa o nome da grid e a url
    *   @param string nomeGrid
    *   @param string url
    *   @return void
    *   @access public
    */
    function gridJs( $nomeGrid, $url )
    {
        $this->nomeGrid = $nomeGrid;
        $this->url = $url;
    }

    /**
    *   adicionaCabecalho
    *
    *   Define o cabecalho da grid
    *   @param array arrayCabecalho
    *   @return void
    *   @access public
    */
    function adicionaCabecalho( $arrayCabecalho )
    {
        if ( is_array( $arrayCabecalho ) ) {
            $this->cabecalho = $arrayCabecalho;
        } else {
            echo "<br />Problema encontrado na função <b>adicionaCabecalho()</b>:<br />O seu parâmentro deve ser um array!<br />";
        }
    }

    /**
    *   adicionaAlinhamento
    *
    *   Define o alinhamento das linhas da grid
    *   @param array arrayAlinhamento
    *   @return void
    *   @access public
    */
    function adicionaAlinhamento( $arrayAlinhamento )
    {
        if ( is_array( $this->cabecalho ) ) {
            if ( is_array( $arrayAlinhamento ) ) {
                if ( count($this->cabecalho) != count( $arrayAlinhamento ) ) {
                    echo "<br />Problema encontrado na função <b>alinhamento()</b>:<br />Número diferentes de colunas encontrada entre o cabeçalho e o alinhamento!<br />";
                } else {
                    $this->alinhamento = $arrayAlinhamento;
                }
            } else {
                echo "<br />Problema encontrado na função <b>alinhamento()</b>:<br />O seu parâmentro deve ser um array!<br />";
            }
        } else {
            echo "<br />Problema encontrado na função <b>alinhamento()</b>:<br />O cabeçalho precisa ser definido antes!<br />";
        }
    }

    /**
    *   adicionaCamposBanco
    *
    *   Define a variável $arrayCamposBanco. Nela fica armazenada o nome dos campos como vem da busca SQL
    *   @param array arrayCamposBanco
    *   @return void
    *   @access public
    */
    function adicionaCamposBanco( $arrayCamposBanco )
    {
        if ( is_array( $this->cabecalho ) ) {
            if ( is_array( $arrayCamposBanco ) ) {
                if ( count($this->cabecalho) != count( $arrayCamposBanco ) ) {
                    echo "<br />Problema encontrado na função <b>adicionaCamposBanco()</b>:<br />Número diferentes de colunas encontrada entre o cabeçalho e os campos!<br />";
                } else {
                    $this->arrayCamposBanco = $arrayCamposBanco;
                }
            } else {
                echo "<br />Problema encontrado na função <b>adicionaCamposBanco()</b>:<br />O seu parâmentro deve ser um array!<br />";
            }
        } else {
            echo "<br />Problema encontrado na função <b>adicionaCamposBanco()</b>:<br />O cabeçalho precisa ser definido antes!<br />";
        }
    }

    /**
    *   adicionaLarguraColuna
    *
    *   Define a variável $arrayLarguraColuna. Recebe um array com as larguras de cada coluna da grid
    *   @param array arrayLarguraColuna
    *   @return void
    *   @access public
    */
    function adicionaLarguraColuna( $arrayLarguraColuna )
    {
        if ( is_array( $this->cabecalho ) ) {
            if ( is_array( $arrayLarguraColuna ) ) {
                if ( count($this->cabecalho) != count( $arrayLarguraColuna ) ) {
                    echo "<br />Problema encontrado na função <b>adicionaLarguraColuna()</b>:<br />Número diferentes de colunas encontrada entre o cabeçalho e os parâmetros das larguras das colunas!<br />";
                } else {
                    $this->arrayLarguraColuna = $arrayLarguraColuna;
                }
            } else {
                echo "<br />Problema encontrado na função <b>adicionaLarguraColuna()</b>:<br />O seu parâmentro deve ser um array!<br />";
            }
        } else {
            echo "<br />Problema encontrado na função <b>adicionaLarguraColuna()</b>:<br />O cabeçalho precisa ser definido antes!<br />";
        }
    }

    /**
    *   expandirColuna
    *
    *   Define a variável $campoExpandido. Recebe um array com as larguras de cada coluna da grid
    *   @param array campoExpandido
    *   @return void
    *   @access public
    */
    function expandirColuna( $campoExpandido ) {
        if ( is_string( $campoExpandido ) ) {
            if ( $campoExpandido != "" ) {
                $this->campoExpandido = $campoExpandido;
            } else {
                echo "<br />Problema encontrado na função <b>expandirColuna()</b>:<br />O parâmetro passado não pode ser nulo!<br />";
            }
        } else {
            echo "<br />Problema encontrado na função <b>expandirColuna()</b>:<br />O parâmetro passado deve ser tipo 'String'!<br />";
        }
    }

    /**
    *   desabilitaBloqueioColunas
    *
    *   Define se as colunas da grid poderão ou não ficar bloqueadas.
    *   @return void
    *   @access public
    */
    function desabilitaBloqueioColunas()
    {
        $this->desabilitaBloqueioColunas = true;
    }

    /**
    *   adicionaParametros
    *
    *   Define os parâmetros da url, onde que 'arrayNomeParametros' recebe o array com o nome das variaveis php
    *       e 'arrayParametros' recebe os valores que elas devem receber
    *   @param array arrayNomeParametros
    *   @param array arrayParametros
    *   @return void
    *   @access public
    */
    function adicionaParametros( $arrayNomeParametros, $arrayParametros )
    {
        if ( ( is_array( $arrayParametros ) ) && ( is_array( $arrayNomeParametros ) ) ){
            if ( $this->url != "" ) {
                if ( count($arrayNomeParametros) == count( $arrayParametros ) ) {
                    $this->parametros = $arrayParametros;
                    $this->campoParametros = $arrayNomeParametros;
                } else {
                    echo "<br />Problema encontrado na função <b>adicionaParametros()</b>:<br />Número doferentes de campos encontrados nos parâmetros dos arrays!<br />";
                }
            } else {
                echo "<br />Problema encontrado na função <b>adicionaParametros()</b>:<br />A url(parametro 2 da classe) não foi informada!<br />";
            }
        } else {
            echo "<br />Problema encontrado na função <b>adicionaParametros()</b>:<br />Os seu parâmentros devem ser arrays!<br />";
        }
    }

    /**
    *   gera
    *
    *   Retorna o código para ser inserido dentro da tag script do programa para gerar a grid em javascript
    *   @return string
    *   @access public
    */
    function gera()
    {
        $js = "";
        $js .= "\n var ".$this->nomeGrid." = function(){";

        $js .= "\n     var ds = new Ext.data.Store({";
        $js .= "\n";
        $js .= "\n         proxy: new Ext.data.HttpProxy({";

        $end = "";
        for( $i=0; $i < count( $this->campoParametros ); $i++ ) {
            $end .= "&".$this->campoParametros[$i]."='+Ext.get('".$this->parametros[$i]."').dom.value+'";
        }
        $end .= "'";

        $js .= "\n             url: '".$this->url.$end;
        $js .= "\n         }),";
        $js .= "\n";
        $js .= "\n         reader: new Ext.data.JsonReader({";
        $js .= "\n             root: 'results',";
        $js .= "\n             totalProperty: 'total',";
        $js .= "\n             id: 'id'";
        $js .= "\n         }, [ ";

        foreach( $this->arrayCamposBanco as $chave => $valorCampo ) {
            if ( $chave > 0 ) $js .= ", ";
            $js .= "\n             {name: '".$valorCampo."', mapping: '".$valorCampo."'}";
        }

        $js .= "\n         ])";
        $js .= "\n     });";
        $js .= "\n";
        $js .= "\n    var cm = new Ext.grid.ColumnModel([{";

        for ( $i=0;$i<count($this->cabecalho);$i++ ) {
            if ( $i>0 ) {
                $js .= "\n        },{";
            }
            if ( $this->campoExpandido == $this->arrayCamposBanco[$i] ) {
                $js .= "\n           id: '".$this->campoExpandido."',";
            }
            $js .= "\n           header: '".$this->cabecalho[$i]."',";
            $js .= "\n           dataIndex: '".$this->arrayCamposBanco[$i]."',";
            $js .= "\n           width: ".$this->arrayLarguraColuna[$i].", ";
            $js .= "\n           align: '".$this->alinhamento[$i]."'";
        }

        $js .= "\n        }]);";
        $js .= "\n";
        $js .= "\n    var grid = new Ext.grid.Grid('".$this->nomeGrid."', {";
        $js .= "\n        ds: ds,";
        $js .= "\n        cm: cm,";
        if ( $this->campoExpandido != "") {
            $js .= "\n        autoExpandColumn: '".$this->campoExpandido."',";
        }
        if ( $this->desabilitaBloqueioColunas == true) {
            $js .= "\n        enableColLock: false";
        }
        $js .= "\n    });";
        $js .= "\n";
        $js .= "\n    grid.render();";
        $js .= "\n    ds.load();";
        $js .= "\n}";

        return $js;
    }
}

?>