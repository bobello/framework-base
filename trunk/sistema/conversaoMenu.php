<?PHP
require "comum/sessao/session2.inc.php";
require_once "comum/sessao/configsis.inc.php";
require_once "comum/sessao/menu.inc.php";
require_once "comum/sessao/package_sistema.class.php";

function retiraUltimoCaracterDaString($string) {
    $tamanhoDaString = strlen($string)-2;
    $string = substr($string,0,$tamanhoDaString);

    return $string;
}

function montaSubMenu(&$grupoMenu, &$codJs, &$permissoes, $srcImage) {
    if (is_array($grupoMenu)) {
        foreach($grupoMenu as $value) {

            $nome = $value["NOME_ACESSO_RAPIDO"];
            if ( $nome == "" ) $nome = $value["NOME"];

            $itemMenuPrincipal = str_replace("'",'\'', $nome);

            if ( (isset($value["MENU"]) && is_array($value["MENU"])) || (isset($value["SUBMENU"]) && is_array($value["SUBMENU"])) ) {
                $link = "null";
                $target = "null";
                if ( $value["LNK"] != "") {
                    $link = "'".$value["LNK"]."'";
                    if ( trim($value["TARGET"]) == "" ) {
                        $target = "'corpo'";
                    } else {
                        $target = "'".trim($value["TARGET"])."'";
                    }
                }
                $codJs .=  "\t\t[null,'".$itemMenuPrincipal." ".$srcImage."',".$link.",".$target.",null,\n";

                if ( is_array($value["MENU"]) ) {
                    $parts = &$value["MENU"];
                } else {
                    $parts = &$value["SUBMENU"];
                }
				
                montaSubMenu($parts, $codJs, $permissoes, $srcImage);
            } else {
                // verifica se o usuário tem permissão para entrar no programa
                if ( (($value["PROG"] != "") || ($value["PROG"] == "0") ) && ($value["MOD"] != "") ) {
                    if ( !getPermissaoPrograma($permissoes, $value["PROG"], $value["MOD"]) ) {
                        $itemMenuPrincipal = "<font style=\"{color:red}\"><b>*</b></font> ".$itemMenuPrincipal;
                    }
                }

                if ( trim($value["TARGET"]) == "" ) {
                    $target = "'corpo'";
                } else {
                    $target = "'".trim($value["TARGET"])."'";
                }

                $codJs .=  "\t\t[null,'$itemMenuPrincipal','".$value["LNK"]."',".$target.",null,\n";
            }

            $codJs .= "\t\t],";
            $codJs .= "\n\t.§";

            $codJs = retiraUltimoCaracterDaString($codJs);
        }
    }
}

function getTodasPermissoes() {
    $prog = new programa(0,0,false,"",false);

    $perm = $prog->getAllPermissoes();

    return $perm;
}

function getPermissaoPrograma(&$arrayPerm, $programa, $modulo) {
    if ( empty($arrayPerm[$programa."|".$modulo]) ) {
        return false;
    } else {
        return true;
    }
}


    // ************************************************************************
    // PROGRAMA PRINCIPAL
    $permissoes = getTodasPermissoes();
	
    GLOBAL $MENU_SUSPENSO;

    $srcImage = "<img src=\"arrow_desativada.gif\" border=0>";

    $codigoJs = "[\n";

    $GRUPOS = &$MENU_SUSPENSO;
    
    // percorre os títulos deste menu
    foreach ($GRUPOS as $value) {
        $itemMenuPrincipal = str_replace("'",'\'', $value["TITLE"]);

        # Iniciando o grupo
        if ( !$value["ESPECIAL"] ) {
            $codigoJs .=  "\t[null,'".$itemMenuPrincipal." ".$srcImage."',null,null,null,\n";
        }

        if ( !empty($value["SUB"]) ) {
            $array = &$value["SUB"];
			//dump($value, "VALUE");
            montaSubMenu($value["SUB"], $codigoJs, $permissoes, $srcImage);

            $codigoJs = retiraUltimoCaracterDaString($codigoJs);
        }

        if ( !$value["ESPECIAL"] ) {
            $codigoJs .= "],";
        }
        $codigoJs .= "§";
    }

    $pos = strpos($SISCONF['SESSAO']['USUARIO']['NOME'], " ");
    if ( $pos === false ) {
        $nomeUsu = $SISCONF['SESSAO']['USUARIO']['NOME'];
    } else {
        $nomeUsu = substr($SISCONF['SESSAO']['USUARIO']['NOME'], 0, $pos);
    }

    $codigoJs .= "[null,'<font style=\"{font-weight: 700;}\">Usuário: ".$nomeUsu."</font>',null,null,null,],§";

    $codigoJs = retiraUltimoCaracterDaString($codigoJs);
    $codigoJs = str_replace('§',"\t_cmSplit,\n",$codigoJs);
    $codigoJs .= "\n];\n";
	
    echo "menuSGU=".trim($codigoJs)."\n";
?>
