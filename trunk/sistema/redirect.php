<?php
    require "comum/sessao/session2.inc.php";
    require_once "comum/sessao/configsis.inc.php";

    GLOBAL $SISCONF;

    echo "<html>";
    echo "<body onLoad='document.form.submit()'>";
    //echo "<body>";
    dump($SISCONF['PORTAL']['HOST'].$SISCONF['PORTAL']['MAIN']);
    echo "<form action=\"".$SISCONF['PORTAL']['HOST'].$SISCONF['PORTAL']['MAIN']."\" method=post name=form>";
    foreach(array_keys($_REQUEST) as $k){
        echo "<input type=\"hidden\" name=\"$k\" value=\"".$_REQUEST[$k]."\"><br />";
    }

    if (!empty($pagina)) {
        $page = $pagina;
        $cont = 0;
        foreach(array_keys($_REQUEST) as $k){
            if ($k != "pagina") {
                $cont++;
                if ($cont==1) {$page .= "?";}
                else {$page .= "&";}

                $page .= "$k=".$_REQUEST[$k];
            }
        }
    }

    echo "</form>";
    echo "</body>
        </html>";
?>