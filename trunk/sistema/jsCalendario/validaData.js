
/**
*   validaData.js
*
*   Notas do autor:
*       Essa é uma função usada para o controle do que foi digitado no campo
*       de data, usado a partir da classe textData da package_form. Faz um controle
*       que verifica o que o usuário digitou no campo, permitindo apenas que
*       seja usado números no campo. Somente faz uma verificação quanto ao backspace
*       para que pudesse ser apagado o valor no campo.
*
*
*   @author Henrique Girardi dos Santos
*   @version 1
*
*/

/**
*   validaData
*
*   Função que controla o que foi digitado no campo de data da classe TextData da package_form
*   @param string opcao
*   @param string id
*   @param event event
*   @return void
*   @access public
*/
function validaData( opcao, id, e)
{
    var data = document.getElementById( id );

    if ( opcao == 'up' ) {

        var novoData = "";
        var i;

        caractere = data.value.charAt(data.value.length-1);
        if ( isNaN( parseInt(caractere) ) ) {
            for(i=0; i< data.value.length-1; i++) {
                caractere = data.value.charAt(i);
                novoData = novoData + caractere;
            }
            data.value = novoData;
        }

    } else if ( opcao == 'down' ) {

        if ( window.event ) {
            var tecla = window.event.keyCode;
        } else if ( e.keyCode ) {
            var tecla = e.keyCode;
        }

        if ( tecla != 8 ) {
            if ((data.value.length == 2) || (data.value.length == 5)){
                data.value = data.value + '/';
            }
        }
    }
}