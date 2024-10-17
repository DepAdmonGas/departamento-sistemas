<?php  
include_once "../../app/help.php";
$con = $ClassConexionBD->conectarBD();
$idRegistro = $_GET['idRegistro'];

$sql = "SELECT * FROM ds_soporte_actividades WHERE id_ticket = '".$idRegistro."' ";
$result = mysqli_query($con, $sql);
$numero = mysqli_num_rows($result);
?>

 

<div class="table-responsive">
<table class="custom-table" style="font-size: 12px;" width="100%" >
<thead class="navbar-bg">

		<tr>
            <th class="align-middle">#</th>
			<th class="align-middle text-start">Descripción de la actividad</th>
			<th class="align-middle text-center" width="24px"><img src="<?=RUTA_IMG_ICONOS;?>descargar.png"></th>
			<th class="align-middle text-center" width="24px"><img src="<?=RUTA_IMG_ICONOS;?>eliminar.png"></th>
		</tr>
	</thead>
    <tbody class="bg-light">
	<?php

	if ($numero > 0) {
        $num = 1;

		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$id = $row['id'];
		$descripcion = $row['descripcion'];

		if($row['archivo'] == ""){
			$Archivo = '<a ><img src="'.RUTA_IMG_ICONOS.'eliminar.png" ></a>';
		}else{
			$Archivo = '<a href="'.RUTA_ARCHIVOS.$row['archivo'].'" download><img src="'.RUTA_IMG_ICONOS.'descargar.png" ></a>';
		}

		echo '<tr>';
        echo '<th class="align-middle">'.$num.'</th>';
		echo '<td class="align-middle text-start">'.$descripcion.'</td>';
		echo '<td class="align-middle">'.$Archivo.'</td>';
		echo '<td class="align-middle"><img src="'.RUTA_IMG_ICONOS.'eliminar.png" onclick="EliminarActividad('.$idRegistro.','.$id.')"></td>';
		echo '</tr>';

        $num++;
		}

	}else{
        echo "<tr style='background-color: #f8f9fa'><th colspan='9' class='text-center text-secondary'><small>No se encontró información para mostrar </small></th></tr>";
	}

	?>
	</tbody> 
	</table>
</div>