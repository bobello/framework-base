<?php
require_once "comum/sessao/configsis.inc.php";

class JavaScripts
{
    function writeDoNewWindow($width=750,$height=550,$lpos=0,$rpos=0,$resize=1,$scrolls=1,$menubar=1,$toolbar=1)
    {
        echo "\n<script type=text/javascript>".
            "\n  function doOpenNewWindow(str) {".
            "\n  x = window.open(str,\"\",\"resizable=$resize,width=$width,height=$height,screenX=1, screenY=1,scrollbars=$scrolls, menubar=$menubar, toolbar=$toolbar\");".
            "\n  x.moveTo($lpos,$rpos);".
            "\n  x.focus();".
            "\n  }".
            "\n</script>";
    }

    function scriptSeletorAno()
    {
        $js = "";
        $js .= "\n <script language='javascript'>";
        $js .= "\n function mudarAno(operacao, id, submete) {";
        $js .= "\n      combo = document.getElementById(id);";
        $js .= "\n      form = combo.form;";
        $js .= "\n      if(operacao == 'mais') {";
        $js .= "\n          combo.value = parseInt(combo.value) + 1;";
        $js .= "\n      }";
        $js .= "\n      if(operacao == 'menos') {";
        $js .= "\n          combo.value = parseInt(combo.value) - 1;";
        $js .= "\n      }";
        $js .= "\n      if (submete) {";
        $js .= "\n          form.submit();";
        $js .= "\n      }";
        $js .= "\n }";
        $js .= "\n </script>";
        return $js;
    }
	

	function scriptSeletorMes()
    {
        $js = "";
        $js .= "\n <script language='javascript'>";
        $js .= "\n function mudarMes(operacao, id, submete) {";
        $js .= "\n      combo = document.getElementById(id);";
        $js .= "\n      form = combo.form;";
        $js .= "\n      if(operacao == 'mais') {";
		$js .= "\n      	if (combo.value < 12){ ";
        $js .= "\n          	combo.value = parseInt(combo.value) + 1;";
		$js .= "\n      	}else{ ";
		$js .= "\n      		combo.value = 1";
        $js .= "\n      	}";
        $js .= "\n      }";
        $js .= "\n      if(operacao == 'menos') {";
		$js .= "\n      	if (combo.value > 1){";
        $js .= "\n          	combo.value = parseInt(combo.value) - 1;";
		$js .= "\n      	}else{ ";
		$js .= "\n      		combo.value = 12";
        $js .= "\n      	}";
        $js .= "\n      }";
        $js .= "\n      if (submete) {";
        $js .= "\n          form.submit();";
        $js .= "\n      }";
        $js .= "\n }";
        $js .= "\n </script>";
        return $js;
    }

    function scriptSeletorEdicao($edicaoInicial = 0)
    {
        $js = "";
        $js .= "\n <script language='javascript'>";
        $js .= "\n function mudarEdicao(operacao, id, submete) {";
        $js .= "\n      combo = document.getElementById(id);";
        $js .= "\n      form = combo.form;";
        $js .= "\n      if(operacao == 'mais') {";
        $js .= "\n              combo.value = parseInt(combo.value) + 1;";
        $js .= "\n      }";
        $js .= "\n      if(operacao == 'menos') {";
        $js .= "\n          if (combo.value > ".$edicaoInicial."){";
        $js .= "\n              combo.value = parseInt(combo.value) - 1;";
        $js .= "\n          }else{ ";
        $js .= "\n              combo.value = ".$edicaoInicial.";";
        $js .= "\n          }";
        $js .= "\n      }";
        $js .= "\n      if (submete) {";
        $js .= "\n          form.submit();";
        $js .= "\n      }";
        $js .= "\n }";
        $js .= "\n </script>";
        return $js;
    }
    
    function scriptTextValor()
    {
    	$js = "";
    	$js .= "\n <script language='javascript'>";
    	
    	$js .= "\n function formataValor(campo)";
		$js .= "\n {";
		$js .= "\n     campo.value = filtraCampo(campo);";
		$js .= "\n     vr = campo.value;";
		$js .= "\n     tam = vr.length;";
		$js .= "\n ";
		$js .= "\n     if ( tam <= 2 ){";
		$js .= "\n         campo.value = vr ; }";
		$js .= "\n     if ( (tam > 2) && (tam <= 5) ){";
		$js .= "\n         campo.value = vr.substr( 0, tam - 2 ) + ',' + vr.substr( tam - 2, tam ) ; }";
		$js .= "\n     if ( (tam >= 6) && (tam <= 8) ){";
		$js .= "\n         campo.value = vr.substr( 0, tam - 5 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }";
		$js .= "\n     if ( (tam >= 9) && (tam <= 11) ){";
		$js .= "\n         campo.value = vr.substr( 0, tam - 8 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }";
		$js .= "\n     if ( (tam >= 12) && (tam <= 14) ){";
		$js .= "\n         campo.value = vr.substr( 0, tam - 11 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ; }";
		$js .= "\n     if ( (tam >= 15) && (tam <= 18) ){";
		$js .= "\n         campo.value = vr.substr( 0, tam - 14 ) + '.' + vr.substr( tam - 14, 3 ) + '.' + vr.substr( tam - 11, 3 ) + '.' + vr.substr( tam - 8, 3 ) + '.' + vr.substr( tam - 5, 3 ) + ',' + vr.substr( tam - 2, tam ) ;}";
		$js .= "\n }";
		
		$js .= "\n function filtraCampo(campo)";
		$js .= "\n {";
		$js .= "\n     var s = '';";
		$js .= "\n     var cp = '';";
		$js .= "\n     var regra = new RegExp('[0-9]');";
		$js .= "\n     vr = campo.value;";
		$js .= "\n     tam = vr.length;";
		$js .= "\n ";
		$js .= "\n     for (i = 0; i < tam ; i++) {";
		$js .= "\n         var conferir = regra.exec(vr.substring(i,i + 1));";
		$js .= "\n         if (vr.substring(i,i + 1) != \"/\" && vr.substring(i,i + 1) != '-' && vr.substring(i,i + 1) != '.'  && vr.substring(i,i + 1) != ',' && conferir != null){";
		$js .= "\n             s = s + vr.substring(i,i + 1);}";
		$js .= "\n     }";
		$js .= "\n     campo.value = s;";
		$js .= "\n     return cp = campo.value";
		$js .= "\n }";
    	$js .= "\n </script>";
		return $js;
    }
}

/*     Javascript para gerenciar os clicks no check box e na linha  */
class gerenciamentoGrids
{
    var $classesDeMarcacao=array();
    var $js;

    function gerenciamentoGrids( $classesDeMarcacao=array() , $arrayDadosMarcacao = array() )
    {
        //Passa para a variável JS as variáveis a serem declaradas
        $this->_declaracaoVariaveis( $classesDeMarcacao );
        //Marca os CheckBox que podem estar marcados
        $this->checarCheckBox( $arrayDadosMarcacao );
    }

    function _declaracaoVariaveis( $classesDeMarcacao )
    {
        $this->classesDeMarcacao = $classesDeMarcacao;

        //Variáveis globais
        $this->js = ""
        ." var arrayStyles = new Array(); \n"
        ." var idLinhaAtual; \n"
        ." var idGridAtual; \n"
        ." var objCheck; \n"
        ." var classesDeMarcacao = new Array(); \n";
        foreach( $this->classesDeMarcacao as $i => $classe )
        {
            $this->js .= " classesDeMarcacao.push('" .$classe. "'); \n";
        }
    }

    function checarCheckBox( $arrayLinhas = array() )
    {
        if ( count($arrayLinhas)>0 )
        {
            $this->js .= ""
            ." var linhaASerMarcada; \n";

            foreach( $arrayLinhas as $i => $idLinha )
            {
                $this->js .= ""
                ." linhaASerMarcada = document.getElementById('".$idLinha."'); \n"
                ." linhaASerMarcada.onclick();\n"
                ." linhaASerMarcada.onclick();\n";
            }

            $this->js .= "linhaASerMarcada = null; \n";
        }

    }

    function gerenciarMarcacaoLinhaECheckBox()
    {

        //Declaração antiga da função principal
        //." function marcarLinhaECheckBox( linha , idCheckBox , idxColComCheckBox , styleCelComCheck , styleOutrasCelulas , desmarcarCheckBoxAnterior , idGrid )\n "
        $this->js .= ""
            ." function marcarLinhaECheckBox( linha , idCheckBox , desmarcarCheckBoxAnterior , idGrid )\n"
            ." {\n"
            ."      objCheck = document.getElementById( idCheckBox );\n"    //Pega o CheckBox que tem que ser marcado
            ."      var celulas = linha.getElementsByTagName( 'td' );\n"    //Retorna todas as células da linha que foi passada por parâmetro
            ."      var numeroCelulas = celulas.length;\n"                  //Retorna o número de células
            ."      var c = 0;\n"
            ."      idGridAtual = idGrid;\n"
            ."      idLinhaAtual = linha.id;\n"

            ."      if (objCheck.checked==true)\n"
            ."      {\n"
            //Inverte o valor do checkbox checado pois quando se clica no checkbox, logo após este evento é acionado o evento onclick da linha
            ."          inverterCheckBox( objCheck );\n"
            //Aplica o estilo as células da linha que continham o checkbox checado
            ."          aplicaEstiloAnterior( desmarcarCheckBoxAnterior );\n"
            ."      }\n"
            ."      else\n"
            ."      {\n"
            ."          if (arrayStyles.length!=0)\n"
            ."          {\n"
            //Aplica o estilo as células da linha que continham o checkbox checado
            ."              aplicaEstiloAnterior( desmarcarCheckBoxAnterior );\n"
            //Descheca o checkbox checado anteriormente
            ."              if (desmarcarCheckBoxAnterior==1)\n"
            ."              {\n"
            ."                  deschecaCheckBoxAnterior();\n"
            ."              }\n"
            ."          }\n"
            //Guarda as classes de estilo, o id do checkbox, id da linha e o id da tabela
            ."          guardaDadosAnterior( linha, idCheckBox, desmarcarCheckBoxAnterior);\n"
            //Aplica o estilo as linhas
            ."          objCheck.checked = true;\n"
            ."          for (c = 0; c < numeroCelulas; c++)\n"
            ."          {\n"
            ."              celulas[c].className = classesDeMarcacao[c];\n"
            ."          }\n"
            ."      }\n"
            ." }\n\n"

            //Apenas descheca o checbox
            ." function inverterCheckBox( objCkeckBox )\n"
            ." {\n"
            ."      objCkeckBox.checked = !objCkeckBox.checked;\n"
            ." }\n\n"

            //Descheca o último checkbox do grid atual
            ." function deschecaCheckBoxAnterior()\n"
            ." {\n"
            ."      for( c=0 ; c<arrayStyles.length ; c++ )\n"
            ."      {\n"
            //Percorre o array de estilos para buscar o último checkbox checado para deschecar o checkbox
            ."          if( idGridAtual == arrayStyles[c][3] )\n"
            ."          {\n"
            ."              var objCheckBox = document.getElementById(arrayStyles[c][2]);\n"
            ."              objCheckBox.checked = false;\n"
            ."          }\n"
            ."      }\n"
            ." }\n\n"

            //Aplica o estilo anterior das células as células
            ." function aplicaEstiloAnterior( desmarcarCheckBoxAnterior )\n"
            ." {\n"

            ."      var objLinhaAnterior;\n"
            ."      var celulasAnteriores;\n"
            ."      var numeroCelulas;\n"

            ."      for(c=0;c<arrayStyles.length;c++){\n"
            ."          if( (((desmarcarCheckBoxAnterior==0) && (idLinhaAtual==arrayStyles[c][1])) || \n"
            ."              (desmarcarCheckBoxAnterior==1)) && (idGridAtual==arrayStyles[c][3]) ) \n"
            ."          {\n"
            ."              objLinhaAnterior = document.getElementById(arrayStyles[c][1]);\n"
            ."              celulasAnteriores = objLinhaAnterior.getElementsByTagName('td');\n"
            ."              numeroCelulas  = celulasAnteriores.length;\n"
            ."              for (i = 0; i < numeroCelulas; i++)\n"
            ."              {\n"
            ."                  celulasAnteriores[i].className = arrayStyles[c][0][i];\n"
            ."              }\n"
            ."              if(desmarcarCheckBoxAnterior==1)\n"
            ."              {\n"
            ."                  i=numeroCelulas;\n"
            ."                  c=arrayStyles.length;\n"
            ."              }\n"
            ."          }\n"
            ."      }\n"
            ."  }\n\n"

            //Guarda os dados da linha em um array
            ." function guardaDadosAnterior( linha , idCheckBox , desmarcarCheckBoxAnterior )\n"
            ." {\n"
            ."      var objLinhaAtual = document.getElementById( linha.id );\n"
            ."      var celulasAtuais = objLinhaAtual.getElementsByTagName( 'td' );\n"
            ."      var numeroCelulas = celulasAtuais.length;\n"

            ."      var estilos = new Array();\n"
            ."      for (c = 0; c < numeroCelulas; c++) {\n"
            ."          estilos.push( celulasAtuais[c].className );\n"
            ."      }\n"
            ."      var tdLinhaAnterior = new Array();\n"
            ."      tdLinhaAnterior[0] = estilos;\n"
            ."      tdLinhaAnterior[1] = linha.id;\n"
            ."      tdLinhaAnterior[2] = idCheckBox;\n"
            ."      tdLinhaAnterior[3] = idGridAtual;\n"
            ."      arrayStyles.push( tdLinhaAnterior );\n"
            ."      if(desmarcarCheckBoxAnterior==1){\n"
            ."          for(c=0;c<arrayStyles.length;c++){\n"
            ."              if((c<arrayStyles.length-1) && (idGridAtual==arrayStyles[c][3]))\n"
            ."              {\n"
            ."                  arrayStyles.splice(c,1);"
            ."              }\n"
            ."          }\n"
            ."      }\n"
            ." }\n\n";
    }
    function getCode()
    {
        return $this->js;
    }
}

class validacoesJavaScript
{
    function getValidacaoData(){
        $js = " function isDate ( word ) {\n"
            ."      var dtArray = new Array();\n"
            ."      var year, month, day;\n "
            ."      dtArray = word.split('/') ; //it separate the word for desired caracter \n "
            ."      day = dtArray[0] ;\n"
            ."      month = dtArray[1] ;\n"
            ."      year = dtArray[2] ;\n"
            ."      // month argument must be in the range 1 - 12 \n"
            ."      month = month - 1; // javascript month range : 0- 11 \n"
            ."      var tempDate = new Date(year,month,day); \n"
            ."      var yearJ,monthJ,dayJ; \n"
            ."      yearJ = new String(); \n"
            ."      monthJ = new String(); \n"
            ."      dayJ = new String(); \n"
            ."      yearJ.value = tempDate.getYear() ; \n"
            ."      monthJ.value = tempDate.getMonth() ; \n"
            ."      dayJ.value = tempDate.getDate() ; \n"
            ."      if ( (yearJ.value == year) && (month == monthJ.value) && "
                    ."(day == dayJ.value) ) \n"
            ."      {\n"
            ."          return true;\n"
            ."      }else{ \n"
            ."          return false;\n"
            ."      }\n"
            ."  }\n";
        return $js;
    }
}

class scriptsPesquisa
{
    var $campos;

    function scriptsPesquisa($camposAdicPesquisa = "")
	{
        $this->campos = array();
        if ( is_array($camposAdicPesquisa) ) {
            $this->campos = $camposAdicPesquisa;
        }
    }

    function getScriptDefinicaoFuncoes( $identifPesquisa, $programa, $link, $parametrosAdic = "", $larguraNovaJanela = 600, $alturaNovaJanela = 300 )
	{
        if ( $parametrosAdic != "" ) $parametrosAdic .= "&";

        # 5- este código será executado quando o cpf perder o foco (on_blur)
        $js = "\n<script type=\"text/javascript\">\n".
            "   function pesquisa".$identifPesquisa."(objId, objNome";

        foreach($this->campos as $key=>$value) {
            $js .= ", obj".ucwords($value);
            $parametrosAdic .= "campos[]=".$value."&";
        }
        $js .= ") {\n".
            "       var id = objId;\n".
            "       var nome = objNome;\n";
        foreach($this->campos as $key=>$value) {
            $js .= "        var ".$value." = obj".ucwords($value).";\n";
        }
        /*Se a chave de pesquisa vier em branco não faz nada caso contrário ele faz a pesquisa chamando pelo programa
        passado por parâmetro $find*.php
        */
        $js .= "        if ( id.value == '' ) {\n".
            "           nome.value = '';\n";
        foreach($this->campos as $key=>$value) {
            $js .= "            ".$value.".value = '';\n";
        }
        $js .= "        } else {\n".
                "           window.open('".$link.$programa."?".$parametrosAdic."acao=pesqCod&chv='+escape(id.value), 'pesq', '');\n".
                "           window.id = id;\n".
                "           window.nome = nome;\n";
        foreach($this->campos as $key=>$value) {
            $js .= "            window.".$value." = ".$value.";\n";
        }
        $js .= "        }\n".
            "   }\n";
            "\n";

        $js .= "\n function abreTelaPesq".$identifPesquisa."(objId, objNome";
        foreach($this->campos as $key=>$value) {
            $js .= ", obj".ucwords($value);
        }
        $js .= ") {\n";
        $js .= "    var id = objId;\n".
            "       var nome = objNome;\n";
        foreach($this->campos as $key=>$value) {
            $js .= "        var ".$value." = obj".ucwords($value).";\n";
        }
        $js .= "        var larguraWindow = ".$larguraNovaJanela.";\n".
            "       var alturaWindow = ".$alturaNovaJanela.";\n".
            "       \n".
            "       x = window.open('".$link.$programa."?".$parametrosAdic."acao=pesqNome', '_blank', 'dependent=yes,scrollbars=yes,statusbar=yes,width='+larguraWindow+',height='+alturaWindow);\n".
            "       x.moveTo((screen.width-larguraWindow)/2, (screen.height-alturaWindow)/2);\n".
            "       \n".
            "       window.id = id;\n".
            "       window.nome = nome;\n";
        foreach($this->campos as $key=>$value) {
            $js .= "        window.".$value." = ".$value.";\n ";
        }
        $js .= "    }\n".
            "</script>\n";

        return $js;
    }

    function getIFrame()
	{
        GLOBAL $SISCONF;
		$pagina = "";
        //$pagina = $SISCONF['SIS']['INTRANET']['HOST']."arrow.gif";

        $iFrame = "<br><br>";

		if ($SISCONF['SESSAO']['USUARIO']['IP'] == '10.10.1.999') {
			$iFrame .= "<IFRAME NAME=pesq SRC='".$pagina."' STYLE='{height:800; width:600}' FRAMEBORDER='1  ' MARGINWIDTH='0' MARGINHEIGHT='0' SCROLLING='auto'></IFRAME>";
		}else{
			$iFrame .= "<IFRAME NAME=pesq SRC='".$pagina."' STYLE='{height:0%; width:0%}' FRAMEBORDER='0' MARGINWIDTH='0' MARGINHEIGHT='0' SCROLLING='auto'></IFRAME>";
		}

        return $iFrame;
    }

    # Vai retornar o codigo Javascript para quando o campo perder o foco (tab),executar a função pesquisaPesqPessoa
    function getScriptPesquisaPorCodigo( $identifPesquisa, $nomeCampoDescricao, $incluirAspaNoFim=true)
	{
        $asp = "";
        if ( $incluirAspaNoFim == true ) $asp = "\"";

        $js = "onBlur=\"pesquisa".$identifPesquisa."(this, this.form.".$nomeCampoDescricao;
        //Criando os parâmetros adicionais que estão na propriedade $this->campos, definido na intCheque.class
        foreach($this->campos as $chv=>$vlr) {
            $js .= ", this.form.".$vlr;
        }
        $js .= ")".$asp;

        return $js;
    }

    function getScriptPesquisaPorNome( $identifPesquisa, $textoLink, $nomeForm, $nomeCampoId, $nomeCampoDescricao,$jsAdicional="")
	{

		$estilo = "style=\"cursor:pointer;\"";
        $js = "onClick=\"abreTelaPesq".$identifPesquisa."(".$nomeForm.".".$nomeCampoId.", ".$nomeForm.".".$nomeCampoDescricao;
        foreach($this->campos as $key=>$value) {
            $js .= ", ".$nomeForm.".".$value;
        }
        $js .= ")";

        $link = "<u><a ".$estilo." ".$js.$jsAdicional."\">".$textoLink."</a></u>";

        return $link;
    }

    function getScriptRetornaNomePeloCodigo( $dados, $msg)
	{
        if ( is_array($dados) ) {
            $nome = str_replace("'", "\'", $dados[0]);
        } else {
            $nome = str_replace("'", "\'", $dados);
        }

        $js = "<script type='text/javascript'>\n".
            "   parent.nome.value = '".$nome."';\n";
        $cont = 0;


        foreach($this->campos as $key=>$value) {
            $cont++;
            $js .= "    parent.".$value.".value = '".$dados[$cont]."';\n";
        }

        if ( $nome == "" ) {
            $js .= "alert('".$msg."');\n";
        }
		
        $js .= "</script>";

        return $js;
    }

    function getScriptFuncaoRetornaPesqPeloNome($adicionalJS="")
	{
        $js = "\n<script type=\"text/javascript\">".
            "function retornaPesq( id, nome";
        foreach($this->campos as $key=>$value) {
            $js .= ", ".$value;
        }
        $js .= ") {\n".
            "   top.opener.id.value = id;\n".
            "   top.opener.nome.value = nome;\n";
        foreach($this->campos as $key=>$value) {
            $js .= "    top.opener.".$value.".value = ".$value.";\n";
        }
        $js .= $adicionalJS;
        $js .= "    top.close();\n".
            "}\n".
            "</script>\n";
        return $js;
    }

    function getReplaceSQL($sql)
	{       
        return "replace(replace(".$sql.", '''', '\\\'''), '\"', '".htmlentities("\"")."')";
    }

    function getScriptFuncaoAutoComplete()
	{
            $js = " function autoComplete (field, select, forcematch, letra) { \n";
            $js .= "    var found = false; \n";
            $js .= "    var str; \n";
            $js .= "    var aux; \n";
            $js .= "    var property; \n";                      //receberá 'value':se o que foi digitado for numeros, ou 'text': se for texto
            $js .= "    var letraDoValue = letra; \n";          //receberá a letra que deve ser concatenada aos ids da combo. Vem por parâmetro
            $js .= "    var arrayBusca = select.options; \n";   //receberá o conteúdo da combo (ids e valores)
            $js .= "    aux = field.value;\n";                  //aux recebe o que o usuário digitou para pesquisa
            $js .= "    str = Number(field.value); \n";         //retorna um número se o field.value(o que o usuário digitou para pesquisa)
                                                                // for numérico. Caso contrário, retorna NaN (not a number).
            $js .= "    if (isNaN(str)){\n";                    //se o str não for numérico, então é texto
            $js .= "        property = 'text';\n";              //a pesquisa será feita no texto
            $js .= "    }else{\n";
            $js .= "        property = 'value';\n";             //a pesquisa será feita no value
            $js .= "    }\n";
            $js .= "    for (var i = 0; i < arrayBusca.length; i++) { \n";
            $js .= "        if (isNaN(str)) {\n "; //procurando no texto
            $js .= "            if (arrayBusca[i][property].toUpperCase().indexOf(aux.toUpperCase()) == 0) {\n ";
            $js .= "                found=true; \n";
            $js .= "                break;  \n";
            $js .= "            } \n";
            $js .= "        }else{ \n"; //procurando no value
            $js .= "            if (arrayBusca[i][property]== letraDoValue+aux) {\n ";
            $js .= "                found=true; \n";
            $js .= "                break;  \n";
            $js .= "            } \n";
            $js .= "        } \n";
            $js .= "    } \n";
            $js .= "    if (found) { \n ";
            $js .= "        select.selectedIndex = i; \n ";
            $js .= "    }else{ \n ";
            $js .= "        select.selectedIndex = ''; \n "; //será selecionada a opção 'Selecione ...'.
            $js .= "    } \n";
            $js .= "    if (field.createTextRange) {\n ";
            $js .= "        if (forcematch && !found) {\n ";
            $js .= "            aux=aux.substring(0,aux.length-1);\n  ";
            $js .= "            return;\n ";
            $js .= "        }\n ";
            $js .= "        var cursorKeys ='8;46;37;38;39;40;33;34;35;36;45;'; \n ";
            $js .= "        if (cursorKeys.indexOf(event.keyCode+';') == -1) { \n";
            $js .= "            var r1 = field.createTextRange(); \n";
            $js .= "            var oldValue = r1.text; \n";
            $js .= "            var newValue = found ? arrayBusca[i][property] : oldValue; \n";
            $js .= "            if (newValue != aux) { \n";
            $js .= "                aux = newValue; \n";
            $js .= "                var rNew = field.createTextRange(); \n";
            $js .= "                rNew.moveStart('character', oldValue.length); \n";
            $js .= "                rNew.select(); \n";
            $js .= "            }\n ";
            $js .= "        }\n ";
            $js .= "    }\n ";
            $js .= " }\n ";

        return $js;
    }//function

}//class ScriptsPesquisa

/** Esta classe gera funções javascript para carregar conteúdo HTML para o objeto que se necessita  **/
class carregaHTML
{
    var $js;
    var $objetoCarregado;

    function carregaHTML()
    {
        $this->js = ""
        ."\n    var Ajax;"
        ."\n    var nomeDivRecebeHTML;"
        ."\n    function loadXMLDoc(url, divRecebeHTML){"
        ."\n        Ajax = false;"
        ."\n        nomeDivRecebeHTML = divRecebeHTML;"
        ."\n        if (url.indexOf( \"?\",0 ) == -1){"
        ."\n            url = url + \"?\";"
        ."\n        }else{"
        ."\n            url = url + \"&\";"
        ."\n        }"
        ."\n        url = url + \"iniHTML=0\";"
        ."\n        /**"
        ."\n        *"
        ."\n        *   Os objetos XMLHttpRequest e ActiveXObject se equivalem, eles"
        ."\n        *"
        ."\n        *   O objeto XMLHttpRequest permite que as requisições HTTP sejam feitas via browser, "
        ."\n        *   permite a comunicação assííncrona com o servidor, através de script (JavaScript), "
        ."\n        *   sem que seja iniciada uma nova janela, não tendo a necessidade de atualização da página, "
        ."\n        *   sendo possível criar páginas mais interativas."
        ."\n        *"
        ."\n        **/"
        ."\n        //alert(url);"
        ."\n        if (window.XMLHttpRequest) { "
        ."\n            Ajax = new XMLHttpRequest(); "
        ."\n            Ajax.onreadystatechange = processReqChange; "
        ."\n            Ajax.open(\"GET\", url, true);"
        ."\n            Ajax.send(null);"
        ."\n        } else if (window.ActiveXObject) {"
        ."\n            try {"
        ."\n                Ajax = new ActiveXObject(\"Msxml2.XMLHTTP.4.0\");"
        ."\n            } catch(e) {"
        ."\n                try {"
        ."\n                    Ajax = new ActiveXObject(\"Msxml2.XMLHTTP.3.0\");"
        ."\n                } catch(e) {"
        ."\n                    try {"
        ."\n                        Ajax = new ActiveXObject(\"Msxml2.XMLHTTP\");"
        ."\n                    } catch(e) {"
        ."\n                        try {"
        ."\n                            Ajax = new ActiveXObject(\"Microsoft.XMLHTTP\");"
        ."\n                        } catch(e) {"
        ."\n                            Ajax = false; "
        ."\n                        }"
        ."\n                    }"
        ."\n                }"
        ."\n            }"
        ."\n            if (Ajax) {"
        ."\n                Ajax.onreadystatechange = processReqChange;"
        ."\n                Ajax.open(\"GET\", url, true);"
        ."\n                Ajax.send();"
        ."\n            }"
        ."\n        }"
        ."\n    }"
        ."\n\n"
        ."\n    function processReqChange(){"
        ."\n        if (Ajax.readyState == 4){"
        ."\n            if (Ajax.status == 200){"
        ."\n                document.getElementById(nomeDivRecebeHTML).innerHTML = Ajax.responseText;"
        ."\n            } else {"
        ."\n                alert(\"Houve um problema ao obter os dados:\" + Ajax.statusText);"
        ."\n            }"
        ."\n        }"
        ."\n    }"
        ."\n";

        // Carrega qualquer tipo de objeto em uma div, independente do numero de divs, além de mostrar aviso ao carregar(caso o programador deseje) //

        $this->jsCarregador = ""
        ."\n <script language='JavaScript'>
        \n function ajaxGet(url,elemento_retorno,exibe_carregando,sincrono){
            var ajax1 = pegaAjax();
            if(ajax1){
                url = antiCacheRand(url);
                ajax1.onreadystatechange = ajaxOnReady;
                if (sincrono) {
                    ajax1.open('GET', url , false);
                } else {
                    ajax1.open('GET', url , true);
                }
                //ajax1.setRequestHeader('Content-Type', 'text/html; charset=iso-8859-1');
				//'application/x-www-form-urlencoded');
                ajax1.setRequestHeader('Cache-Control', 'no-cache');
                ajax1.setRequestHeader('Pragma', 'no-cache');
                //if(exibe_carregando){ put('Carregando ...')    }
                ajax1.send(null)
                return true;
            }else{
                return false;
            }
            function ajaxOnReady(){
                if (ajax1.readyState==4){
                    escondeDivCarregando();
                    if(ajax1.status == 200){
                        var texto=ajax1.responseText;
                        if(texto.indexOf(' ')<0) texto=texto.replace(/\+/g,' ');
                        // texto=unescape(texto); //descomente esta linha se tiver usado o urlencode no php ou asp
                        put(texto);
                        extraiScript(texto);
                    }else{
                        if(exibe_carregando){put('Falha no carregamento. ' + httpStatus(ajax1.status));}
                    }
                    ajax1 = null
                }else if(exibe_carregando){//para mudar o status de cada carregando
                    criaDivCarregando();
                }
            }
            function put(valor){ //coloca o valor na variavel/elemento de retorno
                if((typeof(elemento_retorno)).toLowerCase()=='string'){ //se for o nome da string
                    if(valor != 'Falha no carregamento'){
                        eval(elemento_retorno + '= unescape(\"' + escape(valor) + '\")')
                    }
                }else if(elemento_retorno.tagName.toLowerCase()=='input'){
                    valor = escape(valor).replace(/\%0D\%0A/g,'')
                    elemento_retorno.value = unescape(valor);
                }else if(elemento_retorno.tagName.toLowerCase()=='select'){
                    select_innerHTML(elemento_retorno, valor)
                }else if(elemento_retorno.tagName){
                    elemento_retorno.innerHTML = valor;
                    //alert(elemento_retorno.innerHTML)
                }
            }
            function pegaAjax(){ //instancia um novo xmlhttprequest
                //baseado na getXMLHttpObj que possui muitas cópias na net e eu nao sei quem é o autor original
                if(typeof(XMLHttpRequest)!='undefined'){return new XMLHttpRequest();}
                var axO=['Microsoft.XMLHTTP','Msxml2.XMLHTTP','Msxml2.XMLHTTP.6.0','Msxml2.XMLHTTP.4.0','Msxml2.XMLHTTP.3.0'];
                for(var i=0;i<axO.length;i++){ try{ return new ActiveXObject(axO[i]);}catch(e){} }
                return null;
            }
            function httpStatus(stat){ //retorna o texto do erro http
                switch(stat){
                    case 0: return 'Erro desconhecido de javascript';
                    case 400: return '400: Solicita&ccedil;&atilde;o incompreensível'; break;
                    case 403: case 404: return '404: N&atilde;o foi encontrada a URL solicitada'; break;
                    case 405: return '405: O servidor n&atilde;o suporta o m&eacute;todo solicitado'; break;
                    case 500: return '500: Erro desconhecido de natureza do servidor'; break;
                    case 503: return '503: Capacidade m&aacute;xima do servidor alcançada'; break;
                    default: return 'Erro ' + stat + '. Mais informa&ccedil;&otilde;es em http://www.w3.org/Protocols/rfc2616/rfc2616-sec10.html'; break;
                }
            }
            function antiCacheRand(aurl){
                var dt = new Date();
                if(aurl.indexOf('?')>=0){// já tem parametros
                    return aurl + '&' + encodeURI(Math.random() + '_' + dt.getTime()) + '&' + 'iniHTML=0';
                }else{ return aurl + '?' + encodeURI(Math.random() + '_' + dt.getTime()) + '&' + 'iniHTML=0';}
            }
        }"

        ."\n function select_innerHTML(objeto,innerHTML){
            objeto.innerHTML = ''
            var selTemp = document.createElement('select')
            var opt;
            selTemp.id = 'select'
            document.body.appendChild(selTemp)
            selTemp = document.getElementById('select1')
            selTemp.style.display='none'
            if(innerHTML.toLowerCase().indexOf('<option')<0){//se não é option eu converto
                innerHTML = '<option>' + innerHTML + '</option>'
            }
            innerHTML = innerHTML.replace(/<option/g,'<span').replace(/<\/option/g,'</span')
            selTemp.innerHTML = innerHTML
            for(var i=0;i<selTemp.childNodes.length;i++){
                if(selTemp.childNodes[i].tagName){
                    opt = document.createElement('OPTION')
                    for(var j=0;j<selTemp.childNodes[i].attributes.length;j++){
                        opt.setAttributeNode(selTemp.childNodes[i].attributes[j].cloneNode(true))
                    }
                    opt.value = selTemp.childNodes[i].getAttribute('value')
                    opt.text = selTemp.childNodes[i].innerHTML
                    if(document.all){ //IEca
                        objeto.add(opt)
                    }else{
                        objeto.appendChild(opt)
                    }
                }
            }
            document.body.removeChild(selTemp)
            selTemp = null
        }"

        ."\n function extraiScript(texto){
            var ini = 0;
            // loop enquanto achar um script
            while (ini!=-1){
                // procura uma tag de script
                ini = texto.indexOf('<script', ini);
                // se encontrar
                if (ini >=0){
                    // define o inicio para depois do fechamento dessa tag
                    ini = texto.indexOf('>', ini) + 1;
                    // procura o final do script
                    var fim = texto.indexOf('</script', ini);
                    // extrai apenas o script
                    codigo = texto.substring(ini,fim);
                    // executa o script
                    //eval(codigo);
                    novo = document.createElement('script')
                    novo.text = codigo;
                    document.body.appendChild(novo);
                }
            }
        }"


        //APARECE UMA DIV ENQUANTO CARREGA O FORMULARIO POR AJAX

        // Exibe mensagem de carregando - usar junto com o AJAXGET
        ."\n //Variáveis globais
                var _loadTimer    = setInterval(__loadAnima,18);
                var _loadPos    = 0;
                var _loadDir    = 2;
                var _loadLen    = 0;

                //Anima a barra de progresso
                function __loadAnima(){
                    var elem = document.getElementById('barra_progresso');
                    if(elem != null){
                        if (_loadPos == 0) _loadLen += _loadDir;
                        if (_loadLen > 30 || _loadPos > 79) _loadPos += _loadDir;
                        if (_loadPos>79) _loadLen -= _loadDir;
                        if (_loadPos>79 && _loadLen==0) _loadPos=0;
                        elem.style.left  = _loadPos;
                        elem.style.width = _loadLen;
                    }
                }

                //Esconde o carregador
                function escondeDivCarregando(){
                    //this.clearInterval(_loadTimer);
                    var objLoader = document.getElementById('carregador_pai');
                    objLoader.style.display = 'none';
                    objLoader.style.visibility = 'hidden';
                }

                function __loadAparece(){
                    //_loadTimer;
                    //__loadAnima();
                    var objLoader = document.getElementById('carregador_pai');
                    objLoader.style.display = '';
                    objLoader.style.visibility = '';
                }"

        ."\n function criaDivCarregando(){
            var numero = document.getElementById('numero');
            //alert(numero.value);
            if (numero.value > 0){
                __loadAparece();
            }else{
                var carregador_pai = document.getElementById('carregador_pai');

                var carregador = document.createElement('DIV');
                var aguarde = document.createElement('DIV');
                var carregador_fundo = document.createElement('DIV');
                var barra_progresso = document.createElement('DIV');
                var texto = document.createElement('INPUT');

                carregador.setAttribute('id', 'carregador');
                aguarde.setAttribute('align', 'center');
                carregador_fundo.setAttribute('id', 'carregador_fundo');
                barra_progresso.setAttribute('id', 'barra_progresso');
                texto.setAttribute('id', 'text');
                texto.setAttribute('type', 'text');
                texto.setAttribute('value', 'Aguarde Carregando...');

                // carregador.id = 'carregador';
                // aguarde.align = 'center';
                // carregador_fundo.id = 'carregador_fundo';
                // barra_progresso.id = 'barra_progresso';
                // texto.id = 'text';
                // texto.type = 'text';
                // texto.value = 'Aguarde Carregando...';


                //alert(texto.value);

                carregador.appendChild(aguarde);
                aguarde.appendChild(texto);
                carregador.appendChild(carregador_fundo);
                carregador_fundo.appendChild(barra_progresso);


                carregador_pai.appendChild(carregador);

                numero.value = parseInt(numero.value) + 1;
            }
        }";
        $this->jsCarregador .= "\n </script>";
        $this->jsCarregador .= "\n\n <div id='carregador_pai'><input type='hidden' value=0 id='numero' ></div>\n";

    }
}

?>