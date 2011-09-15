<?php
GLOBAL $SISCONF;
GLOBAL $PHP_SELF;

$PHP_SELF = $_SERVER["PHP_SELF"];
$host = "http://10.10.10.33:82/framework_base/sistema/";
$path = "D:/DESENVOLVIMENTO_LOCAL/framework_base/sistema/";

$SISCONF['SIS']['PATH_SGF'] = "/var/www/sgf/";
$SISCONF['SIS']['PATH_SGF_ARQUIVOS'] = $SISCONF['SIS']['PATH_SGF']."arquivos/";


$SISCONF['SIS']['COMUM']['MENSAGEM']['CONEXAO'] = '<br />Por Favor, entre em contato com o administrador';

$SISCONF['SIS']['BANCO_PADRAO'] = "sgc_novo";
$SISCONF['SIS']['BANCOS_DE_DADOS']['SGC'] = "sgc_novo";

//Seção de configuração dos usuários logados
$SISCONF['SESSAO']['USUARIO']['ID'] = (isset($_SESSION["userid"]) ? $_SESSION["userid"] : "");

$SISCONF['SESSAO']['USUARIO']['USUARIO'] = (isset($_SESSION["user"]) ? $_SESSION["user"] : "");
$SISCONF['SESSAO']['USUARIO']['NOME'] = (isset($_SESSION["usernome"]) ? $_SESSION["usernome"] : "");
$SISCONF['SESSAO']['USUARIO']['SENHA'] = (isset($_SESSION["pwd"]) ? $_SESSION["pwd"] : "");
$SISCONF['SESSAO']['USUARIO']['TEMA'] = "default";
$SISCONF['SESSAO']['USUARIO']['IP'] = getenv("REMOTE_ADDR");
$SISCONF['ARQUIVO'] = $PHP_SELF;

$SISCONF['PAGINA']['LINK'] = getenv("HTTP_REFERER");
$SISCONF['PAGINA']['VARS'] = '';

###############################################
##  SEÇÃO PARA AUTENTICAÇÃO ENVIO DE E-MAIL PARA O SERVIDOR  ##
###############################################
$SISCONF['SMTP_MAIL']['HOST'] = "mail.unilasalle.edu.br";
$SISCONF['SMTP_MAIL']['USERNAME'] = "";
$SISCONF['SMTP_MAIL']['PASSWORD'] = "";
$SISCONF['SMTP_MAIL']['SMTP_AUTH'] = false;
###############################################

//seção de configuração dos Targets de programas.
$SISCONF['SIS']['TARGET']['MENU']     = " target=\"menu\" ";
$SISCONF['SIS']['TARGET']['PROGRAMA'] = " target=\"corpo\" ";

//seção de configuração do COMUM (DOMÍNIOS)
$SISCONF['SIS']['COMUM']['DOMAIN'] = $host;      

//seção de configuração do COMUM
$SISCONF['SIS']['COMUM']['HOST']    = $host;
$SISCONF['SIS']['COMUM']['PATH']    = "comum/";

$SISCONF['SIS']['COMUM']['SESSAO']  = $SISCONF['SIS']['COMUM']['PATH']."sessao/";
$SISCONF['SIS']['COMUM']['BANCO']   = $SISCONF['SIS']['COMUM']['PATH']."banco/";
$SISCONF['SIS']['COMUM']['TELA']    = $SISCONF['SIS']['COMUM']['PATH']."tela/";
$SISCONF['SIS']['COMUM']['UTIL']    = $SISCONF['SIS']['COMUM']['PATH']."util/";
$SISCONF['SIS']['COMUM']['ERRO']    = $SISCONF['SIS']['COMUM']['PATH']."erro/";
$SISCONF['SIS']['COMUM']['TEMAS']   = $SISCONF['SIS']['COMUM']['HOST']."modulos/temas/";
$SISCONF['SIS']['COMUM']['TEMAS2']  = $SISCONF['SIS']['COMUM']['PATH']."/modulos/temas/imagens/";
$SISCONF['SIS']['COMUM']['IMAGENS'] = $SISCONF['SIS']['COMUM']['HOST']."imagens/";
$SISCONF['SIS']['COMUM']['IMAGENS_TEMA'] = $SISCONF['SIS']['COMUM']['HOST']."modulos/temas/comum/images/";
$SISCONF['SIS']['COMUM']['FOTOS']['HOST'] = "/temp/";
$SISCONF['SIS']['COMUM']['BASE_PHP'] = $host."modulos/";
//seção de configuração do INTRANET
$SISCONF['SIS']['INTRANET']['HOST']  = $host;
$SISCONF['SIS']['INTRANET']['PATH']  = "/phpclasses/";

$SISCONF['SIS']['INTRANET']['NEGOCIO']   = $SISCONF['SIS']['INTRANET']['PATH']."negocio/";
$SISCONF['SIS']['INTRANET']['INTERFACE'] = $SISCONF['SIS']['INTRANET']['PATH']."interface/";

$SISCONF['SIS']['INTRANET']['THEMA']['HOST']  = $SISCONF['SIS']['INTRANET']['HOST']."modulos/temas/default/";
$SISCONF['SIS']['INTRANET']['THEMA']['PATH']  = $path."/modulos/temas/default/";
$SISCONF['SIS']['INTRANET']['PROGRAMA']  = $path."/modulos/";
$SISCONF['SIS']['HOST'] = $path;

//Configurações do DESENV (BD de teste)
$SISCONF['DB']['SISTEMA']['BANCO'] = 'MYSQL';
$SISCONF['DB']['SISTEMA']['USER_CONNECT'] = 'REQUEST';
$SISCONF['DB']['SISTEMA']['USUARIO'] = '';
$SISCONF['DB']['SISTEMA']['SENHA'] = '';
$SISCONF['DB']['SISTEMA']['HOST'] = 'localhost';
$SISCONF['DB']['SISTEMA']['BASE'] = "sistema_gerenciador";

//Configurações do PARSER de BD para Matrizes
$SISCONF['DB']['MATRIX']['BANCO'] = 'MATRIX';
$SISCONF['DB']['MATRIX']['USER_CONNECT'] = 'DEFAULT';
$SISCONF['DB']['MATRIX']['USUARIO'] = 'x';
$SISCONF['DB']['MATRIX']['SENHA'] = 'x';
$SISCONF['DB']['MATRIX']['HOST'] = 'x';
$SISCONF['DB']['MATRIX']['BASE'] = 'x';

//Configuração do PATH padrão para os relatórios .PDF
$SISCONF['SIS']['RELATORIO_PDF']['PATH'] = $path.'/modulos/relatorios/';
$SISCONF['SIS']['RELATORIO_PDF']['HOST'] = $host.'/modulos/relatorios/';

$SISCONF['PORTAL']['PATH'] = '/sistemas/';
$SISCONF['PORTAL']['HOST'] = $host;
$SISCONF['PORTAL']['MAIN'] = 'portal.php';

$SISCONF['SIS']['DEBUG'] = false;
$SISCONF['SIS']['DEBUG_IP'][] = 'localhost'; //gelsimar
$SISCONF['SIS']['DEBUG_IP'][] = '127.0.0.1'; //gelsimar
$SISCONF['SIS']['DEBUG_IP'][] = '192.168.56.1'; //gelsimar

$SISCONF['SIS']['QUERIES'] = array();
$SISCONF['SIS']['EVENTS'] = array();

//Não é classe, somente função
function memorizeQuery($query = ''){
    GLOBAL $SISCONF;
    array_push($SISCONF['SIS']['QUERIES'],$query);
}

function isDeveloper(){
    //Verifica se o IP do usuário corrente esta na lista dos desenvolvedores
    GLOBAL $SISCONF;
    $ret = false;
    for ($d=0; $d < count($SISCONF['SIS']['DEBUG_IP']); $d++){
        if ($SISCONF['SESSAO']['USUARIO']['IP'] == $SISCONF['SIS']['DEBUG_IP'][$d]) {
            $ret = true;
        }
    }
    return $ret;
}

function isLocked(){
    //Usado para liberar o acesso nos sistemas dos usuarios desenvolvedores
    GLOBAL $SISCONF;
    $ret = false;

    for ($d=0; $d < count($SISCONF['SIS']['DEBUG_IP']); $d++){
        if ($SISCONF['SESSAO']['USUARIO']['IP'] == $SISCONF['SIS']['DEBUG_IP'][$d]) {
            $ret = true;
        }
    }

    return $ret;
}

/**
 * Só exibe um DUMP de uma variável passada
 * 
 */
function dump($variavel,$info="",$cor='blue')
{
    if (isLocked()) {
        if (trim($info)!="") {
            echo "<br /><font color='".$cor."'>Exibindo ".$info."</font>";
        }
        echo "<pre>";
        print_r($variavel);
        echo "</pre>";
    }
}

/**
* Remove as barras digitadas pelo usuário
* Retorna o array limpo
*/
function formataRequests( $arrRequest ){
   	$arrRequest = str_replace("\'", "'", $arrRequest);

 if( is_array($arrRequest) && count($arrRequest) > 0 ){
   	foreach ($arrRequest as $chave => $value) {
		if( is_array( $value ) ){
			$value = formataRequests($value);
		}else{
			$value = strtr($value, "\\\"", " ");
	      	$value = trim( $value );
		}
		$arrRequest[$chave] = $value;
   	}         
 }
   
   return $arrRequest;
} 

require_once "comum/util/debug.class.php";

#COMENTARIO
//instancia o debugger

$SISCONF['DEBUG'] = new ext_debug_class();
?>