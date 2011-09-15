<?PHP
//set_include_path("/var/www/OZ-AT/sgc_alterado/phpclasses/");
require "comum/sessao/session2.inc.php";
require_once "comum/util/package_browser_dir.class.php";
require "comum/sessao/configsis.inc.php";
require_once "comum/banco/package_bd.class.php";

$classBd = new genericDB();
$classBd->permiteConectar();

GLOBAL $TNS_SUN;
GLOBAL $_REQUEST;
GLOBAL $SISCONF;

$l_user = (isset($_REQUEST["l_user"]) ? mb_strtolower($_REQUEST["l_user"]) : null);
$l_pwd = (isset($_REQUEST["l_pwd"]) ? $_REQUEST["l_pwd"] : null );

$redirectJs = "\n<html><head></head><body bgcolor=\"#FFFFFF\">".
"\n	<font face=\"Verdana\" size=\"+1\">Aguarde redirecionamento ...</font>".
"\n	<br /><br />".
"\n	<font face=\"Verdana\" size=\"1\">Caso vo&ecirc; n&atilde;o tenha sido redirecionado para o site, clique no link abaixo:</font><br>".
"\n	<a href=\"portal.php\">LOGIN</a>".
"\n	<script language:javascript>".
"\n		window.open(\"portal.php\",'_top');".
"\n	</script>".
"\n</body></html>";
//error_reporting(0);

if (empty($l_user) or empty($l_pwd)) {
    echo $redirectJs;
} else {
    $_SESSION["user"] = $l_user;
    $_SESSION["pwd"] = $l_pwd;
    $_SESSION["bancoDeDados"] = $SISCONF['DB']['SISTEMA']['HOST'];

    $user = $l_user;
    $pwd = $l_pwd;

    // Seta uma mensagem de Erro Caso o Login dê errado
    $_SESSION["mensagem_erro"] = "Atenção: Usuário ou Senha Inválidos";

    $conn = mysql_connect($SISCONF['DB']['SISTEMA']['HOST']
    						, $user, $pwd)
        or die ($redirectJs);
    
    mysql_select_db($SISCONF['DB']['SISTEMA']['BASE']) or die ("Não foi possível conectar no MYSQL!");
    
    // Limpa a mensagem de Erro
    $page2="";
    $menssagem_erro="";
    $SQL = "SELECT id_usuario, usu_nome FROM usuario WHERE lower(usu_usuario) = '".$user."'";

    $dyn = mysql_query($SQL, $conn);
    while ($row = mysql_fetch_array($dyn)) {
        $userid = $row['id_usuario'];
        $usernome = $row['usu_nome'];

        $_SESSION["userid"] = $userid;
        $_SESSION["usernome"] = $usernome;
    }

    // exclui os arquivos da pasta de relatórios
    GLOBAL $SISCONF;

    $arq = new formDir();
    $arq->diretorio = $SISCONF['SIS']['RELATORIO_PDF']['PATH'];
    $arq->filtro_extensao = "pdf";
    $arq->listaArquivos(true);
    $arq->excluiArquivos();
	$pagina = null;
    echo "<html><head></head><body bgcolor=\"#FFFFFF\">";
    echo "<font face=\"Verdana\" size=\"+1\">Aguarde redirecionamento ...</font>";
    echo "<br><br>";
    echo "  <font face=\"Verdana\" size=\"1\">Caso vo&ecirc; n&atilde;o tenha sido redirecionado para o site, clique no link abaixo:</font>";
    echo "  <br>";
    echo "  <a href=\"portal.php\">Página Solicitada</a>";
    echo "<script language:javascript>";
    $_REQUEST['user'] = $user;
    $_REQUEST['usernome'] = $usernome;
    $_REQUEST['userid'] = $userid;
    $_REQUEST['pwd'] = $pwd;
    $_REQUEST['pagina'] = $pagina;

    $_SESSION['user'] = $user;
    $_SESSION['usernome'] = $usernome;
    $_SESSION['userid'] = $userid;
    $_SESSION['pwd'] = $pwd;
    $_SESSION['pagina'] = $pagina;
    echo "window.open('redirect.php','_top');";
    echo "</script>";
    echo "</body></html>";
}
?>
