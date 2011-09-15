// apenas para o IntImpRelDensidadeCargo.php
function seleciona_todo_listbox(listId) {	
	controle =  document.getElementById(listId);	
	for(var i = 0;i < controle.options.length; i++){
			controle.options[i].selected = true;
						
	}		
}
