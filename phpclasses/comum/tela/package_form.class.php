<?php
require_once "comum/util/package_scripts.class.php";

// alteração: foi adicionado o maxlenght a classe textfield
// diegom
class Form {
//var $descriptor = "function Form(\$title = '', \$action = '', \$table_width = '70%', \$onSubmit = '')";
    var $descriptor = "function Form(\$title = '', \$action = '', \$table_width = '70%', \$onSubmit = '')
<br />function setFormName(\$name)
<br />function setFormHeigth(\$heigth)
<br />function setFormTarget(\$target)
<br />function setAsterisc(\$valor)
<br />function addMessage(\$contents)
<br />function addTitleMessage(\$contents=\"Atenção\")
<br />function addErrorMessage(\$contents)
<br />function setLeftColWidth(\$value)
<br />function setFormAlign(\$value = \"CENTER\")
<br />function setRightColWidth(\$value)
<br />function setNoBorder()
<br />function setNoBackground(){
<br />function addBlockQuote(\$levels = 0)
<br />function setOnSubmit( \$value )
<br />function getOnSubmit()
<br />function &GetTitle()
<br />function SetTitle(\$title)
<br />function SetAction(\$action)
<br />function AddField(&\$field)
<br />function AddButton(\$label, \$name, \$action)
<br />function SetButtonLabel(\$index, \$label)
<br />function SetButtonReturn()
<br />function Generate()
<br />function getDescriptor()";

    var $title;
    var $name;
    var $action;
    var $formTarget;
    var $method;
    var $elements;
    var $return;
    var $reset;
    var $styles;
    var $help;
    var $footer;
    var $width;
    var $form_table_width;
    var $form_table_heigth;
    var $form_table_border;
    var $form_table_cellpadding;
    var $form_table_cellspacing;
    var $form_table_bgcolor;
    var $form_table_bgcolor2;
    var $form_button_background;
    var $form_button_font_color;
    var $blockquote_levels;
    var $type;
    var $leftcolwidth;
    var $rightcolwidth;
    var $message;
    var $errors_message;
    var $errors_message_js;
    var $noBorder;
    var $noBackgroud;
    var $enable_scripts;
    var $titleMessagesError;
    var $formAlign;
    var $onSubmit;
    var $asterisc = FALSE;
    var $formUpLoad = false;
    var $naoVisivelImpressao;

    // constructor da classe
    function Form($title = '', $action = '', $table_width = '70%', $onSubmit = '', $naoVisivelImpressao=false) {

        $this->form_button_background = "#E5E2D8";
        $this->form_button_font_color = "#000000";
        $this->form_button_font_color = "#CE0000";
        $this->formTarget = "";
        $this->naoVisivelImpressao = $naoVisivelImpressao;
        $this->form_table_width = $table_width;
        $this->form_table_border = 0;
        $this->form_table_cellpadding = 0;
        $this->form_table_cellspacing = 4;
        $this->title = $title;
        $this->name = 'frm'.mt_rand(1, 40000);
        $this->action = $action;
        $this->method = 'post';
        $this->width = '100%';
        $this->buttons[] = new FormButton('Enviar', 'FORM_SUBMIT_BTN_NAME', 'SUBMIT');
        $this->return = false;
        $this->reset = false;
        $this->blockquote_levels = 0;
        $this->type = 'COMMAND';
        $this->noBorder = true;
        $this->noBackgroud = true;
        $this->errors_message = '';
        $this->errors_message_js = '';
        $this->enable_scripts = false;
        $this->titleMessagesError = "";
        $this->onSubmit = '';
        if ($onSubmit != "") $this->setOnSubmit( $onSubmit );
        $this->setFormAlign();
    }

    function setFormName($name) {
        $name = trim($name);
        if ( $name != "") $this->name = $name;
    }

    // Seta a altura do Formulário
    function setFormHeigth($heigth) {
        $heigth = trim($heigth);
        if ($heigth != "") $this->form_table_heigth = $heigth;
    }

    function setFormTarget($target) {
        $target = trim($target);
        $this->formTarget = $target;
    }

    function setAsterisc($valor) {
        $this->asterisc = $valor;
    }

    //
    function addMessage($contents) {
        $contents=trim($contents);
        if ($contents!="") $this->message .= "<br />".$contents;
    }

    function addTitleMessage($contents="Atenção") {
        $contents=trim($contents);
        $this->titleMessagesError = $contents;
    }

    //
    function addErrorMessage($contents) {
        $contents = trim($contents);
        $fnt = "<font size=1 face=\"courier\"></font>";
        $fnt .= "<font size=2 face=\"arial\"><b></b></font>";
        $fnt .= "<font size=1 face=\"arial\"></font>";

        if ($contents != "") {
            $this->errors_message .= "<br /><center>$fnt" . $contents."</center>";
            $contents = str_replace("<br />",'\n',$contents);
            $contents = str_replace("<br />",'\n',$contents);
            $this->errors_message_js .= '\\n'. $contents;
        }
    }

    //
    function setLeftColWidth($value) {
        $this->leftcolwidth = $value;
    }

    function setFormAlign($value = "CENTER") {
        $value = trim(strtoupper($value));
        if ( ($value=="CENTER") or ($value=="LEFT") or ($value=="RIGHT") ) {
            $this->formAlign = $value;
        } else {
            $this->formAlign = "CENTER";
        }
    }

    function setRightColWidth($value) {
        $this->rightcolwidth = $value;
    }

    //
    function setNoBorder() {
        $this->noBorder = FALSE;
    }

    function setNoBackground() {
        $this->noBackground = FALSE;
    }

    function addBlockQuote($levels = 0) {
        $this->blockquote_levels = $levels;
    }

    function setOnSubmit( $value ) {
        $this->onSubmit = $value;
    }

    function getOnSubmit() {
        return $this->onSubmit;
    }

    // busca o titulo do form
    function &GetTitle() {
        return $this->title;
    }

    // seta um titulo para a classe
    function SetTitle($title) {
        $this->title = $title;
    }

    // seta a url de ação para o form
    function SetAction($action) {
        $this->action = $action;
    }

    // adiciona um formfield ao formulario
    function AddField(&$field) {
        $this->elements[] = $field;
    }

    // adiciona um formbutton ao formulario
    function AddButton($label, $name, $action) {
        $this->elements[] = new FormButton($label, $name, $action);
    }

    // seta o valor do nome do botao adicionado
    function SetButtonLabel($index, $label) {
        $this->elements[$index]->label = $label;
    }

    // seta o botao retornar
    function SetButtonReturn() {
        $this->return = true;
    }

    function setFormUpLoad($opcao = true) {
        $this->formUpLoad = $opcao;
    }

    function getCode() {

        GLOBAL $SISCONF;
        $html = "";

        $block_lev_up = "";
        $block_lev_dn = "";

        $fontStyleTitle = " font-family: Tahoma, Arial; font-size 12px ";
        $fontStyleLCol = " font-family: Tahoma, Arial; font-size 11px ";
        $fontStyleRCol = " font-family: Tahoma, Arial; font-size 11px ";

        if ($this->blockquote_levels > 0) {
            for ($i = 1;$i <= $this->blockquote_levels;$i++) {
                $block_lev_up .= "\n<blockquote>\n";
                $block_lev_dn .= "\n</blockquote>\n";
            }
        }

        $onSubmit = $this->onSubmit;
        if ( trim($onSubmit) != "" ) $onSubmit = " onSubmit=\"$onSubmit\" ";

        $tmpTarget = "";
        if (trim($this->formTarget)!="") {
            $tmpTarget = " target=\"".$this->formTarget."\" ";
        }

        if ($this->naoVisivelImpressao===true) {
            $form = "<div class='naoVisivelImpressao'><form class=cadastro";
        }
        else {
            $form = "<form class=cadastro";
        }


        $this->action = htmlspecialchars(strip_tags($this->action),ENT_QUOTES);

        if ( $this->formUpLoad === true ) $form .= " enctype=\"multipart/form-data\"";
        $form .= " name=\"".$this->name."\" method=\"".$this->method."\" ".$tmpTarget." action=\"".$this->action."\" ".$onSubmit.">\n";

        $html .= $form;

        //verifica se ha borda ou nao
        if ($this->noBorder==FALSE) {
            if ($this->noBackgroud==FALSE) {
                $html .= "$block_lev_up" . "<table align=".$this->formAlign." BORDER=0 CELLPADDING=3 CELLSPACING=3 ><tr><td >" . "<table class=cadastro_borda cellpadding=0 cellspacing=0>\n";
            }else {
                $html .= "$block_lev_up" . "<table align=".$this->formAlign." BORDER=0 CELLPADDING=3 CELLSPACING=3 BGCOLOR=\"".$this->form_table_bgcolor."\"><tr><td BGCOLOR=\"".$this->form_table_bgcolor2."\">" . "<table class=cadastro_borda cellpadding=0 cellspacing=0>\n";
            }
        }else {
            if ($this->noBackgroud==FALSE) {
                $html .= "$block_lev_up" . "<table align=".$this->formAlign." BORDER=6 CELLPADDING=3 CELLSPACING=3 ><tr><td >" . "<table class=cadastro_borda cellpadding=0 cellspacing=0>\n";
            }else {
                $html .= "$block_lev_up" . "<table align=".$this->formAlign." BORDER=6 CELLPADDING=3 CELLSPACING=3 BGCOLOR=\"".$this->form_table_bgcolor."\"><tr><td BGCOLOR=\"".$this->form_table_bgcolor2."\">" . "<table class=cadastro_borda cellpadding=0 cellspacing=0>\n";
            }
        }

        $fieldsObrigatorios="";
        if ($this->title != "") {
            if ($this->asterisc==TRUE) {
                $fieldsObrigatorios = "<br /><font class=TITULO_OBRIGATORIO>* Dados Obrigatórios</font>";
            }

            $html .= "<tr><td class=cadastro_cabecalho align=center ><p align=CENTER style={font-size: 14px}><br /><b><label class='XPTITLE'>" . $this->title . "</label></b>$fieldsObrigatorios</p></td></tr>";
        }

        $showMessageBox = FALSE;
        if (trim($this->errors_message) != "") {
            $html .= "<tr><td class=cadastro align=center>";
            $html .= "<p align=LEFT>" . "<font style={font-size: 12px;}><b>".$this->titleMessagesError."</b></font>" . "<font style={font-size: 10px;}>" . $this->errors_message . "</font>" . "</p>";
            $html .= "</td></tr>";
            $showMessageBox = TRUE;
        }
        $html .= "<tr><td class=cadastro_cabecalho align=center >";

        if (trim($this->form_table_heigth)!="") {
            $html .= "<table class=cadastro border = " . $this->form_table_border . " " . "width=\"" . $this->form_table_width . " heigth=\"" . $this->form_table_heigth. "\" " . " style='{ height : ".$this->form_table_heigth."px }' cellpadding=\"" . $this->form_table_cellpadding . "0\" " . "cellspacing=\"" . $this->form_table_cellspacing . "\">\n";
        } else {
            $html .= "<table class=cadastro border = " . $this->form_table_border . " " . "width=\"" . $this->form_table_width . "\" " . "cellpadding=\"" . $this->form_table_cellpadding . "0\" " . "cellspacing=\"" . $this->form_table_cellspacing . "\">\n";
        }

        $hidden = null;

        $hasAsterisc = FALSE;

        if ($this->elements) {
            $html .= "\n<tr>";
            $col = 0;
            foreach ($this->elements as $f) {
                $cl = get_class($f);
                $cl = mb_strtoupper($cl);
                $f->background = $this->form_button_background;
                $f->font_color = $this->form_button_font_color;

                if ( isset($f->asterisc) ) {
                    if ($f->asterisc===TRUE) $hasAsterisc =TRUE;
                }

                $col++;

                $col_with = "";

                $col_class = " class=cadastro ";
                if ($col == 1) {
                    if (is_int($this->leftcolwidth)) {
                        $col_with = " Width=" . $this->leftcolwidth;
                    }
                    $col_class = "class=cadastro_cabecalho";
                }else {
                    $this->form_table_width = (integer) $this->form_table_width;
                    if ((is_int($this->leftcolwidth)) && (is_int($this->form_table_width))) {
                        $col_with = " Width=" . ($this->form_table_width - $this->leftcolwidth);
                    }
                }

                if ($cl == "NEWLINE") {
                    $html .= "\n</tr>\n";
                    $html .= $f->getCode();
                    $col = 0;
                } elseif ($cl == "CLEARFIELD") {
                    $html .= "<td $col_with class=cadastro_cabecalho>";
                    $html .= $f->getCode();
                    $html .= "</td>";
                } elseif ($cl == "TOOLBUTTON") {
                    if ($f->Getclass() == "FORMBUTTON") {
                        $f->background = $this->form_button_background;
                        $html .= "<td $col_with class=cadastro_cabecalho>";
                        $SISCONF['DEBUG']->addToDebug($f,"");
                        $html .= $f->getCode();
                        $html .= "</td>";
                    } else {
                        $html .= "<td $col_with class=cadastro_cabecalho >";
                        $html .= $f->getCode();
                        $html .= "</td>";
                    }
                } else {
                    $html .= "<td valign=top $col_with $col_class>";
                    $f->background = $this->form_button_background;
                    $html .= $f->getCode();
                    $html .= "</td>";
                }
            }
            $html .= "</tr>";
        }

        if ($this->reset) {
            $html .= "      <input type=\"reset\">\n";
        }

        if ($this->return) {
            $html .= "      <input name=\"return\"  type=\"button\" value=\"Retornar\" onclick=\"javascript:history.back(-1)\">\n";
        }

        $html .= "</table>\n";

        if ($hidden) {
            $html .= "      <!-- START OF HIDDEN FIELDS -->\n";

            foreach ($hidden as $h) {
                $html .= "      ";
                $html .= $h->getCode();
                $html .= "\n";
            }
            $html .= "      <!-- END OF HIDDEN FIELDS -->\n";
        }

        $html .= "</td></tr></table>" . "</td></tr></table>" . "\n $block_lev_dn \n";

        if ($this->naoVisivelImpressao===true) {
            $html .= "</form></div>\n";
        }
        else {
            $html .= "</form>\n";
        }

        if ($showMessageBox == TRUE) {
            $this->titleMessagesError = trim($this->titleMessagesError);
            $script = "<script language='JavaScript'>\n";
            $script .= "<!--\n";
            if ($this->titleMessagesError=="") {
                $script .= 'window.alert("'. strip_tags($this->errors_message_js).'");'."\n";
            }else {
                $script .= 'window.alert("'. strip_tags($this->titleMessagesError).':\\n'. strip_tags($this->errors_message_js).'");'."\n";
            }
            $script .= "-->\n";
            $script .= "</script>\n";
            $html .= $script;
        }
        return $html;
    }

    // gera o codigo do form
    function Generate() {
        $html = $this->getCode();
        echo $html;
    }

    function getDescriptor() {
        return $this->descriptor;
    }
} //fim da classe form


class ToolButton {
    var $descriptor = "function ToolButton()";
    var $elements;
    var $background;
    var $font_color;
    var $_code;

    function ToolButton() {
        $this->elements = array();
        $this->_code = "";
    }

    function AddButton(&$new_button) {
        array_push($this->elements, $new_button);
    }

    function Getclass() {
        $f = $this->elements[0];
        $cl = get_class($f);
        return $cl;
    }

    function getCode() {
        GLOBAL $SISCONF;
        foreach ($this->elements as $f) {
            $f->background = $this->background;
            $f->font_color = $this->font_color;
            $this->_code .= $f->getCode();
            $SISCONF['DEBUG']->addToDebug($f,'$f');
        }
        return $this->_code;
    }

    function Generate() {
        GLOBAL $SISCONF;
        foreach ($this->elements as $f) {
            $f->background = $this->background;
            $f->font_color = $this->font_color;
            $f->generate();
            $SISCONF['DEBUG']->addToDebug($f,'$f');
        }
    }

} //fim da classe ToolButton


//
class FormButton {
    var $descriptor = "function FormButton(\$label, \$name, \$action, \$size=\"\", \$id=\"\", \$js=\"\")";
    var $label;
    var $name;
    var $action;
    var $size;
    var $id;
    var $background;
    var $font_color;
    var $_code;

    function FormButton($label, $name, $action, $size="", $id="") {
        $this->label = $label;
        $this->name = $name;
        $this->action = $action;
        $this->size = $size;
        $this->id = $id;
    }

    function getCode() {
        GLOBAL $SISCONF;
        $SISCONF['DEBUG']->addToDebug($this,'Formbutton');
        $bgc = "";
        $fnc = "";

        $this->_code = "";

        $class = "BUTTON";
        if ( strtoupper($this->action) == 'FILE' ) $class = "XP";
        $id = $this->id != "" ? "id=\"".$this->id."\"" : "";
        $this->_code .= "<input class=\"".$class."\" ".$id." name=\"".$this->name."\"";

        if (strtoupper($this->action) == 'SUBMIT') {
            $this->_code .= "  type=\"submit\"";
        }else if (strtoupper($this->action) == 'RESET') {
                $this->_code .= "  type=\"reset\"";
            }else if (strtoupper($this->action) == 'RETURN') {
                    $this->_code .= "  type=\"button\" onclick=\"javascript:history.back(-1)\"";
                }else if (strtoupper($this->action) == 'FILE') {
                        $this->_code .= "  type=\"file\" size=\"".$this->size."\"";
                    }else {
                        $this->_code .= "  type=\"button\" onclick=\"$this->action\"";
                    }

        if ($this->label) {
            $this->_code .= "  value=\"$this->label\"";
        }

        $this->_code .= ">\n";

        return $this->_code;
    }

    function Generate() {
        echo $this->getCode();
    }

    function getDescriptor() {
        return $this->descriptor;
    }
}


// form de insercao de imagem
class FormImage {
    var $descriptor = "function FormImage(\$label, \$name, \$img)";
    var $label;
    var $name;
    var $img;
    var $background;
    var $font_color;
    var $_code;

    function FormImage($label, $name, $img) {
        $this->label = $label;
        $this->name = $name;
        $this->img = $img;
    }

    function getCode() {
        $this->_code = "";
        $this->_code .= "<img class='XP' src=\"$this->img\" border=0>";
        return $this->_code;
    }

    function Generate() {
        echo $this->getCode();
    }

    function getDescriptor() {
        return $this->descriptor;
    }
}


//
class FormField {
    var $descriptor = "function FormField(\$label, \$name, \$value = null)";
    var $form; // faz referencia com o objeto do form
    var $label; //label do objeto
    var $name; //nome do objeto no form
    var $value; //valor do objeto
    var $attrs; //atributos do objeto
    var $background;
    var $font_color;
    var $estilo;
    var $_code;
    var $asterisc = false;
    var $id="";

    function FormField($label, $name, $value = null) {
        $this->label = $label;
        $this->name = $name;
        $this->value = $value;
    }

    function AddAttribute($value) {
        $this->attrs[] = array($value);
    }

    function AddStyle($estilo) {
        $this->estilo = $estilo;
    }

    function Attributes() {
        $s='';
        if (count($this->attrs)) {
            foreach($this->attrs as $a) {
                if ($s) {
                    $s .= ' ';
                }
                $s .= $a[0] . '="' . $a[1] . '"';
            }
        }
        return $s;
    }

    function getCode() {
        $this->_code = "";
        if ($this->label) $this->_code .= $this->value;

        $a = $this->Attributes();
        $this->_code .= "<input class='XP' name=\"$this->name\" type=\"hidden\" value=\"$this->value\" $a>";

        return $this->_code;
    }

    function Generate() {
        echo $this->getCode();
    }

    function getDescriptor() {
        return $this->descriptor;
    }

}


//
class Text extends FormField {
    var $descriptor = "function Text(\$content, \$asterisc=FALSE)";
    var $idFor;
    function Text($content,$asterisc=FALSE, $idFor="") {
        $this->FormField($content, null);
        if (is_bool($asterisc)) $this->asterisc = $asterisc;
        $this->idFor = $idFor;
    }

    function getCode() {
        $ast = "";
        if ($this->asterisc===TRUE) $ast = "<font color=RED>*</font>";
        $this->_code = "<label class=\"XP\"".($this->idFor!=""?" for=".$this->idFor."":"").">{$this->label} $ast</label>";
        return $this->_code;
    }
    function Generate() {
        echo $this->getCode();
    }
}


class ClearField extends FormField {
    var $descriptor = "function ClearField(\$content = \"\")";

    function ClearField($content = "") {
        $this->FormField($content, null);
    }

    function getCode() {
        $this->_code = "<label class='XP'>{$this->label}</label>";
        return $this->_code;
    }
    function Generate() {
        echo $this->getCode();
    }
}


//
class TextLabel extends FormField {
    var $descriptor = "function TextLabel(\$label, \$value = null)";
    var $label;
    var $name;
    var $value;

    function TextLabel($label, $value = null) {
        $this->FormField($label, null);

        $this->label = $label;
        $this->value = $value;
    }

    function getCode() {
        $this->_code = "<label class='XP'>{$this->label}</label>";
        return $this->_code;
    }
    function Generate() {
        echo $this->getCode();
    }
}


class TextField extends FormField {
    var $descriptor = "function TextField(\$label, \$name, \$value = '', \$size = 10, \$obs = '', \$max = '', \$locked = false, \$js = \"\", \$disabled = \"\", , \$align=\"LEFT\", \$color='' \$id=\"\")";
    var $size;
    var $max;
    var $type;
    var $obs;
    var $locked;
    var $js;
    var $disabled;
    var $_align = '';
    var $_bgcolor = '';

    function TextField($label, $name, $value = '', $size = 10, $obs = '', $max = '', $locked = false, $js = "", $disabled = "", $align="LEFT", $bgcolor="", $id="") {
        $this->FormField($label, $name, $value);

        $this->size = $size;
        $this->max = $max;
        $this->type = $size ? 'text' : 'hidden';
        $this->obs = $obs;
        $this->locked = $locked;
        $this->js = $js;
        $this->disabled = $disabled;
        $this->setAlign($align);
        $this->_bgcolor = trim($bgcolor);
        $this->id = $id;
    }

    function setAlign($align) {
        $align = trim(strtoupper($align));
        switch ($align) {
            case 'LEFT': $this->_align = 'left'; break;
            case 'RIGHT': $this->_align = 'right'; break;
            case 'CENTER': $this->_align = 'center'; break;
            default : $this->_align = 'left'; break;
        }
    }


    function getCode() {
        $this->_code = "";

        $bgcolor = ( $this->_bgcolor != "" ? " background-color:".$this->_bgcolor.";" : "");

        if ( trim($this->id) =="" ) {
            $this->id = "i_".$this->name;
        }

        $this->_code .= "<input id=\"".$this->id."\" name=\"$this->name\" type=\"$this->type\" value=\"".htmlentities($this->value)."\"";
        if ($this->locked == true) $this->_code .= " readonly class=\"XPLOCK\" ";
        else $this->_code .= " class='XP' ";

        // size
        if ($this->type == 'text' && $this->size) $this->_code .= " size=\"$this->size\"";
        if ($this->type == 'password' && $this->size) $this->_code .= " size=\"$this->size\"";

        // max lenght
        if ($this->type == 'text' && $this->max) $this->_code .= " maxlength=\"$this->max\"";
        if ($this->type == 'password' && $this->max) $this->_code .= " maxlength=\"$this->max\"";

        // javaScript
        if ( $this->js != "" ) $this->_code .= " ".$this->js;

        // disabled
        if ( $this->disabled == true ) $this->_code .= " disabled=true ";

        $this->_code .= " style='{text-align: ".$this->_align."; $bgcolor}'> $this->obs\n";

        return $this->_code;
    }

    function Generate() {
        echo $this->getCode();
    }
}


//
class TextHidden extends TextField {
    var $descriptor = "function TextHidden(\$label, \$name, \$value = '', \$size = '', \$obs = '', \$max = '', \$locked = false, \$js = '')";

    function TextHidden($label, $name, $value = '', $size = '', $obs = '', $max = '', $locked = false, $js = "") {
        $this->TextField($label, $name, $value, $size, $obs, $max, $locked, $js);
        $this->type = 'hidden';
    }
}


//
class PasswordField extends TextField {
    var $descriptor = "function PasswordField(\$label, \$name, \$value, \$size = 10, \$max = '')";
    function PasswordField($label, $name, $value, $size = 10, $max = '') {
        $this->TextField($label, $name, $value, $size);
        $this->type = 'password';
        $this->max = $max;
    }
}


//
class TextArea extends FormField {
    var $descriptor = "function TextArea(\$label, \$name, \$value, \$cols = 40, \$rows = 5, \$locked = false, \$id = \"\")";
    var $rows;
    var $cols;
    var $lock;

    function TextArea($label, $name, $value, $cols = 40, $rows = 5, $locked = false, $id="") {
        $this->FormField($label, $name, $value);
        $this->lock = $locked;

        $this->rows = $rows;
        $this->cols = $cols;
        $this->id = $id;
    }

    function getCode() {

        if ( trim($this->id) =="" ) {
            $this->id = "i_".$this->name;
        }

        $this->_code = "";
        if ($this->lock)    $this->_code .= "<textarea id='".$this->id."' class='XPLOCK' name=\"$this->name\" rows=\"$this->rows\" cols=\"$this->cols\" readonly>$this->value</textarea>";
        else    $this->_code .= "<textarea id='".$this->id."' class='XP' name=\"$this->name\" rows=\"$this->rows\" cols=\"$this->cols\">$this->value</textarea>";
        return $this->_code;
    }

    function Generate() {
        echo $this->getCode();
    }
}

class TextData extends FormField {
    var $descriptor = "function TextData(\$label, \$name, \$value = '', \$size = 8, \$obs = '', \$locked = false, \$js = \"\", \$disabled = \"\", \$onKeyUp=\"\", \$habValJs = true, \$habBotCalendar = true, \$align=\"LEFT\", \$color='' \$id=\"\", \$tipoObjeto=\"DATA\")";
    var $max;
    var $obs;
    var $locked;
    var $js;
    var $disabled;
    var $onKeyUp;
    var $habValJs;
    var $habBotCalendar;
    var $_align = '';
    var $_bgcolor = '';
    var $id = '';

    function TextData($label, $name, $value = '', $size = 8, $obs = '', $locked = false, $js = "", $disabled = "", $onKeyUp="", $habValJs=true, $habBotCalendar=true, $align="LEFT", $bgcolor="", $id="", $onKeyDown="", $objeto="DATA") {
        $this->FormField($label, $name, $value);

        $this->size = $size;
        $this->obs = $obs;
        $this->locked = $locked;
        $this->js = $js;
        $this->disabled = $disabled;
        $this->onKeyUp = $onKeyUp;
        $this->onKeyDown = $onKeyDown;
        $this->habValJs = $habValJs;
        $this->habBotCalendar = $habBotCalendar;
        $this->setAlign($align);
        $this->_bgcolor = trim($bgcolor);
        $this->id = $id;
        $this->objeto = $objeto;
    }

    function setAlign($align) {
        $align = trim(strtoupper($align));
        switch ($align) {
            case 'LEFT': $this->_align = 'left'; break;
            case 'RIGHT': $this->_align = 'right'; break;
            case 'CENTER': $this->_align = 'center'; break;
            default : $this->_align = 'left'; break;
        }
    }

    function getCode() {
        GLOBAL $SISCONF;
        $this->_code = "";

        if ( $this->habBotCalendar == true ) {
            $this->_code .= "\n<link rel=\"stylesheet\" href=\"".$SISCONF['SIS']['COMUM']['HOST']."jsCalendario/dhtmlgoodies_calendar.css?random=20051112\" media=\"screen\"></LINK>";
            $this->_code .= "\n<script type=\"text/javascript\" src=\"".$SISCONF['SIS']['COMUM']['HOST']."jsCalendario/dhtmlgoodies_calendar.js?random=20060118\"></script>";
        }

        if ( $this->habValJs == true ) {
            $this->_code .= "\n<script type=\"text/javascript\" src=\"".$SISCONF['SIS']['COMUM']['HOST']."jsCalendario/validaData.js\"></script>
			<script src=\"".$SISCONF['SIS']['COMUM']['HOST']."arquivosJS/jquery-1.4.2.min.js\" type=\"text/javascript\"></script>
			<script src=\"".$SISCONF['SIS']['COMUM']['HOST']."arquivosJS/jquery.maskedinput-1.1.4.pack.js\" type=\"text/javascript\"></script>
			<script src=\"".$SISCONF['SIS']['COMUM']['HOST']."arquivosJS/jquery.alphanumeric.js\" type=\"text/javascript\"></script>
			<script src=\"".$SISCONF['SIS']['COMUM']['HOST']."arquivosJS/jquery.alphanumeric.pack.js\" type=\"text/javascript\"></script>\n";
        }
		

        $bgcolor = ( $this->_bgcolor != "" ? " background-color:".$this->_bgcolor.";" : "");
        if ( trim($this->id) == "" ) $this->id = "i_".$this->name;

        $this->_code .=
            "<input id=\"".$this->id."\" name=\"".$this->name."\" type=\"text\" value=\"".htmlentities($this->value)."\"";
        if ($this->locked == true) $this->_code .= " readonly class='XPLOCK' ";
        else $this->_code .= " class='XP' ";

        // size
        if ($this->size != "") $this->_code .= " size=\"".$this->size."\"";

        if( $this->objeto == "DATA" ) {
            $len = 10;
        } else {
            $len = 7;
        }
        // max lenght
        $this->_code .= " maxlength=\"".$len."\"";

        // javaScript
        if ( $this->js != "" ) $this->_code .= " ".$this->js;

        $js = "";
        $js2 = "";
        if ( $this->habValJs == true) {
            $js = " onKeyUp=\"validaData( 'up', '".$this->id."', event ); ";
            $js2 = " onKeyDown=\"validaData( 'down', '".$this->id."', event ); ";
        }

        if ( ( $js != "") and ($this->onKeyUp != "" ) ) {
            $js .= $this->onKeyUp."\" ";
        } elseif ( ( $js == "") and ($this->onKeyUp != "" ) ) {
            $js .= " onKeyUp=\"".$this->onKeyUp."\"";
        } else {
            $js .= "\" ";
        }

        if ( ( $js2 != "") and ($this->onKeyDown != "" ) ) {
            $js2 .= $this->onKeyDown."\" ";
        } elseif ( ( $js2 == "") and ($this->onKeyDown != "" ) ) {
            $js2 .= " onKeyDown=\"".$this->onKeyDown."\"";
        } else {
            $js2 .= "\" ";
        }

        $this->_code .= $js.$js2;

        // disabled
        if ( $this->disabled == true ) $this->_code .= " disabled=true ";

        $this->_code .= " style='{text-align: ".$this->_align."; ".$bgcolor."}'> ";

        //calendario
        if ( $this->habBotCalendar == true ) {
        /**
         * Adicionado por diegom em 14/08/2007
         */
            GLOBAL $SGU_INTERNET;
            switch ($SGU_INTERNET) {
                default:
                    $caminhoImagens = $SISCONF['SIS']['COMUM']['IMAGENS'];
                    break;
            }
            $string = "Selecione uma Data";
            $mascara = "dd/mm/yyyy";
            if( $this->objeto == "PARCELAS" ) {
                $mascara = "dd/yyyy";
                $string = "Selecione uma Parcela";
            }

            $this->_code .= "<img src=\"".$caminhoImagens."calendar.jpg\" title='".$string."' style=\"{cursor:pointer;}\" onclick=displayCalendar(document.getElementById('".$this->id."'),'".$mascara."',this,'".$this->objeto."')>";
        }

        // obs
        $this->_code .= " ".$this->obs."\n";

        return $this->_code;
    }

    function Generate() {
        echo $this->getCode();
    }
}

class NewLine extends FormField {
    var $descriptor = "function NewLine(\$label = \"\", \$name = \"\")";

    function NewLine($label = "", $name = "") {
        $this->FormField($label, $name);
    }

    function getCode() {
        $this->_code = "";
        return $this->_code;
    }
    function Generate() {
        echo $this->getCode();
    }
}

//
class CheckBox extends FormField {
    var $descriptor = "function CheckBox(\$label, \$name, \$value, \$checked = false, \$text = null, \$js = '', \$disable = false) ";
    var $checked;
    var $text;
    var $js;
    var $disable;
    var $gerarComTextoComClText;

    function CheckBox($label, $name, $value, $checked = false, $text = null, $js = '', $disable = false,$id="", $gerarComTextoComClText=false) {
        $this->FormField($label, $name, $value);

        $this->checked = $checked;
        $this->text = $text;
        $this->js = $js;
        $this->disable = $disable;
        $this->id = $id;
        $this->gerarComTextoComClText = $gerarComTextoComClText;
    }

    function getCode() {
        if ($this->id !="") {
            $id = $this->id;
        } else {
            $id = $this->name.$this->value;
        }
		
	/* ADDED BY ROBERTO 08/03/2010	
	$file_name = "impRelDensidadeCargo.php";
	
	if ( file_exists ( $file_name )) { 
		$this->_code = "<input id=\"".$id."\" class=\"IGNORE\" name=\"".$this->name."\" type=\"checkbox\" onclick=\"seleciona_todo_listbox()\" value=\"".$this->value."\"";
	} else 
		{
		$this->_code = "<input id=\"".$id."\" class=\"IGNORE\" name=\"".$this->name."\" type=\"checkbox\" value=\"".$this->value."\"";
		}
		 ---------- END ------------ 
		
		*/	

        $this->_code = "<input id=\"".$id."\" class=\"IGNORE\" name=\"".$this->name."\" type=\"checkbox\" value=\"".$this->value."\"";
		
        if ($this->checked) $this->_code .= " checked";
        if ( $this->js != "" ) $this->_code .= " ".$this->js;
        if ( $this->disable == true ) $this->_code .= " disabled=\"".$this->disable."\"";
        $this->_code .= '>';

        #if ($this->text) $this->_code .= '&nbsp;' . $this->text;
        if ($this->gerarComTextoComClText==true) {
            $objText = new Text($this->text,false,$id);
            if ($this->text) $this->_code .= "&nbsp;".$objText->getCode();
        } else {
            if ($this->text) $this->_code .= "&nbsp;<label class=\"XP\" for=".$id.">".$this->text."</label>";
        }

        return $this->_code;
    }

    function Generate() {
        echo $this->getCode();
    }
}
//

class RadioButton extends FormField {
    var $descriptor = "function RadioButton(\$label, \$name, \$value, \$checked = false, \$text, \$js = '', \$disable = false)";
    var $checked;
    var $text;
    var $js;
    var $disable;
    var $gerarComTextoComClText;

    function RadioButton($label, $name, $value, $checked = false, $text, $js = '', $disable = false, $gerarComTextoComClText=false) {
        $this->FormField($label, $name, $value);

        $this->checked = $checked;
        $this->text = $text;
        $this->js = $js;
        $this->disable = $disable;
        $this->gerarComTextoComClText = $gerarComTextoComClText;
    }

    function getCode() {
        $id = "\"".$this->name.$this->value."\"";
        $this->_code = "";
        $this->_code .= "<input ID=".$id." class='IGNORE' name=\"{$this->name}\" type=\"radio\" value=\"{$this->value}\" ".$this->js."";
        if ($this->checked) $this->_code .= ' checked';
        if ($this->disable == true) $this->_code .= ' disabled = true';

        $this->_code .= '>';
        if ($this->gerarComTextoComClText==true) {
            $objText = new Text($this->text,false,$id);
            if ($this->text) $this->_code .= "&nbsp;".$objText->getCode();
        } else {
            if ($this->text) $this->_code .= "&nbsp;<label class=\"XP\" for=".$id.">".$this->text."</label>";
        }
        return $this->_code;
    }

    function Generate() {
        echo $this->getCode();
    }
}


class Separator extends FormField {
    var $descriptor = "function Separator(\$label = null) ";
    function Separator($label = null) {
        $this->FormField($label, null);
    }

    function getCode() {
        $this->_code = "";
        $this->_code .= "<br /><b><label class='XP'>{$this->label}</label></b>";
        return $this->_code;
    }

    function Generate() {
        echo $this->getCode();
    }
}


//
class LookupField extends FormField {
    var $descriptor = "function LookupField(\$label, \$name, \$form, \$module, \$item, \$value = '')";
    var $action;
    var $form;
    var $module;
    var $item;
    var $info;

    function LookupField($label, $name, $form, $module, $item, $value = '') {
        $this->FormField($label, $name, $value);
        $this->form = $form;
        $this->module = $module;
        $this->item = $item;
    }

    function getCode() {
        $this->_code = "";

        $actionLU = "module={$this->module}"."&action=lookup" . "&item={$this->item}".
            "&lookup_form={$this->form->name}"."&lookup_field={$this->name}"."&lookup_text={$this->name}_text";

        $actionAC = "module={$this->module}"."&action=autocomplete"."&item={$this->item}";


        $this->_code .= "<table cellspacing=\"0\" cellpadding=\"0\" border=\"0\">\n";
        $this->_code .= "  <tr>\n";
        $this->_code .= "    <td>\n";
        $this->_code .= "      <input class='XP' name=\"{$this->name}\" type=\"text\" size=\"8\" " . " onBlur=\"AutoComplete('$actionAC'," .
            "document.{$this->form->name}.{$this->name}," . "document.{$this->form->name}.{$this->name}_text)\" " . "value=\"$this->value\">\n";
        $this->_code .= "    </td>\n";
        $this->_code .= "    <td>\n";
        $this->_code .= "      &nbsp;&nbsp;<input class='XP' name=\"{$this->name}_text\" " . "type=\"text\" size=\"30\" value=\"$this->info\">\n";
        $this->_code .= "    </td>\n";
        $this->_code .= "    <td>\n";
        $this->_code .= "      &nbsp;<input class='XP' type=\"button\" value=\"...\" " . "onClick=\"Lookup('$actionLU')\">\n";
        $this->_code .= "    </td>\n";
        $this->_code .= "  </tr>\n";
        $this->_code .= "</table>\n";

        return $this->_code;
    }

    function Generate() {
        echo $this->getCode();
    }

}

//DESENV
//classe new combo
class comboBoxNew extends FormField {
    var $descriptor = "function ComboBoxNew(\$label, \$name, \$value, \$options, \$selecione = 'Selecione', \$multiplo=false, \$size ='',\$js='', \$disable=false)";
    var $options;
    var $multiplo;
    var $size;
    var $disable;

    function ComboBoxNew($label, $name, $value, $options, $selecione = 'Selecione',$multiplo=false,$size ='',$js="", $disable=false) {
        $this->FormField($label, $name, $value);

        $this->disable = $disable;
        $this->multiplo = $multiplo;
        $this->size = $size;
        $this->js = $js;
        $this->options = $options;
        @reset($this->options);
        $o = @current($this->options);

        // array multi-dimensional (result from db query)
        if (is_array($o)) {
            $default = array('', $selecione);

            $this->options = array_merge(array($default), $this->options);
        } else {
            if ($this->size =='') {
            // simple array
                $default = array('' => $selecione);
                $this->options = array_merge($default, $this->options);
            }
        }
    }

    function getCode() {
        if ($this->multiplo == true) {
            $atributoMultiplo ='multiple';
        } else {
            $atributoMultiplo ='';
        }

        if ($this->size !='') {
            $atributoSize ='size = '.$this->size ;
            $script = '';
        } else {
            $atributoSize ='';
            $script = " onChange=\"ComboBox_onSelectionChange('{$this->label}',this.form.elements['{$this->name}'],this.form.elements['{$this->name}'])\"";
        }
        $ret ="";
        $id = "i_".$this->name;
        $ret = "<select class='XP' id=\"".$id."\" name=\"{$this->name}\" " .$atributoMultiplo ." " .$atributoSize . " " . $script ;
        if ( $this->js != "" ) {$ret .= $this->js;}
        if ( $this->disable == true ) $ret .= " disabled=true";
        $ret .= "> \n";
        foreach(array_keys($this->options) as $k) {
            $o = $this->options[$k];

            if (is_array($o)) {
                list ($id, $name) = $o;

                if (strlen($name) > 40) {
                    $name = substr($name, 0, 37) . '...';
                }

                if (is_array($this->value)) {
                    foreach($this->value as $key) {
                        $ret .= "<option value=\"$id\"";
                        if ($key == $id) {
                            $ret .= ' selected';
                        }
                    }
                    reset($this->value);
                }else {
                    $ret .= "<option value=\"$id\"";
                    if ("$id" == "{$this->value}") {
                        $ret .= ' selected';
                    }
                }

                $ret .= ">$name</option>\n";
            } else {
                $ret .= "<option value=\"$k\"";

                if ("$k" == "{$this->value}") {
                    $ret .= ' selected';
                }

                $ret .= ">$o</option>\n";
            }
        }

        $ret .= "</select>\n";
        return $ret;
    }

    function Generate() {
        echo $this->getCode();
    }
}

class ComboBox extends FormField {

    var $descriptor = "function ComboBox(\$label, \$name, \$value, \$options, \$selecione = 'Selecione', \$disable = false, \$js = '', \$id = '',\$agrupar = false) ";
    var $options;
    var $js;
    var $disable;
    var $agrupar;
    var $multiplo;
    var $size;

    function ComboBox($label, $name, $value, $options, $selecione = 'Selecione', $disable = false, $js = '', $id="",$agrupar = false, $multiplo = false) {
        $this->FormField($label, $name, $value);
        $this->disable = $disable;
        $this->js = $js;
        $this->options = $options;
        @reset($this->options);
        $o = @current($this->options);
        $this->id = $id;
        $this->agrupar = $agrupar;
        $this->multiplo = $multiplo;

        //verifica se eh necessario colocar o selecione
        if (!empty($selecione)) {
        // array multi-dimensional (result from db query)
            if (is_array($o)) {
                $default = array('', $selecione);
                $this->options = array_merge(array($default), $this->options);
            } else {
            // simple array
                $default = array('' => $selecione);
                $this->options = array_merge($default, $this->options);
            }
        }
    }

    function getCode() {

        $this->_code = "";

        if ($this->multiplo == true) {
            $atributoMultiplo ='multiple';
        } else {
            $atributoMultiplo ='';
        }

        if ( trim($this->id) =="" ) {
            $this->id = "i_".$this->name;
        }

        if ($this->size !='') {
            $atributoSize ='size = '.$this->size ;
        } else {
            $atributoSize ='';
        }

        $this->_code .= "\n<select id='".$this->id."' ".$atributoMultiplo." ".$atributoSize." CLASS='XP' name=\"{$this->name}\"";
        if ( $this->js != "") $this->_code .= " ".$this->js;
        if ( $this->disable == true ) $this->_code .= " disabled=true";
        if ( trim($this->estilo) !="" ) $this->_code .= " ".$this->estilo;
        $this->_code .= ">\n";

        if($this->agrupar == false) {
            foreach(array_keys($this->options) as $k) {
                $o = $this->options[$k];
                if (is_array($o)) {
                    list ($id, $name) = $o;
                    if (strlen($name) > 40) $name = substr($name, 0, 37) . '...';
                    $this->_code .= "<option value=\"$id\"";
                    if($this->multiplo && is_array($this->value)) {
                        if (in_array($id, $this->value)) $this->_code .= ' selected';
                    } else {
                        if ($id == $this->value) $this->_code .= ' selected';
                    }
                    $this->_code .= ">$name</option>\n";
                } else {
                    $this->_code .= "<option value=\"$k\"";
                    if($this->multiplo && is_array($this->value)) {
                        if (in_array($k, $this->value)) $this->_code .= ' selected';
                    } else {                       
                        if ($k == $this->value) $this->_code .= ' selected';
                    }
                    $this->_code .= ">$o</option>\n";
                }
            }
        } else {
            foreach($this->options as $chave => $valor) {
                if(!is_array($valor)) {
                    $this->_code .= "<option value=\"".$chave."\"";
                    if($this->multiplo && is_array($this->value)) {
                        if (in_array($chave, $this->value)) $this->_code .= ' selected';
                    } else {
                        if ($chave == $this->value) $this->_code .= ' selected';
                    }
                    $this->_code .= ">".$valor."</option>\n";
                } else {
                    $this->_code .= "<OPTGROUP label=\"$chave\">";
                    foreach($valor as $id =>$dados) {
                        $this->_code .= "<option value=\"$id\"";
                        if($this->multiplo && is_array($this->value)) {
                            if (in_array($id, $this->value)) $this->_code .= ' selected';
                        } else {
                            if ($id == $this->value) $this->_code .= ' selected';
                        }
                        $this->_code .= ">$dados</option>\n";
                    }
                    $this->_code .= " </OPTGROUP>\n";
                }
            }

        }
        $this->_code .= "</select>\n";
        return $this->_code;
    }

    function Generate() {
        echo $this->getCode();
    }
}


//
class Lookup extends FormField {
    var $descriptor = "function Lookup(\$label, \$name, &\$db, \$sql, \$value)";
    var $keys;

    function Lookup($label, $name, &$db, $sql, $value) {
        $q = new genericQuery($db);
        $q->query($sql);

        $this->FormField($label, $name);
        while ($rw = $q->fetchrow()) {
            $this->keys[] = $rw[$value];
        }
    }

    function getCode() {
        $this->_code = "";
        $this->_code .= "<select class='XP' name = \"$this->name\">";
        foreach(array_keys($this->keys) as $k) {
            $l = $this->keys[$k];

            if (is_array($l)) {
                list ($id, $name) = $l;
                if (strlen($name) > 40) $name = substr($name, 0, 37) . '...';
                $this->_code .= "<option value=\"$id\"";
                if ("$id" == "{$this->keys}") $this->_code .= ' selected';
                $this->_code .= ">$name</option>\n";
            } else {
                $this->_code .= "<option value=\"$k\"";
                if ("$k" == "{$this->keys}") $this->_code .= ' selected';
                $this->_code .= ">$l</option>\n";
            }
        }
        $this->_code .= "</select>\n";
        return $this->_code;
    }

    function Generate() {
        echo $this->getCode();
    }
}


class radio extends FormField {
    var $descriptor = "function radio(\$label, \$name, \$value, \$checked = false, \$text = null)";
    var $checked;
    var $text;

    function radio($label, $name, $value, $checked = false, $text = null) {
        $this->FormField($label, $name, $value);

        $this->checked = $checked;
        $this->text = $text;
    }

    function Generate() {
        echo "<input class='IGNORE' name=\"{$this->name}\" type=\"radio\" value=\"{$this->value}\"";

        if ($this->checked) {
            echo ' checked';
        }
        echo '>';

        if ($this->text) {
            echo '&nbsp;' . $this->text;
        }
    }
}


class comboDBNew extends comboDB {
    var $descriptor = "function ComboDBNew(\$label, \$name, \$value, \$qry, \$chave, \$valor, \$selecione = 'Selecione')";
    var $options;

    function ComboDBNew($label, $name, $value, $qry, $chave, $valor,$selecione = 'Selecione') {
        $this->FormField($label, $name, $value);

        //monta um array com os valores da query
        while ($rw = $qry->fetchrow()) {
            $this->options[$rw[$chave]] = $rw[$valor];

        }


        // simple array
        $default = array('' => $selecione);
        $this->options = array_merge($default, $this->options);
        return $this->options;

    }
}

// classe que monta um combo a partir de um QUERY passado por parâmetro
class comboDB extends ComboBox {
    var $descriptor = "function comboDB(\$name, &\$qry, \$value, \$campoChave, \$campoValor, \$selecione='selecione', \$js='')";
    var $options;

    function comboDB($name, &$qry, $value, $campoChave, $campoValor, $selecione='selecione', $js='') {
        $this->name = $name;
        $this->value = $value;
        $this->label = $name;
        $this->js = "";
        if ( trim($js) != "") {
            $this->js = $js;
        }

        $default = array('', $selecione);
        $this->options = array_merge(array($default), $this->options);

        if ($selecione=='selecione') {
            $this->options = array();
        }


        while ($rw = $qry->fetchrow()) {
            $campo = 0;
            $this->options[$rw[$campoChave]] = $rw[$campoValor];
        }

    //return $this->options;
    }
}

class fieldSet {
    var $alinhamentoHorizontal;
    var $alinhamentoVertical;
    var $titulo;
    var $conteudo;
    var $largura;
    var $altura;
    var $mostrarAsterisco;
    var $textoInformativo;
    function fieldSet($conteudo, $altura, $largura, $titulo="", $alinhamentoHorizontal="center", $alinhamentoVertical="center", $mostrarAsterisco=false, $textoInformativo="") {
        $this->alinhamentoHorizontal = $alinhamentoHorizontal;
        $this->alinhamentoVertical = $alinhamentoVertical;
        $this->largura = $largura;
        $this->altura = $altura;
        $this->titulo = $titulo;
        $this->conteudo = $conteudo;
        $this->mostrarAsterisco = $mostrarAsterisco;
        $this->textoInformativo = $textoInformativo;
    }

    function getCode() {
        $objText = new Text($this->titulo, $this->mostrarAsterisco);
        $html = ""
            ."<fieldset title=\"".$this->textoInformativo."\" style=\"height: ".$this->altura."; width:".$this->largura.";\">"
            ."<legend>".$objText->getCode()."</legend>"
            ."<div style=\"width:100%; height: 100%; vertical-align:".$this->alinhamentoVertical."; "
            ."text-align:".$this->alinhamentoHorizontal.";\">".$this->conteudo."</div>"
            ."</fieldset>";
        return $html;
    }
    function Generate() {
        echo $this->getCode();
    }
}

/**
 *
 *   seletorAno
 *
 *   classe para montar uma combo onde o usuário pode alternar o ano.
 *   @package package_form
 *   @author Henrique Girardi dos Santos
 */
class seletorAno {
    var $nome;
    var $ano;
    var $anoInicial;
    var $mostrarScript;
    var $disabled;
    var $js;
    var $onClick;
    var $submete;
    var $_code;
    var $descriptor;

    function seletorAno($nome, $ano, $anoInicial = "", $mostrarScript = true, $disabled = false, $js = "", $onClick = "", $submete = false) {
        $this->descriptor = "function seletorAno(\$nome, \$ano, \$anoInicial, \$mostrarScript = true, \$disabled = false, \$js = \"\", \$onClick = \"\", \$submete = false)";
        $this->nome = $nome;
        $this->ano = $ano;
        $this->anoInicial = $anoInicial;
        $this->disabled = $disabled;
        $this->js = $js;
        $this->onClick = $onClick;
        $this->submete = $submete;
        $this->mostrarScript = $mostrarScript;

        $script = new JavaScripts();

        if ($this->mostrarScript == true) echo $script->scriptSeletorAno();
        if ($this->anoInicial == "") $this->anoInicial = date("Y");
        if ($this->ano == "") $this->ano = $this->anoInicial;
        if ($this->submete === true) $this->submete = 1; else $this->submete = 0;
        if ($this->onClick != "") $this->onClick = "; ".$this->onClick;
        if ($this->disabled === true) $this->disabled = "disabled";
        $this->_code = "";
    }

    function getCode() {
    //aqui foi mudado a cor do ANO .  COR ANTERIOR #990000  2e70de
        $this->_code = "
        <table border=0 cellpadding=0 cellspacing=0>
            <tr>
                <td rowspan=2 align=center valign=top class=mostraCorFundo>
                    <input type=\"text\" id=\"i_".$this->nome."\" name=".$this->nome." size=4 maxlength=4 readonly value=\"".$this->ano."\" style=\"{border:0px; color:#000099; text-align:center; background-color: transparent; font-size:18}\">
                </td>

                <td class=mostraCorFundo>
                        <input type=\"button\" id=\"i_".$this->nome."BtMais\" name=\"".$this->nome."BtMais\" ".$this->js." ".$this->disabled." OnClick=\"mudarAno('mais','i_".$this->nome."', ".$this->submete.")".$this->onClick.";\" style=\"{height:12px; width:16px; background-image:url(../../imagens/setaPraCima.gif); background-position: center; background-repeat:no-repeat;}\" >
                </td>
            <tr>
                <td class=mostraCorFundo>
                    <input type = \"button\" id=\"i_".$this->nome."BtMenos\" name=\"".$this->nome."BtMenos\" ".$this->js." ".$this->disabled." OnClick=\"mudarAno('menos','i_".$this->nome."', ".$this->submete.")".$this->onClick.";\" style=\"{height:12px; width:16px; background-image:url(../../imagens/setaPraBaixo.gif); background-position: center; background-repeat:no-repeat;}\">
                </td>
            </tr>
        </table>";
        return $this->_code;
    }

    function Generate() {
        echo $this->getCode();
    }

    function getDescriptor() {
        return $this->descriptor;
    }

}

class seletorMes {
    var $nome;
    var $mes;
    var $mesInicial;
    var $mostrarScript;
    var $disabled;
    var $js;
    var $onClick;
    var $submete;
    var $_code;
    var $descriptor;

    function seletorMes($nome, $mes, $mesInicial, $mostrarScript = true, $disabled = false, $onClick = "", $submete = false) {
        $this->descriptor = "function seletorMes(\$nome, \$mes, \$mesInicial, \$mostrarScript = true, \$disabled = false, \$js = \"\", \$onClick = \"\", \$submete = false)";
        $this->nome = $nome;
        $this->mes = $mes;
        $this->mesInicial = $mesInicial;
        $this->disabled = $disabled;
        $this->onClick = $onClick;
        $this->submete = $submete;
        $this->mostrarScript = $mostrarScript;

        $script = new JavaScripts();

        if ($this->mostrarScript == true) {
            echo $script->scriptSeletorMes();
        }
        if ($this->mesInicial == "") {
            $this->mesInicial = date("n");
        }
        if ($this->mes == "") {
            $this->mes = $this->mesInicial;
        }
        if ($this->submete === true) {
            $this->submete = 1;
        }else {
            $this->submete = 0;
        }
        if ($this->onClick != "") {
            $this->onClick = ", ".$this->onClick;
        }
        if ($this->disabled === true) {
            $this->disabled = "disabled";
        }
        $this->_code = "";


    }

    function getCode() {
        $this->_code = "
        <table border=0 cellpadding=0 cellspacing=0>
            <tr>
                <td rowspan=2 align=center valign=top class=mostraCorFundo>
                    <input type=\"text\" id=\"i_".$this->nome."\" name=".$this->nome." size=4 maxlength=4 readonly value=\"".$this->mes."\" style=\"{border:0px; color:#990000; text-align:center; background-color: transparent; font-size:18}\">
                </td>

                <td class=mostraCorFundo>
                        <input type=\"button\" name=\"".$this->nome."BtMais\" ".$this->js." ".$this->disabled." OnClick=\"mudarMes('mais','i_".$this->nome."', ".$this->submete.")".$this->onClick.";\" style=\"{height:12px; width:16px; background-image:url(../../imagens/setaPraCima.gif); background-position: center; background-repeat:no-repeat;}\" >
                </td>
            <tr>
                <td class=mostraCorFundo>
                    <input type = \"button\" name=\"".$this->nome."BtMenos\" ".$this->js." ".$this->disabled." OnClick=\"mudarMes('menos','i_".$this->nome."', ".$this->submete.")".$this->onClick.";\" style=\"{height:12px; width:16px; background-image:url(../../imagens/setaPraBaixo.gif); background-position: center; background-repeat:no-repeat;}\">
                </td>
            </tr>
        </table>";
        return $this->_code;
    }

    function Generate() {
        echo $this->getCode();
    }

    function getDescriptor() {
        return $this->descriptor;
    }

}

class seletorEdicao {
    var $nome;
    var $edicao;
    var $edicaoInicial;
    var $mostrarScript;
    var $disabled;
    var $js;
    var $onClick;
    var $submete;
    var $_code;
    var $descriptor;

    function seletorEdicao($nome, $edicao, $edicaoInicial = 0, $mostrarScript = true, $disabled = false, $onClick = "", $submete = false) {
        $this->descriptor = "function seletorEdicao(\$nome, \$edicao, \$edicaoInicial, \$mostrarScript = true, \$disabled = false, \$js = \"\", \$onClick = \"\", \$submete = false)";
        $this->nome = $nome;
        $this->edicao = $edicao;
        $this->edicaoInicial = $edicaoInicial;
        $this->disabled = $disabled;
        $this->onClick = $onClick;
        $this->submete = $submete;
        $this->mostrarScript = $mostrarScript;

        $script = new JavaScripts();

        if ($this->mostrarScript == true) {
            echo $script->scriptSeletorEdicao($edicaoInicial);
        }

        if ($this->edicao == "") {
            $this->edicao = $this->edicaoInicial;
        }

        if ($this->submete === true) {
            $this->submete = 1;
        }else {
            $this->submete = 0;
        }
        if ($this->onClick != "") {
            $this->onClick = ", ".$this->onClick;
        }
        if ($this->disabled === true) {
            $this->disabled = "disabled";
        }
        $this->_code = "";

    }

    function getCode() {
        $this->_code = "
        <table border=0 cellpadding=0 cellspacing=0>
            <tr>
                <td rowspan=2 align=center valign=top class=mostraCorFundo>
                    <input type=\"text\" id=\"i_".$this->nome."\" name=".$this->nome." size=4 maxlength=4 readonly value=\"".$this->edicao."\" style=\"{border:0px; color:#990000; text-align:center; background-color: transparent; font-size:18}\">
                </td>

                <td class=mostraCorFundo>
                        <input type=\"button\" name=\"".$this->nome."BtMais\" ".$this->js." ".$this->disabled." OnClick=\"mudarEdicao('mais','i_".$this->nome."', ".$this->submete.")".$this->onClick.";\" style=\"{height:12px; width:16px; background-image:url(../../imagens/setaPraCima.gif); background-position: center; background-repeat:no-repeat;}\" >
                </td>
            <tr>
                <td class=mostraCorFundo>
                    <input type = \"button\" name=\"".$this->nome."BtMenos\" ".$this->js." ".$this->disabled." OnClick=\"mudarEdicao('menos','i_".$this->nome."', ".$this->submete.")".$this->onClick.";\" style=\"{height:12px; width:16px; background-image:url(../../imagens/setaPraBaixo.gif); background-position: center; background-repeat:no-repeat;}\">
                </td>
            </tr>
        </table>";
        return $this->_code;
    }

    function Generate() {
        echo $this->getCode();
    }

    function getDescriptor() {
        return $this->descriptor;
    }

}

class botaoDuvida {
    var $descriptor;
    var $mensagem;

    function botaoDuvida ($pagina, $mensagem, $width=500,$height=500,$lpos=0,$rpos=0,$resize=0,$scrolls=1,$menubar=0,$toolbar=0) {
        $this->descriptor = "function importEndButton(\$mensagem)";
        $this->pagina = $pagina;
        $this->mensagem = $mensagem;

        $script = new JavaScripts();
        echo $script->writeDoNewWindow($width, $height, $lpos, $rpos, $resize, $scrolls, $menubar, $toolbar);
        $this->_code = "";
    }

    function getCode() {
        GLOBAL $SISCONF;
        $imagem = $SISCONF['SIS']['COMUM']['IMAGENS']."interrogacao.gif";
        $javaScript = "onClick=\"doOpenNewWindow('".$this->pagina."')\"";
        $this->_code = "";
        $this->_code .= "<img src=\"".$imagem."\" title='".$this->mensagem."' ".$javaScript." style=\"{cursor:pointer;}\" >"; //height=\"15\" width=\"15\"

        return $this->_code;
    }

    function Generate() {
        echo $this->getCode();
    }

    function getDescriptor() {
        return $this->descriptor;
    }
}

class TextValor extends FormField {
    var $descriptor = "function TextValor(\$label, \$name, \$value = '', \$obs = '', \$locked = false, \$js = \"\", \$disabled = \"\" \$align=\"LEFT\", \$color='' \$id=\"\")";
    var $max;
    var $obs;
    var $locked;
    var $js;
    var $disabled;
    var $_align = '';
    var $_bgcolor = '';
    var $id = '';
    var $size = "";
    var $maxLength = "";

    function TextValor($param) {
        $this->FormField($param["LABEL"]="", $param["NAME"], $param["VALUE"]);

        if(isset($param["SIZE"])) {$this->size = $param["SIZE"];}
        if(isset($param["OBS"])) {$this->obs = $param["OBS"];}
        if(isset($param["LOCKED"])) {$this->locked = $param["LOCKED"];}
        if(isset($param["JS"])) {$this->js = $param["JS"];}
        if(isset($param["DISABLED"])) {$this->disabled = $param["DISABLED"];}
        if(isset($param["ALIGN"])) {$this->setAlign($param["ALIGN"]);}
        if(isset($param["BGCOLOR"])) {$this->_bgcolor = trim($param["BGCOLOR"]);}
        if(isset($param["ID"])) {$this->id = $param["ID"];}
        if(isset($param["MAXLENGTH"])) {$this->maxLength = $param["MAXLENGTH"];}
    // $this->lerJs = $param["LERJS"];
    }

    function setAlign($align) {
        $align = trim(strtoupper($align));
        switch ($align) {
            case 'LEFT': $this->_align = 'LEFT'; break;
            case 'RIGHT': $this->_align = 'RIGHT'; break;
            case 'CENTER': $this->_align = 'CENTER'; break;
            default : $this->_align = 'LEFT'; break;
        }
    }

    function getCode() {
        GLOBAL $SISCONF;
        $this->_code = "";

        $bgcolor = ( $this->_bgcolor != "" ? " background-color:".$this->_bgcolor.";" : "");
        if ( trim($this->id) == "" ) $this->id = "i_".$this->name;

        $this->_code .=
            "<input id=\"".$this->id."\" name=\"".$this->name."\" type=\"text\" value=\"".htmlentities($this->value)."\"";
        if ($this->locked == true) $this->_code .= " READONLY CLASS='XPLOCK' ";
        else $this->_code .= " CLASS='XP' ";

        // size
        if ($this->size != "") $this->_code .= " size=\"".$this->size."\"";

        // max lenght
        $this->_code .= " maxlength=\"".$this->maxLength."\"";

        // javaScript
        $js = "";
        $js = " onKeyUp=\"formataValor(this);\" ";
        $js .= $this->js;

        $this->_code .= $js;
        // disabled
        if ( $this->disabled == true ) $this->_code .= " DISABLED=true ";

        //style
        $this->_code .= " STYLE='{text-align: ".$this->_align."; ".$bgcolor."}'> ";

        // obs
        $this->_code .= " ".$this->obs."\n";

        $objScript = new JavaScripts();
        echo $objScript->scriptTextValor();

        return $this->_code;
    }

    function Generate() {
        echo $this->getCode();
    }
}
?>