<?php  
include_once "../../app/help.php";
$con = $ClassConexionBD->conectarBD();
$idRegistro = $_GET['idRegistro'];

$sql = "SELECT * FROM ds_soporte_evidencia WHERE id_ticket = '".$idRegistro."' ";
$result = mysqli_query($con, $sql);
$numero = mysqli_num_rows($result);
?>

<table class="table table-sm table-bordered mt-1 mb-1 pb-1">
	<thead class="table-light">
		<tr>
            <th class="align-middle">#</th>
			<th class="align-middle">Descripción de la evidencia</th>
			<th class="align-middle text-center" width="24px"><img src="<?=RUTA_IMG_ICONOS;?>descargar.png"></th>
			<th class="align-middle text-center" width="24px"><img src="<?=RUTA_IMG_ICONOS;?>eliminar.png"></th>
		</tr>
	</thead>
	<tbody>
	<?php

	if ($numero > 0) {

        $num = 1;

		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$id = $row['id'];
		$descripcion = $row['descripcion'];

		echo '<tr>';
        echo '<td class="align-middle">'.$num.'</td>';
		echo '<td class="align-middle">'.$descripcion.'</td>';
		echo '<td class="align-middle"><a href="'.RUTA_ARCHIVOS.$row['evidencia'].'" download><img src="'.RUTA_IMG_ICONOS.'descargar.png" ></a></td>';
		echo '<td class="align-middle"><img src="'.RUTA_IMG_ICONOS.'eliminar.png" onclick="EliminarEvidencia('.$idRegistro.','.$id.')"></td>';
		echo '</tr>';

        $num++;
		}

	}else{
	echo "<tr><td colspan='4' class='text-center'><small>No se encontró información para mostrar</small></td></tr>";
	}

	?>
	</tbody> 
	</table>