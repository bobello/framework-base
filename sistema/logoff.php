<?PHP
//$inc_path = "/usr/local/apache/htdocs/teste/includes/";
//include $inc_path."sess.php";
require "comum/sessao/session2.inc.php";
require_once "comum/sessao/configsis.inc.php";

#session_unregister('user');
unset($user);
#session_unregister('pwd');
unset($pwd);
#session_unregister('db');
unset($db);
#session_unregister('page');
unset($page);
#session_unregister('page2');
unset($page2);
#session_unregister('userid');
unset($userid);
#session_unregister('menssagem_erro');
unset($menssagem_erro);
#session_unregister('resultado_pesquisa');
unset($resultado_pesquisa);
#session_unregister('operacao');
unset($operacao);
#session_unregister('theme');
unset($theme);
#session_unregister('operacao');
unset($operacao);
#session_unregister('ano_semestre');
unset($ano_semestre);

session_destroy();

   echo "<html><head></head><body bgcolor=\"#FFFFFF\">";
   echo "<font face=\"Verdana\" size=\"+1\">Aguarde redirecionamento ...</font>";
   echo "<br><br>";
   echo "  <font face=\"Verdana\" size=\"1\">Caso vo&ecirc; n&atilde;o tenha sido redirecionado para o site, clique no link abaixo:</font>";
   echo "  <br>";
   echo "  <a href=\"portal.php\">LOGIN</a>";
   echo "<script language:javascript>";
   echo "window.open(\"redirect.php\",'_top');";
   echo "</script>";
   echo "</body></html>";

?>