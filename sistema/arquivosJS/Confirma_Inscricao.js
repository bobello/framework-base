				$(document).ready(function() {
					/* AO DESCARREGAR A PAGINA, BLOQUEIA O BTN DIREITO DO MOUSE */
					
					$(document).bind('contextmenu',function(e){
					return false
					})
					
					
					/* AO DESCARREGAR A PAGINA, BLOQUEIA A TECLA ENTER */
					$('input').live('keypress', function(event){
							if (event.keyCode == 13 || event.wich == 13){
								return false;
							}
					}); 
					
					/* MASCARAS */
					$('#i_datDataNascimentoConfirm').mask('99/99/9999');
					//$('#i_strRegistroGeral').mask('9999999999'); RG PODE TER LETRAS (MILITAR ETC..)
					$('#i_strCep').mask('99999999');
					
					/* SOMENTE TEXTO MAIUSCULO */
					$('#i_strNome').keyup(function() {
					var nome = $('#i_strNome').alphanumeric({ichars:'\\=+-_.,;<>!@#$%&¨*\'\"/(){}[]0123456789abcdefghijklmnopqrstuvwxyz\u00E7°ªº§|'});
					//$('#i_strNome').val(nome);
					});
					
					/* BLOQUEIA CONTROL DO TECLADO */
					$('#i_datDataNascimentoConfirm').keydown(function(event){
						if (event.keyCode == '17') {
						alert('Redigite sua data de nascimento');
						}
					})
				}); 
				
				/* CAMPO DATA NASC CONFIRM PERDE FOCO */
				$('#i_datDataNascimentoConfirm').blur(function() {
				vd_nasc = $('#i_datDataNascimento').val()
				if(vd_nasc != $(this).val()){
				$('#i_datDataNascimentoConfirm').css('border','1px solid red')
				$('#x').html('<h2 style=\"color:red\">A sua data de nascimento n\u00E3o confere com a anterior</h2>')
				}
				else {
					$('#i_datDataNascimentoConfirm').css('border','1px solid #1D3955')
					$('#x').html('')
					}
				})	

				/*
				function volta(){
				$('#confirma_inscricao').hide()
				$('#confirma_inscricao').empty()
				}
				*/
				
				function verifica_campos(){
					v_nome      = $('#i_strNome').val()
					v_nasc      = $('#i_datDataNascimento').val()
					v_rg        = $('#i_strRegistroGeral').val()
					v_uf        = $('#i_intEstadoId option:selected').html()
					v_cidade    = $('#i_intCidadeId option:selected').html()
					v_bairro    = $('#i_strBairro').val()
					v_end       = $('#i_strEndereco').val()
					v_numero    = $('#i_strNumEndereco').val()
					v_cep       = $('#i_strCep').val()
					v_compl     = $('#i_strComplemento').val()
					v_email     = $('#i_strEmail').val()
					v_fone      = $('#i_strFone').val()
					v_mala_dir  = $('input[type=radio][name=strMalaDireta]:checked').val()
					v_cota_pcd  = $('input[type=radio][name=strNecessidadeEspecial]:checked').val()
					v_cota_cid  = $('#i_strCid').val()
					v_cota_afr  = $('input[type=radio][name=strAfro]:checked').val()
					v_nasc_conf = $('#i_datDataNascimentoConfirm').val()
					
					if((v_nome == '') || (v_nome.length < 3))
					{
					alert('Campo Nome n\u00E3o pode ser vazio, m\u00EDnimo 3 caracteres!');	
					$('#i_strNome').focus();
					return false;
					}
						else if ((v_nasc == '') || (v_nasc.length < 10))
						{
						alert('Campo data nascimento n\u00E3o pode ser vazio, preencha corretamente!');
						$('#i_datDataNascimento').focus();
						return false;	
						}
						else if ((v_rg == '') || (v_rg.length < 10))
						{
						alert('Campo RG n\u00E3o pode ser vazio, preencha corretamente!');
						$('#i_strRegistroGeral').focus();
						return false;	
						}
						else if (v_uf == 'Selecione')
						{
						alert('Selecione o estado!');	
						return false;	
						}
						else if (v_cidade == 'Selecione')
						{
						alert('Selecione a cidade!');	
						return false;	
						}
						else if (v_bairro == '')
						{
						alert('Campo bairro n\u00E3o pode ser vazio!');	
						$('#i_strBairro').focus();
						return false;	
						}
						else if (v_end == '')
						{
						alert('Campo endere\u00E7o n\u00E3o pode ser vazio!');
						$('#i_strEndereco').focus();
						return false;	
						}
						else if (v_numero == '')
						{
						alert('Campo n\u00FAmero n\u00E3o pode ser vazio!');	
						$('#i_strNumEndereco').focus();
						return false;	
						}
						else if ((v_cep == '') || (v_cep.length < 8))
						{
						alert('Campo CEP n\u00E3o pode ser vazio!');	
						$('#i_strCep').focus();
						return false;	
						}
						else if ((v_nasc_conf == '') || (v_nasc_conf != v_nasc))
						{
						alert('Campo data nascimento n\u00E3o confere com a anterior!');	
						$('#i_datDataNascimentoConfirm').focus();
						return false;	
						}
					else 
					{
						/* O MALDITO IE N\u00E3O RECONHECEU O APPEND, BANANAS DA MICROSOFT */
						/*
						$('#confirma_inscricao').css({'display':'block'})
						$('#confirma_inscricao')
						.append
						(
						'<h1>SEUS DADOS EST\u00E3O CORRETOS?</h1>',
						'<p>NOME: <span>'+ v_nome +'</span></p>',
						'<p>NASCIMENTO: <span>'+ v_nasc +'</span></p>',
						'<p>RG: <span>'+ v_rg +'</span></p>',
						'<p>ESTADO: <span>'+ v_uf +'</span></p>',
						'<p>CIDADE: <span>'+ v_cidade +'</span></p>',
						'<p>BAIRRO: <span>'+ v_bairro +'</span></p>',
						'<p>ENDERE\u00E7O: <span>'+ v_end +'</span></p>',
						'<p>N\u00FAMERO: <span>'+ v_numero +'</span></p>',
						'<p>CEP: <span>'+ v_cep +'</span></p>',
						'<p>COMPL.: <span>'+ v_compl +'</span></p>',
						'<p>E-MAIL: <span>'+ v_email +'</span></p>',
						'<p>FONE: <span>'+ v_fone +'</span></p>',
						'<p>RECEBER MALA DIRETA: <span>'+ v_mala_dir +'</span></p>',
						'<p>OPTAR POR COTA PCD: <span>'+ v_cota_pcd +'</span></p>',
						'<p>COTA CID: <span>'+ v_cota_cid +'</span></p>',
						'<p>OPTAR POR COTA AFRO: <span>'+ v_cota_afr +'</span></p>',
						'<h2><input type=submit value=\"SIM - Gravar\" name=strAcao><input type=button value=\"N\u00E3O - Voltar e Corrigir\" name=strAcao onclick=volta()></h2>'
						)
						*/
						//return true;
						var agree=confirm(" CONFIRME SEUS DADOS! (OK para GRAVAR, Cancelar para VOLTAR) \n\n NOME: "+ v_nome +" \n\n NASCIMENTO: "+ v_nasc +" \n\n RG: "+ v_rg +" \n\n ESTADO: "+ v_uf +" \n\n CIDADE: "+ v_cidade +" \n\n BAIRRO: "+ v_bairro +" \n\n ENDERE\u00C7O: "+ v_end +" \n\n N\u00DAMERO: "+ v_numero +" \n\n CEP: "+ v_cep +" \n\n COMPL.: "+ v_compl +" \n\n E-MAIL: "+ v_email +" \n\n FONE: "+ v_fone +" \n\n RECEBER MALA DIRETA: "+ v_mala_dir +" \n\n OPTAR POR COTA PCD: "+ v_cota_pcd +" \n\n COTA CID: "+ v_cota_cid +" \n\n OPTAR POR COTA AFRO: "+ v_cota_afr +"");
						if (agree)
						return true ;
						else
						return false ;
					}
				}