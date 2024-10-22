<?php 
include_once "../../help.php";
$con = $ClassConexionBD->conectarBD();
$idticket = $_GET['idticket'];

$sql = "SELECT
ds_soporte.id_ticket, 
ds_soporte.id_personal,
ds_soporte.descripcion,
ds_soporte.prioridad,
ds_soporte.fecha_creacion,
ds_soporte.fecha_inicio,        
ds_soporte.fecha_termino,
ds_soporte.tiempo_solucion,
ds_soporte.fecha_termino_real,
ds_soporte.porcentaje,
ds_soporte.id_personal_soporte,
ds_soporte.estado,
ds_soporte.categoria,
tb_usuarios.nombre,
tb_estaciones.nombre AS nomestacion,
tb_puestos.tipo_puesto
FROM ds_soporte 
INNER JOIN tb_usuarios 
ON ds_soporte.id_personal = tb_usuarios.id
INNER JOIN tb_estaciones
ON tb_usuarios.id_gas = tb_estaciones.id
INNER JOIN tb_puestos
ON tb_usuarios.id_puesto = tb_puestos.id
WHERE ds_soporte.id_ticket = '".$idticket."'  ";
$result = mysqli_query($con, $sql);
$numero = mysqli_num_rows($result);
while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    $id_ticket = $row['id_ticket'];
    $descripcion = $row['descripcion'];
    $prioridad = $row['prioridad'];
    $porcentaje = $row['porcentaje'];
    $fechainicio = $row['fecha_inicio'];
    $fechatermino = $row['fecha_termino'];
    $tiemposolucion = $row['tiempo_solucion'];
    $estado = $row['estado'];
    $categoria = $row['categoria'];

	$explode = explode(' ',$row['fecha_creacion']);
        if($explode[0] == '0000-00-00'){
            $fechaCreacion = 'S/I';
        }else{
            $fechaCreacion = FormatoFecha($explode[0]).', '.date("g:i a",strtotime($explode[1]));
        }

		if($prioridad == 'Baja'){
            $colorPrioridad = 'text-primary';
        }else if($prioridad == 'Media'){
            $colorPrioridad = 'text-warning';
        }else if($prioridad == 'Alta'){
            $colorPrioridad = 'text-danger';
        }

		$explode1 = explode(' ',$row['fecha_inicio']);
        if($explode1[0] == '0000-00-00'){
            $fechaInicio = 'S/I';
        }else{
            $fechaInicio = FormatoFecha($explode1[0]).', '.date("g:i a",strtotime($explode1[1]));
        }

        $explode2 = explode(' ',$row['fecha_termino']);
        if($explode2[0] == '0000-00-00'){
            $fechaTermino = 'S/I';
        }else{
            $fechaTermino = FormatoFecha($explode2[0]).', '.date("g:i a",strtotime($explode2[1]));
        }

        $explode3 = explode(' ',$row['fecha_termino_real']);
        if($explode3[0] == '0000-00-00'){
            $FechaTerminoReal = 'S/I';
        }else{
            $FechaTerminoReal = FormatoFecha($explode3[0]).', '.date("g:i a",strtotime($explode3[1]));
        }
 
		if($row['estado'] == 0){

            $estado = 'Creando';

        }else if($row['estado'] == 1){

            $estado = 'Pendiente';

        }else if($row['estado'] == 2){

            $estado = 'En proceso';

        }else if($row['estado'] == 3){

            $estado = 'Finalizado';

        }else if($row['estado'] == 4){

            $estado = 'Cancelado';
        }
}

$sqlActividad = "SELECT * FROM ds_soporte_actividades WHERE id_ticket = '".$idticket."' ";
$resultActividad = mysqli_query($con, $sqlActividad);
$numeroActividad = mysqli_num_rows($resultActividad);

$sqlEvidencia = "SELECT * FROM ds_soporte_evidencia WHERE id_ticket = '".$idticket."' ";
$resultEvidencia = mysqli_query($con, $sqlEvidencia);
$numeroEvidencia = mysqli_num_rows($resultEvidencia);

?>
<div class="modal-header">
<h5 class="modal-title">Detalle</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


<div class="modal-body"> 

<h6 class="mt-2 text-secondary"># Ticket</h6>
<div>0<?=$idticket;?></div>

<h6 class="mt-2 text-secondary">Fecha creación</h6>
<div><?=$fechaCreacion;?></div>

<h6 class="mt-2 text-secondary">Descripción</h6>
<div><?=$descripcion;?></div>

<div class="row">
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">

    <h6 class="mt-2 text-secondary">Prioridad</h6>
    <div class="<?=$colorPrioridad;?>"><b><?=$prioridad;?></b></div>

    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">

    <h6 class="mt-2 text-secondary">Fecha inicio</h6>
    <div><?=$fechaInicio;?></div>

    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">

    <h6 class="mt-2 text-secondary">Fecha termino</h6>
    <div><?=$fechaTermino;?></div>

    </div>
</div>

<div class="row">
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">

    <h6 class="mt-2 text-secondary">Porcentaje</h6>
    <div><?=$porcentaje;?> %</div>

    </div>
    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">

    <h6 class="mt-2 text-secondary">Estado</h6>
    <div><?=$estado;?></div>

    </div>

    <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12">

    <h6 class="mt-2 text-secondary">Fecha termino real</h6>
    <div><?=$FechaTerminoReal;?></div>

    </div>
</div>

<hr>
<?php if($categoria == "Actividad"):?>
<h6 class="mt-2 text-secondary">Actividad:</h6>


<div class="table-responsive">
<table class="custom-table" style="font-size: 12px;" width="100%" >

<thead class="navbar-bg">
	<tr>
    <th class="align-middle">#</th>
	<th class="align-middle">Descripción de la actividad</th>
    <th class="align-middle">Fecha inicio</th>
    <th class="align-middle">Fecha termino</th>
    <th class="align-middle">Estado</th>
	<th class="align-middle text-center" width="24px"><img src="<?=RUTA_IMG_ICONOS;?>descargar.png"></th>
	</tr>
</thead>

<tbody class="bg-light">
	<?php

	if ($numeroActividad > 0) {
        $numActividad = 1;

		while($rowActividad = mysqli_fetch_array($resultActividad, MYSQLI_ASSOC)){
		$descripcionActividad = $rowActividad['descripcion'];

        if($rowActividad['fecha_inicio'] == '0000-00-00'){
            $AtividadFechaInicio = '';
        }else{
            $AtividadFechaInicio = FormatoFecha($rowActividad['fecha_inicio']);
        }

        if($rowActividad['fecha_termino'] == '0000-00-00'){
            $AtividadFechaTermino = '';
        }else{
            $AtividadFechaTermino = FormatoFecha($rowActividad['fecha_termino']);
        }

        if($rowActividad['estado'] == 0){
            $EstadoDetalle = 'Pendiente';
        }else if($rowActividad['estado'] == 1){
            $EstadoDetalle = 'En proceso';
        }else if($rowActividad['estado'] == 2){
            $EstadoDetalle = 'Finalizada';
        }

        if($rowActividad['archivo'] == ""){
            $Archivo = '<a><img src="'.RUTA_IMG_ICONOS.'eliminar.png" ></a>';
        }else{
            $Archivo = '<a href="'.RUTA_ARCHIVOS.$rowActividad['archivo'].'" download><img src="'.RUTA_IMG_ICONOS.'descargar.png" ></a>';
        }

		echo '<tr>';
        echo '<th class="align-middle">'.$numActividad.'</th>';
		echo '<td class="align-middle">'.$descripcionActividad.'</td>';
        echo '<td class="align-middle">'.$AtividadFechaInicio.'</td>';
        echo '<td class="align-middle">'.$AtividadFechaTermino.'</td>';
        echo '<td class="align-middle">'.$EstadoDetalle.'</td>';
		echo '<td class="align-middle">'.$Archivo.'</td>';
		echo '</tr>';

        $numActividad++;
		}

	}else{
        echo "<tr style='background-color: #f8f9fa'><th colspan='9' class='text-center text-secondary'><small>No se encontró información para mostrar </small></th></tr>";
	}

	?>
	</tbody> 
	</table>
</div>
<?php else:?>
<h6 class="text-secondary mt-2">Evidencia:</h6>

<div class="table-responsive">
<table class="custom-table" style="font-size: 12px;" width="100%" >
<thead class="navbar-bg">
    <tr>
    <th class="align-middle">#</th>
	<th class="align-middle">Descripción de la evidencia</th>
	<th class="align-middle text-center" width="24px"><img src="<?=RUTA_IMG_ICONOS;?>descargar.png"></th>
	</tr>
</thead>

    <tbody class="bg-light">
	<?php

	if ($numeroEvidencia > 0) {

        $numEvidencia = 1;

		while($rowEvidencia = mysqli_fetch_array($resultEvidencia, MYSQLI_ASSOC)){
		$descripcionEvidencia = $rowEvidencia['descripcion'];

		echo '<tr>';
        echo '<td class="align-middle">'.$numEvidencia.'</td>';
		echo '<td class="align-middle">'.$descripcionEvidencia.'</td>';
		echo '<td class="align-middle"><a href="'.RUTA_ARCHIVOS.$rowEvidencia['evidencia'].'" download><img src="'.RUTA_IMG_ICONOS.'descargar.png" ></a></td>';
		echo '</tr>';

        $numEvidencia++;
		}

	}else{
        echo "<tr style='background-color: #f8f9fa'><th colspan='9' class='text-center text-secondary'><small>No se encontró información para mostrar </small></th></tr>";
	}

	?>
	</tbody> 
	</table>
    </div>
<?php endif;?>
</div>
