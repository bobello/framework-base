function ajaxGet(url,elemento_retorno,exibe_carregando,sincrono)
{
    var ajax1 = pegaAjax();
    if(ajax1){
        //url = antiCacheRand(url);
        ajax1.onreadystatechange = ajaxOnReady;
        if (sincrono) {
            ajax1.open('GET', url , false);
        } else {
            ajax1.open('GET', url , true);
        }
        //ajax1.setRequestHeader('Content-Type', 'text/html; charset=iso-8859-1');//'application/x-www-form-urlencoded');
        ajax1.setRequestHeader('Cache-Control', 'no-cache');
        ajax1.setRequestHeader('Pragma', 'no-cache');
        if(exibe_carregando){ put('Carregando ...')    }
        ajax1.send(null);
        return true;
    }else{
        return false;
    }

    function ajaxOnReady()
    {
        if (ajax1.readyState==4) {
            if (exibe_carregando) escondeDivCarregando();
            if(ajax1.status == 200) {
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

    function put(valor) //coloca o valor na variavel/elemento de retorno
    {
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
        }
    }

    function pegaAjax() //instancia um novo xmlhttprequest
    {
        if(typeof(XMLHttpRequest)!='undefined'){return new XMLHttpRequest();}
        var axO=['Microsoft.XMLHTTP','Msxml2.XMLHTTP','Msxml2.XMLHTTP.6.0','Msxml2.XMLHTTP.4.0','Msxml2.XMLHTTP.3.0'];
        for(var i=0;i<axO.length;i++){ try{ return new ActiveXObject(axO[i]);}catch(e){} }
        return null;
    }

    function httpStatus(stat) //retorna o texto do erro http
    {
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

    function antiCacheRand(aurl)
    {
        var dt = new Date();
        if(aurl.indexOf('?')>=0){// já tem parametros
            return aurl + '&' + encodeURI(Math.random() + '_' + dt.getTime()) + '&' + 'iniHTML=0';
        }else{ return aurl + '?' + encodeURI(Math.random() + '_' + dt.getTime()) + '&' + 'iniHTML=0';}
    }
}

function select_innerHTML(objeto,innerHTML)
{
    objeto.innerHTML = ''
    var selTemp = document.createElement('select')
    var opt;
    selTemp.id = 'select'
    document.body.appendChild(selTemp)
    selTemp = document.getElementById('select1')
    selTemp.style.display='none'
    if(innerHTML.toLowerCase().indexOf('<option')<0){ //se não é option eu converto
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
}

function extraiScript(texto)
{
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
}

/**
    CRIA UMA DIV DE CARREGANDO NA TELA
    Exibe mensagem de carregando - usar junto com o AJAXGET
*/

//Variáveis globais
var _loadTimer  = setInterval(__loadAnima,18);
var _loadPos    = 0;
var _loadDir    = 2;
var _loadLen    = 0;
var _verificador = false;

//Anima a barra de progresso
function __loadAnima()
{
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
function escondeDivCarregando()
{
    var objLoader = document.getElementById('carregador');
    objLoader.style.display = 'none';
    objLoader.style.visibility = 'hidden';
}

function __loadAparece()
{
    var objLoader = document.getElementById('carregador');
    objLoader.style.display = '';
    objLoader.style.visibility = '';
}

function criaDivCarregando()
{
    if ( _verificador == true ){
        __loadAparece();
    }else{
        _verificador = true;
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

        carregador.appendChild(aguarde);
        aguarde.appendChild(texto);
        carregador.appendChild(carregador_fundo);
        carregador_fundo.appendChild(barra_progresso);

        document.body.appendChild(carregador);
    }
}