<?php

function render($template, $vars=NULL) {
    global $SISCONF;
    if( file_exists($template) ) {

        if( is_array($vars) ) {
            extract($vars);
        }
        ob_start();
        include($template);
        $render = ob_get_contents();
        ob_end_clean();
        $absPath =  dirname($SISCONF['SIS']['WEB']['HOST']).'/web/';
        $render = str_replace(array('/../', '../'), array($absPath,$absPath), $render );
        if($printable) {
            echo $render;
        }
    }
    
    return $render;

}

?>
