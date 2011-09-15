<?php
require_once "comum/sessao/configsis.inc.php"; //somente no programa
require_once "comum/sessao/menu.inc.php";

class Portal_SGU {
    var $img_Portal;
    var $imgCruz;
    var $imgSeta;
    var $op_menu;
    var $menu;

    var $POSTED;
    var $program;
    var $usuario;
    var $senha;

    function Portal_SGU($POSTED) {
        $this->POSTED = $POSTED;
        $this->img_Portal = "/portal/modulos/temas/comum/images/bar_top.jpg";
        $this->imgCruz = "<img src=\"/portal/modulos/temas/comum/images/b_verm.png\">";
        $this->imgSeta = "<img src=\"/portal/modulos/temas/comum/images/s_verde.gif\"> ";

        $this->clearProperties();
        $this->setProperties();
        $this->program = new programa(0,0,false,"",false);

        $permDB = new confDB();
        $permDB->setBancoByAlias('SISTEMA');
        $permDB->conecta();
        $this->program->setActiveDBConnection($permDB);
    }

    function clearProperties() {
        $this->menu = array();
        $this->op_menu = "";
    }

    function setProperties() {
        if (!empty($this->POSTED['op_menu'])) {
            $this->op_menu = explode("_", $this->POSTED['op_menu']);
            $this->menu = $this->op_menu;
        } else {
            if ( isset($this->POSTED['menu']) ) $this->menu = $this->POSTED['menu'];
        }
    }

    function setUsuario($user, $pwd){
        $this->usuario = $user;
        $this->senha = $pwd;
    }

    function style_body() {
        return "style=\"margin-top: 0em; margin-left: 0em\"";
    }


    function menu() {
        GLOBAL $MENU;
        GLOBAL $MENU_IMG;

        echo "\n<td>".
            "\n<table cellspacing=0 cellpadding=0 border=0 width=630>".
                "\n<tr>".
                  "\n<td width=509 valign=bottom>".
                      "<table border=0 align=left width=100%><tr><td valign=middle align=left>".
                        "<font class='titulosecao'>".
                            "\n<img src='".$MENU_IMG['IMG_PASTA']."' border='0' align='left'>".mb_strtoupper($MENU[$this->menu[0]]['NOME'])."</font>".
                        "</td></tr></table>".
                    "\n</td>".
                    "\n<td width='121' align='right'>".
                        "\n<img src='".$MENU_IMG['HOST_IMAGENS']."board-legenda-sgc.gif' width='121' height='87'>".
                    "\n</td>".
              "\n</tr>".
              "\n<tr>".
                    "\n<td colspan=2>".
                        "\n<table border=0 width=100%>";
                        $this->subMenu($MENU[$this->menu[0]], 0);
                        echo "\n</table>".
                    "\n</td>".
                "\n</tr>".
            "\n</table>".
        "\n</td>";
    }

    function subMenu(&$MENU, $posMenu) {
        GLOBAL $MENU_IMG;
        GLOBAL $SISCONF;

        foreach ($MENU['MENU'] as $key=>$value) {
            if ( (isset($value["MENU"])) && (is_array($value["MENU"])) ) {
                echo "\n<tr>".
                    "\n<td width=20px>&nbsp;</td>".
                    "\n<td valign=\"center\"><img src=\"".$MENU_IMG['IMG_EXPLODE']."\">";
                echo "\n<a href='".$value['LNK']."#".$value["NOME"]."' name='".$value["NOME"]."' class=menu>".$value["NOME"]."</a>";

                $prox = $posMenu+1;
                if ( (isset($this->menu[$prox])) && ($key == $this->menu[$prox]) ) {
                    echo "\n<table border=0 width=100%>";
                    $this->subMenu($value, $prox);
                    echo "</table>";
                }
            } else {
                echo "<tr><td width=20px>&nbsp;</td><td>
                    <table cellspacing=0 cellpadding=0 border=0 width=100%>";
                echo "<tr>";

                // ===========PARTE ADICIONADA=============
                $modulo = $value['MOD'];
                $programa = $value['PROG'];
                $this->program->setPrograma($programa,$modulo);
                $this->program->getProgram();

                $ptarget = "";
                $texto = $value['NOME'];
                $plnk = $value['LNK'];
                if ( isset($value['TARGET']) ) {
                    $ptarget = $value['TARGET'];
                }

                $pastaDeImagens = $MENU_IMG['HOST_IMAGENS'];
                $imagemPermissao = "<img src='".$MENU_IMG['IMG_FINAL']."' border=0>";

        if ($modulo!=0) {
                    if($this->program->permitido('N')){
                        $imagemPermissao = "<img src='".$MENU_IMG['IMG_FINAL']."' border=0 title='Acesso ao módulo permitido'>";
                    } else {
                        $imagemPermissao = "<img src='".$pastaDeImagens."ico-closed.gif' border=0 title='Sem permissão de acesso ao módulo'>";
                        $imagemAddMeuMenu = "";
                    }
                }
                //$descricao = $this->program->getDescricao();
                $descricao = "";
                $imagemInformacao="<img src='".$pastaDeImagens."ico-info.gif' border=0 title='Informações sobre o módulo'>";
                // ========================================

                // ===========PARTE MODIFICADA=============
                echo "<td align=left width=1>".
                        //"<a href='#' onClick='janelaDeInformacao($programa,$modulo)'>".$imagemInformacao."</a></td>".
                        "<td width=1>".$imagemPermissao."</td>".
                        "<td width=5>&nbsp;</td>";

                echo "<td>
                    <a href='".$value['LNK']."' ";
                    if ( isset($value['TARGET']) ) {
                        echo " target='".$value['TARGET']."'";
                    }
                    echo " class=menu>".$texto."</a></td>";
// ========================================
                echo "</tr>";
                echo "</table></td></tr>";
            }
        }
        return;
    }
}
?>
