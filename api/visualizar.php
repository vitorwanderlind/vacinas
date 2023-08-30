<?php
if(isset($_POST["user_id"])){
	include("conexao.php");
	
	$resultado = '';
	
	//$query_user = "SELECT * FROM usuarios WHERE id = '" . $_POST["user_id"] . "' LIMIT 1";
	$query_paciente = "SELECT nome from paciente where codnome = '" . $_POST["user_id"] . "'";
	$resultado_paciente = pg_query($conn, $query_paciente);
	$row_paciente = pg_fetch_assoc($resultado_paciente);
	
	$resultado .= '<dl class="row">';
	
	$resultado .= '<dt class="col-sm-3">Nome</dt>';
	$resultado .= '<dd class="col-sm-9">'.$row_paciente['nome'].'</dd>';
		
	$resultado .= '</dl>';
	
	echo $resultado;
}
?>
	<table class="table table-sm table-striped table-bordered table-hover">
		<thead>
			<tr>
				<th>Vacina</th>
				<th>Data Vacinação</th>
				<th>Dose</th>
			</tr>
		</thead>
		<tbody>
			<?php
			$query_user = "SELECT v.vacina, c.datavacina, c.dose FROM controle as c
								left join vacinas as v on c.codvacinas = v.codvacinas
								left join paciente as p on c.codnome = p.codnome
							where p.codnome = '" . $_POST["user_id"] . "' order by v.vacina, c.datavacina";
			$resultado_user = pg_query($conn, $query_user);

			while($row_user = pg_fetch_assoc($resultado_user)){
				?>
				<tr>
					<th><?php echo $row_user['vacina']; ?></th>
					<td><?php echo date("d/m/Y", strtotime($row_user['datavacina'])); ?></td>
					<td><?php echo $row_user['dose']; ?></td>
				</tr>
				<?php
			}?>
		</tbody>
	</table>
