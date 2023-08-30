<!DOCTYPE HTML>
<html lang="pt-br">  
    <head>  
        <meta charset="utf-8">
        <title>Vacina - Ubiratã</title>
		<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
		<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.3/umd/popper.min.js"></script>
		<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/js/bootstrap.min.js"></script>
    </head>
    <body>
		<div class="container">
			<?php 
				include('form_paciente.php');
			?>
			<br />
			<span id="conteudo"></span><br><br><br>
		</div>
		
		<div id="visulUsuarioModal" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<h5 class="modal-title" id="visulUsuarioModalLabel">Detalhes do Paciente</h5>
						<button type="button" class="close" data-dismiss="modal" aria-label="Close">
						  <span aria-hidden="true">&times;</span>
						</button>
					</div>
					<div class="modal-body">
						<span id="visul_usuario"></span>
					</div>
					<div class="modal-footer">
						<button type="button" class="btn btn-outline-info" data-dismiss="modal">Fechar</button>
					</div>
				</div>
			</div>
		</div>
		<script>
			
			var qnt_result_pg = 50; //quantidade de registro por página
			var pagina = 1; //página inicial
			
			$(document).ready(function () {
				listar_paciente(pagina, qnt_result_pg); //Chamar a função para listar os registros
			});
			
			function listar_paciente(pagina, qnt_result_pg, nome, datanascimento){
				var dados = {
					pagina: pagina,
					qnt_result_pg: qnt_result_pg,
					nome : nome,
					datanascimento : datanascimento
				}
				$.post('listar_paciente.php', dados , function(retorna){
					//Subtitui o valor no seletor id="conteudo"
					$("#conteudo").html(retorna);
				});
			}
			
			$(document).ready(function(){
				$(document).on('click','.view_data', function(){
					var user_id = $(this).attr("id");
					//alert(user_id);
					//Verificar se há valor na variável "user_id".
					if(user_id !== ''){
						var dados = {
							user_id: user_id
						};
						$.post('visualizar.php', dados, function(retorna){
							//Carregar o conteúdo para o usuário
							$("#visul_usuario").html(retorna);
							$('#visulUsuarioModal').modal('show'); 
						});
					}
				});
			});

			$('form').on('submit', function (e){
				e.preventDefault();
				$('#conteudo').html('carregando...');
				listar_paciente(1, 50, $('input[name=nome]').val(), $('input[name=datanascimento]').val());
			});
		</script>
    </body>
</html>
