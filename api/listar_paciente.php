<?php
	include("conexao.php");

	$pagina = filter_input(INPUT_POST, 'pagina', FILTER_SANITIZE_NUMBER_INT);
	$qnt_result_pg = filter_input(INPUT_POST, 'qnt_result_pg', FILTER_SANITIZE_NUMBER_INT);
	if (session_status() !== PHP_SESSION_ACTIVE){
		session_start();
	}
	
	if (!empty($_POST['nome']) || !empty($_POST['datanascimento'])) {
		$_SESSION['nome'] = $_POST['nome'];
		$_SESSION['datanascimento'] = $_POST['datanascimento'];
	}
	$nome = $_SESSION['nome'];
	$datanascimento = $_SESSION['datanascimento'];

	//calcular o inicio visualização
	$inicio = ($pagina * $qnt_result_pg) - $qnt_result_pg;

	//consultar no banco de dados
	if (!empty($nome) and empty($datanascimento)){
		$result_paciente = "SELECT codnome, nome, datanascimento, mae 
						FROM paciente 
						where nome iLIKE '$nome%' order by nome LIMIT 50  OFFSET $inicio";
	} else if (empty($nome) and !empty($datanascimento)){
		$result_paciente = "SELECT codnome, nome, datanascimento, mae 
						FROM paciente 
						where datanascimento = '$datanascimento%' order by nome LIMIT 50  OFFSET $inicio";	
	} else if (!empty($nome) and !empty($datanascimento)){
		$result_paciente = "SELECT codnome, nome, datanascimento, mae 
						FROM paciente 
						where nome iLIKE '$nome%' and datanascimento = '$datanascimento%' order by nome LIMIT 50  OFFSET $inicio";	
	} 
	
	$resultado_paciente = pg_query($conn, $result_paciente);


	//Verificar se encontrou resultado na tabela "paciente"
	if(($resultado_paciente) AND (pg_num_rows($resultado_paciente) != 0)){
		?>
		<table class="table table-sm table-striped table-bordered table-hover">
			<thead>
				<tr>
					<th>Código</th>
					<th>Nome</th>
					<th>Data Nascimento</th>
					<th>Mãe</th>
					<th></th>
				</tr>
			</thead>
			<tbody>
				<?php
				while($row_usuario = pg_fetch_assoc($resultado_paciente)){
					?>
					<tr>
						<th><?php echo $row_usuario['codnome']; ?></th>
						<td><?php echo $row_usuario['nome']; ?></td>
						<td><?php echo date("d/m/Y", strtotime($row_usuario['datanascimento'])); ?></td>
						<td><?php echo $row_usuario['mae']; ?></td>
						<td>
							<button type="button" class="btn btn-outline-primary view_data btn-sm" id="<?php echo $row_usuario['codnome']; ?>">Visualizar</button>
						</td>
					</tr>
					<?php
				}?>
			</tbody>
		</table>
		<?php
		//Paginação - Somar a quantidade de pacientes
		$result_pg = "SELECT COUNT(codnome) AS num_result FROM paciente where nome iLIKE '$nome%'";
		$resultado_pg = pg_query($conn, $result_pg);
		$row_pg = pg_fetch_assoc($resultado_pg);

		//Quantidade de pagina
		$quantidade_pg = ceil($row_pg['num_result'] / $qnt_result_pg);

		//Limitar os link antes depois
		$max_links = 2;

		echo '<nav aria-label="paginacao">';
		echo '<ul class="pagination">';
		echo '<li class="page-item">';
		echo "<span class='page-link'><a href='#' onclick='listar_paciente(1, $qnt_result_pg)'>Primeira</a> </span>";
		echo '</li>';
		for ($pag_ant = $pagina - $max_links; $pag_ant <= $pagina - 1; $pag_ant++) {
			if($pag_ant >= 1){
				echo "<li class='page-item'><a class='page-link' href='#' onclick='listar_paciente($pag_ant, $qnt_result_pg)'>$pag_ant </a></li>";
			}
		}
		echo '<li class="page-item active">';
		echo '<span class="page-link">';
		echo "$pagina";
		echo '</span>';
		echo '</li>';

		for ($pag_dep = $pagina + 1; $pag_dep <= $pagina + $max_links; $pag_dep++) {
			if($pag_dep <= $quantidade_pg){
				echo "<li class='page-item'><a class='page-link' href='#' onclick='listar_paciente($pag_dep, $qnt_result_pg)'>$pag_dep</a></li>";
			}
		}
		echo '<li class="page-item">';
		echo "<span class='page-link'><a href='#' onclick='listar_paciente($quantidade_pg, $qnt_result_pg)'>Última</a></span>";
		echo '</li>';
		echo '</ul>';
		echo '</nav>';

	}else{
		echo "<div class='alert alert-danger' role='alert'>Nenhum paciente encontrado!</div>";
	}
?>
