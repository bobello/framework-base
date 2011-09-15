/**
 * multiUpload v0.3
 * 
 * @author Fredi Machado <fredisoft at gmail dot com>
 * @link http://fredimachado.com.br
 * @date 08/17/2009
 **/

function multiUpload(id, filesdiv, options)
{

	/**
	 * Default function to create the base html that will show files
	 **/
	this.createBaseHtml = function()
	{
		html  = '<div><div class="c30p bold">Arquivo</div><div class="c100 ac normal">Tam.</div>';
		html += '<div class="c100 ac normal">Exclui</div><div class="c30p ac normal">Progresso</div></div><div class="sep"></div>';
		html += '<div id="files_list"></div>';

		document.getElementById(filesdiv).innerHTML = html;
		
		if(document.getElementsByTagName("input")[3].value == 'Importar')
		{
		document.getElementsByTagName("input")[1].style.display = 'none'; // esconde importar arquivo da grade

		document.getElementsByTagName("input")[3].style.display = 'none'; // esconde botão Importar
		document.getElementsByTagName("input")[3].disabled = true; // bloqueia botao Importar
		}
		
	}

		
	/**
	 * Default function to add files to the default base html
	 **/
	this.onSelected = function(e)
	{
		for (var file in e.files)
		{
			var info = e.files[file];
			var divfile = document.createElement("div");
			var extensoesOk = ",.jpeg,.jpg,";
			divfile.id = "file_"+info.id;
			divfile.innerHTML = '<div class="c30p">'+info.name+'</div><div class="c100 ac">'+size(info.size)+'</div><div class="c100 ac"><a href="javascript:'+id+'.cancelUpload('+info.id+');">X</a></div><div class="c30p progress"><div id="progress_'+info.id+'">&nbsp;</div></div><div class="sep"></div>';

			document.getElementById("files_list").appendChild(divfile);
			
			var tam_div = document.getElementById("files_list").offsetHeight; // verifica se tem arquivo carregado para upload de imagem
			
			var extensao = "," + info.name.substr( info.name.length - 4 ).toLowerCase() + ",";
			
			if( extensoesOk.indexOf( extensao ) == -1 )
			{ 
			uploader.cancelUpload(info.id); // DELETA ARQUIVOS COM EXTENSÃO NÃO PERMITIDA
			}
			else {
				//sendIt(); // ENVIA ARQUIVOS PRA PASTA
				} 			
			
			if(tam_div > 0){
				document.getElementById("btn_envio_grade").style.display = 'none';
				}
				else {
					//alert('nada');
					}
					
			
		}
	}



	/**
	 * Default function to remove the file from the default base html
	 **/
	this.onCancel = function(e)
	{
		var divfile = document.getElementById("file_"+e.id);
		document.getElementById("files_list").removeChild(divfile);
	}

	/**
	 * Default function to show the upload progress
	 **/
	this.onProgress = function(e)
	{
		var progress = Math.ceil(Number(e.bytesLoaded / e.bytesTotal * 100));
		var div = document.getElementById("progress_"+e.id);
		var val = String(progress)+"%";
		div.innerHTML = val;
		div.style.width = val;
	}

	/**
	 * Default function to clear the list of files
	 **/
	this.onClearQueue = function(e)
	{
		document.getElementById("files_list").innerHTML = "";
	}

	this.prepareData = function(data)
	{
		var strData = '';
		for (var name in data)
			strData += '&' + name + '=' + data[name];
		return escape(strData.substr(1));
	}
	
	/* ADDED BY ROBERTO  */
	this.liberaBtnImportar = function(e)
	{	
	// IntImportaRespostaQuestao.php linha 273 ($form->adicionaBotao("Importar","",'strAcao');)
	var tag_input = document.getElementsByTagName("input")[3].value; // procura pelo value Importar no input
	if(tag_input == 'Importar'){
	document.getElementsByTagName("input")[3].disabled = false; // libera botao Importar
	document.getElementsByTagName("input")[3].style.display = 'block'; // mostra botao Importar
	document.getElementById("img_loading").style.display = 'none';
	document.getElementById("label_importa_arquivo_grade").style.display = 'block'; // libera div
	document.getElementsByTagName("input")[1].style.display = 'block'; // libera importar arquivo da grade
	alert('Imagens das grades de respostas enviadas com sucesso!\n\nAgora você pode importar o arquivo da grade de respostas.');
		}
	}
	
	this.showLoading = function() {
		document.getElementById("img_loading").style.display = 'block';
		}
    



	/**
	 * Default options
	 */
	this.op = {
		swf:               'upload.swf', // path to the swf file
		script:            'upload.php', // path to the upload script
		expressInstall:    null,
		scriptAccess:      'sameDomain',
		width:             237, // flash button width
		height:            27, // flash button height
		wmode:             'opaque', // flash button wmode
		method:            'POST', // method to send vars to the upload script
		data:              {}, // data object to send with each upload. ex.: { foo: 'bar' }
		maxsize:           0, // maximum file size in bytes (0 = any size)
		fileDescription:   'JPG,JPEG', // text to show in the combo box on the bottom of the selection window
		fileExtensions:    '*.jpeg;*.jpg', // Extension to allow ex.: '*.jpg;*.gif;*.png'
		createBaseHtml:    this.createBaseHtml, // Base html
		onMouseClick:      function() {}, // function to execute when the user has clicked the uploader swf
		onSelectionCancel: function() {}, // function to execute when the user presses "Cancel" in the selection window
		onSelected:        this.onSelected, // function to execute when the user makes the selection
		onStart:           function() {}, // function to execute when the uploader starts sending a file
		onError:           function() {}, // function to execute when an Error occurs
		onProgress:        this.onProgress, // function to execute on every progress change of a single file upload
		onCancel:          this.onCancel, // function to execute when a file upload is canceled
		onComplete:        function() {}, // function to execute when a file upload is complete
		onAllComplete:     this.liberaBtnImportar, // function to execute when every file from the queue was sent
		onClearQueue:      this.onClearQueue, // function to execute when the queue is cleared
		callback:          function() {} // function to execute when the swf object is embeded
	}

	this.op = mergeRecursive(this.op, options);

	var op = this.op;

	DOMReady(function() { op.createBaseHtml(); });

	var path = location.pathname;
	path = path.split('/');
	path.pop();
	path = path.join('/') + '/';

	var params = {};

	params.id      = id;
	params.path    = path;
	params.script  = op.script;
	params.method  = op.method;
	if (op.multi)  params.multi = true;
	if (op.auto)   params.auto  = true;
	params.maxsize = op.maxsize;
	params.desc    = op.fileDescription;
	params.ext     = op.fileExtensions;

	if (op.data)
		params.scriptData = this.prepareData(op.data);
	
	swfobject.embedSWF(op.swf, id, op.width, op.height, '9.0.24', op.expressInstall, params, {'quality':'high','wmode':op.wmode,'allowScriptAccess':op.scriptAccess}, null, op.callback);

	this.el = function()
	{
		return document.getElementById(id);
	}

	this.setData = function(data)
	{
		this.el().setData(this.prepareData(data));
	}

	this.startUpload = function()
	{
		this.el().startUpload();
	}

	this.cancelUpload = function(fileid)
	{
		this.el().cancelUpload(fileid);
	}

	this.clearUploadQueue = function()
	{
		this.el().clearUploadQueue();
	}
}

function mergeRecursive(obj1, obj2)
{
	for (var p in obj2)
	{
		try
		{
			if (obj2[p].constructor == Object)
				obj1[p] = mergeRecursive(obj1[p], obj2[p]);
			else
				obj1[p] = obj2[p];
		}
		catch(e)
		{
			obj1[p] = obj2[p];
		}
	}

	return obj1;
}

function DOMReady(f)
{
	if (/(?!.*?compatible|.*?webkit)^mozilla|opera/i.test(navigator.userAgent))
		document.addEventListener("DOMContentLoaded", f, false);
	else
		window.setTimeout(f,0);
}

function size(val)
{
	var kb = Number(Number(val)/1024).toFixed(1);
	return kb >= 1000 ? Number(kb/1024).toFixed(1) + " MB" : kb + " KB";
}


function habilita_envio_grade(){
	document.getElementById("label_importa_arquivo_grade").style.display = 'block';
	document.getElementsByTagName("input")[1].style.display = 'block'; // libera o input file
	document.getElementsByTagName("input")[3].style.display = 'block'; // mostra btn inportar
	document.getElementsByTagName("input")[3].disabled = false; // libera btn importar
	}
