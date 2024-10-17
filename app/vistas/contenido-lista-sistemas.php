<?php  
include_once "../../app/help.php";
$con = $ClassConexionBD->conectarBD();

date_default_timezone_set('America/Mexico_City');
$fecha_del_dia = date("Y-m-d");

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
        tb_usuarios.nombre,
        tb_estaciones.nombre AS nomestacion,
        tb_puestos.tipo_puesto
        FROM ds_soporte 
        INNER JOIN tb_usuarios 
        ON ds_soporte.id_personal = tb_usuarios.id
        INNER JOIN tb_estaciones
        ON tb_usuarios.id_gas = tb_estaciones.id
        INNER JOIN tb_puestos
        ON tb_usuarios.id_puesto = tb_puestos.id WHERE (ds_soporte.estado <> 0 AND ds_soporte.estado <> 4) 
        ORDER BY ds_soporte.estado ASC, ds_soporte.fecha_creacion DESC";
        $result = mysqli_query($con, $sql);
        $numero = mysqli_num_rows($result);
?>
<div class="table-responsive">
<table id="tabla-sistemas" class="table table-sm table-bordered mt-2" style="font-size: 14px;">
	<thead class="navbar-bg">
		<tr>
            <th class="align-middle"># Ticket</th>
            <th class="align-middle">Fecha creación</th>
			<th class="align-middle">Descripción</th>
            <th class="align-middle">Estación o Departamento</th>
            <th class="align-middle">Prioridad</th>

            <th class="align-middle">Fecha inicio</th>
            <th class="align-middle">Fecha termino</th>

            <th class="align-middle">Estado</th>  
            <th class="align-middle">Porcentaje</th>
            <th class="align-middle">Responsable</th> 
            <th class="align-middle">Fecha termino real</th>

            <th class="align-middle text-center" width="24px"><img src="<?=RUTA_IMG_ICONOS;?>detalle.png"></th>
            <th class="align-middle text-center" width="24px"><img src="<?=RUTA_IMG_ICONOS;?>comentarios.png"></th>
			<th class="align-middle text-center" width="24px"><img src="<?=RUTA_IMG_ICONOS;?>editar.png"></th>
			<th class="align-middle text-center" width="24px"><img src="<?=RUTA_IMG_ICONOS;?>eliminar.png"></th>
		</tr>
	</thead>
	<tbody class="bg-white">
	<?php

	if ($numero > 0) {

		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$id_ticket = $row['id_ticket'];
		$descripcion = $row['descripcion'];
        $prioridad = $row['prioridad'];
        $porcentaje = $row['porcentaje'];
        $nomestacion = $row['nomestacion'];
        $ToComentarios = $ClassContenido->ToComentarios($id_ticket);
        $idPersonalSoporte = $row['id_personal_soporte'];
        $PersonalSoporte = $ClassContenido->Responsable($idPersonalSoporte);
        $nombre = $row['nombre'];

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

        if($row['estado'] == 0){

            //$trColor = 'table-warning';
            $trColor = 'background-color: #fcfcda';
            $estado = 'Creando';
            $Editar = '<a onclick="EditarTicket('.$id_ticket.')"><img src="'.RUTA_IMG_ICONOS.'editar.png" ></a>';
            $Eliminar = '<img src="'.RUTA_IMG_ICONOS.'eliminar.png" onclick="EliminarTicket('.$id_ticket.')">';

        }else if($row['estado'] == 1){

            //$trColor = 'table-secondary';
            $trColor = 'background-color: #f0f0f0';
            $estado = 'Pendiente';
            $Editar = '<a onclick="EditarTicket('.$id_ticket.')"><img src="'.RUTA_IMG_ICONOS.'editar.png" ></a>';
            $Eliminar = '<img src="'.RUTA_IMG_ICONOS.'eliminar.png" onclick="EliminarTicket('.$id_ticket.')">';

        }else if($row['estado'] == 2){

            //$trColor = 'table-info';
            $trColor = 'background-color: #cfe2ff';
            $estado = 'En proceso';
            $Editar = '<a onclick="EditarTicket('.$id_ticket.')"><img src="'.RUTA_IMG_ICONOS.'editar.png" ></a>';
            $Eliminar = '<img src="'.RUTA_IMG_ICONOS.'eliminar.png" onclick="EliminarTicket('.$id_ticket.')">';

        }else if($row['estado'] == 3){

            //$trColor = 'table-success';
            $trColor = 'background-color: #b0f2c2';
            $estado = 'Finalizado';
            $Editar = '<img class="grayscale" src="'.RUTA_IMG_ICONOS.'editar.png" >';
            $Eliminar = '<img class="grayscale" src="'.RUTA_IMG_ICONOS.'eliminar.png">';

        }else if($row['estado'] == 4){

            //$trColor = 'table-danger';
            $trColor = 'background-color: #ffb6af';
            $estado = 'Cancelado';
            $Editar = '<img class="grayscale" src="'.RUTA_IMG_ICONOS.'editar.png" >';
            $Eliminar = '<img class="grayscale" src="'.RUTA_IMG_ICONOS.'eliminar.png">';

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

        if($ToComentarios > 0){
            $ToComent = '<div class="float-end"><span class="badge bg-danger rounded-circle" style="font-size: .5em;margin-top:12px;margin-left:-10px;position: absolute;">'.$ToComentarios.'</span></div>';
          }else{
           $ToComent = ''; 
          }

          if($nomestacion == 'Comodines'){
            $EstacionDepartamento = $row['tipo_puesto'];
          }else{
            $EstacionDepartamento = $nomestacion;
          }

          $explode3 = explode(' ',$row['fecha_termino_real']);
        if($explode3[0] == '0000-00-00'){
            $fechaterminoreal = 'S/I';
        }else{
            $fechaterminoreal = '<div class="text-success">'.FormatoFecha($explode3[0]).', '.date("g:i a",strtotime($explode3[1])).'</div>';
        }

        if($row['estado'] == 1){

            echo '<tr style="'.$trColor.'">';
            echo '<th class="align-middle"><b>0'.$id_ticket.'</b></th>';
            echo '<td class="align-middle"><small>'.$fechaCreacion.'</small></td>';
            echo '<td class="align-middle">'.$descripcion.'</td>';
            echo '<td class="align-middle"><b>'.$EstacionDepartamento.'</b></td>';
            echo '<td class="align-middle '.$colorPrioridad.'"><small><b>'.$prioridad.'</b></small></td>';

            echo '<td class="align-middle"><small>'.$fechaInicio.'</small></td>';
            echo '<td class="align-middle"><small>'.$fechaTermino.'</small></td>';

            echo '<td class="align-middle">'.$estado.'</td>';
            echo '<td class="align-middle">'.$porcentaje.' %</td>';
            echo '<td class="align-middle text-secondary">'.$PersonalSoporte.'</td>';
            echo '<td class="align-middle"><b>'.$fechaterminoreal.'</b></td>';

            echo '<td class="align-middle"><a onclick="ModalDetalle('.$id_ticket.')"><img src="'.RUTA_IMG_ICONOS.'detalle.png" ></a></td>';
            echo '<td class="align-middle"><a onclick="ModalComentarios('.$id_ticket.')">'.$ToComent.'<img src="'.RUTA_IMG_ICONOS.'comentarios.png" ></a></td>';
            echo '<td class="align-middle">'.$Editar.'</td>';
            echo '<td class="align-middle">'.$Eliminar.'</td>';
            echo '</tr>';

        }else if($row['estado'] == 2){

            echo '<tr style="'.$trColor.'">';
            echo '<th class="align-middle"><b>0'.$id_ticket.'</b></th>';
            echo '<td class="align-middle"><small>'.$fechaCreacion.'</small></td>';
            echo '<td class="align-middle">'.$descripcion.'</td>';
            echo '<td class="align-middle"><b>'.$EstacionDepartamento.'</b></td>';
            echo '<td class="align-middle '.$colorPrioridad.'"><small><b>'.$prioridad.'</b></small></td>';

            echo '<td class="align-middle"><small>'.$fechaInicio.'</small></td>';
            echo '<td class="align-middle"><small>'.$fechaTermino.'</small></td>';

            echo '<td class="align-middle">'.$estado.'</td>';
            echo '<td class="align-middle">'.$porcentaje.' %</td>';
            echo '<td class="align-middle text-secondary">'.$PersonalSoporte.'</td>';
            echo '<td class="align-middle"><b>'.$fechaterminoreal.'</b></td>';

            echo '<td class="align-middle"><a onclick="ModalDetalle('.$id_ticket.')"><img src="'.RUTA_IMG_ICONOS.'detalle.png" ></a></td>';
            echo '<td class="align-middle"><a onclick="ModalComentarios('.$id_ticket.')">'.$ToComent.'<img src="'.RUTA_IMG_ICONOS.'comentarios.png" ></a></td>';
            echo '<td class="align-middle">'.$Editar.'</td>';
            echo '<td class="align-middle">'.$Eliminar.'</td>';
            echo '</tr>';

        }else if($row['estado'] == 3){

            echo '<tr style="'.$trColor.'">';
            echo '<td class="align-middle"><b>0'.$id_ticket.'</b></td>';
            echo '<td class="align-middle"><small>'.$fechaCreacion.'</small></td>';
            echo '<td class="align-middle">'.$descripcion.'</td>';
            echo '<td class="align-middle"><b>'.$EstacionDepartamento.'</b></td>';
            echo '<td class="align-middle '.$colorPrioridad.'"><small><b>'.$prioridad.'</b></small></td>';

            echo '<td class="align-middle"><small>'.$fechaInicio.'</small></td>';
            echo '<td class="align-middle"><small>'.$fechaTermino.'</small></td>';

            echo '<td class="align-middle">'.$estado.'</td>';
            echo '<td class="align-middle">'.$porcentaje.' %</td>';
            echo '<td class="align-middle text-secondary">'.$PersonalSoporte.'</td>';
            echo '<td class="align-middle"><b>'.$fechaterminoreal.'</b></td>';

            echo '<td class="align-middle"><a onclick="ModalDetalle('.$id_ticket.')"><img src="'.RUTA_IMG_ICONOS.'detalle.png" ></a></td>';
            echo '<td class="align-middle"><a onclick="ModalComentarios('.$id_ticket.')">'.$ToComent.'<img src="'.RUTA_IMG_ICONOS.'comentarios.png" ></a></td>';
            echo '<td class="align-middle">'.$Editar.'</td>';
            echo '<td class="align-middle">'.$Eliminar.'</td>';
            echo '</tr>';
  
       }

		}

	}

	?>
	</tbody> 
	</table>
    </div>
