<?php
require_once "comum/sessao/configsis.inc.php";
GLOBAL $SISCONF;

$urlHost = 'https://desenvolvimento.unilasalle.edu.br/portal/';
define("HOST", $urlHost);
define("INFORMATION", HOST."modulos/temas/comum/images/Information.gif");
define("ERROR", HOST."modulos/temas/comum/images/Error.gif");
define("WARNING", HOST."modulos/temas/comum/images/Warning.gif");
define("QUESTION", HOST."modulos/temas/comum/images/Question.gif");
define("OK", HOST."modulos/temas/comum/images/Ok.gif");
define("TEXT", "");

//classe generica de retorno de mensagens
class message {

    //metodo constructor da classe
    function message($mensagem = '', $tipo = ERROR, $titulo = 'Prezado Usuário:') {
        if ($mensagem != '') {
            return $this -> getMessage($mensagem, $tipo, $titulo);
        }
    }

    //metodo que retorna a mensagem
    function getMessage($mensagem, $tipo = ERROR, $titulo = 'Prezado Usuário:') {
        $_mensagem = "\n<TABLE ALIGN=CENTER BORDER=6 CELLPADDING=3 CELLSPACING=3 BGCOLOR=#EFCE59><TR><TD BGCOLOR=\"#E9F0D8\">" . "<TABLE ALIGN=CENTER BORDER=0 CELLPADDING=3 CELLSPACING=0><TR>";

        if (trim($tipo) != '') {$_mensagem .= "<TD VALIGN=TOP><IMG SRC=\"$tipo\" BORDER=0></TD>";}
        $_mensagem .= "<TD>";

        if (trim($titulo) != '') {$_mensagem .= "<B><CENTER>$titulo</CENTER></B>";}
        $_mensagem .= "$mensagem</TD></TR></TABLE></TD></TR></TABLE>\n";

        return $_mensagem;
    }
}

//caixa de mensagens personalizadas
class msgbox extends message {

        //atributos da classe
        var $mensagem;
        var $tipo;
        var $titulo;
        var $action;

        //metodo constructor da classe
        function msgbox($mensagem='', $tipo= ERROR, $forma = TEXT, $titulo = 'aviso', $action=''){
            GLOBAL $SISCONF;
            $form = new form("Sistemas",$action,"10%");
            $form->addField(new FormButton("botao","botao","submit"));
            $_mensagem = "\n<TABLE ALIGN=CENTER BORDER=2 CELLPADDING=0 CELLSPACING=3 BGCOLOR=#EFCE59>
                            <TR><TD BGCOLOR=\"#E9F0D8\">
                            <TABLE ALIGN=CENTER BORDER=0 CELLPADDING=3 CELLSPACING=0><TR>";
            if (trim($tipo) != '') $_mensagem .= "<TD><IMG SRC=\"$tipo\" BORDER=0></TD>";
            $_mensagem .= "<TD>";
            if (trim($titulo) != '') $_mensagem .= "<B><CENTER>$titulo</CENTER></B>";
            $_mensagem .= "</TD></TR></TABLE></TD></TR>
                          <TR><TD BGCOLOR=\"#E9F0D8\">
                          <BR><P>$mensagem</P>
                          <center>";

           $action = htmlspecialchars(strip_tags($action),ENT_QUOTES);
            //verifica se deve montar o form
            switch($forma){
                case TEXT:
                    $_mensagem .="";
                    break;
                case OK:
                    $_mensagem .= "<form class=cadastro name=\"frm\" method=\"post\" action=\"$action\">
                    <table BGCOLOR=#EFCE59 border = 0 width=\"10%\">
                    <tr><td bgcolor=#C1D9FE ><input name=\"botao\"  type=\"submit\"  value=\" OK \">
                    </td></tr></table>
                    </form>";
                    break;
                case VOLTAR:
                    $_mensagem .= "<form class=cadastro name=\"frm\" method=\"post\" action=\"$action\">
                    <table BGCOLOR=#EFCE59 border = 0 width=\"10%\">
                    <tr><td bgcolor=#C1D9FE ><input name=\"botao\"  type=\"submit\"  value=\" VOLTAR \">
                    </td></tr></table>
                    </form>";
                    break;
            } // switch

             $_mensagem .= "</center>
                          </TD></TR>
                          </TABLE>\n";

           return $_mensagem;
        }
}
?>