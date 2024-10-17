<?php  
include_once "../../help.php";
$con = $ClassConexionBD->conectarBD();
$estadoBuscar = $_GET['estado'];

if($estadoBuscar == 0){
    $TextBuscar = 'Registros en proceso de creación';
    $Buscar = "ds_soporte.id_personal = '".$Session_IDUsuarioBD."' AND ds_soporte.estado = 0" ;

}else if($estadoBuscar == 1){
    $TextBuscar = 'Registros pendientes';
    $Buscar = "ds_soporte.id_personal = '".$Session_IDUsuarioBD."' AND ds_soporte.estado = 1" ;

}else if($estadoBuscar == 2){
    $TextBuscar = 'Registros en proceso';
    $Buscar = "ds_soporte.id_personal = '".$Session_IDUsuarioBD."' AND ds_soporte.estado = 2" ;

}else if($estadoBuscar == 3){
    $TextBuscar = 'Registros finalizados';
    $Buscar = "ds_soporte.id_personal = '".$Session_IDUsuarioBD."' AND ds_soporte.estado = 3" ;

}else if($estadoBuscar == 4){
    $TextBuscar = 'Registros cancelados';
    $Buscar = "ds_soporte.id_personal = '".$Session_IDUsuarioBD."' AND ds_soporte.estado = 4" ;

}else if($estadoBuscar == 5){
    $TextBuscar = 'Todos los registros';
    $Buscar = "ds_soporte.id_personal = '".$Session_IDUsuarioBD."' " ;   
}
 
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
    ON tb_usuarios.id_puesto = tb_puestos.id
    WHERE $Buscar ORDER BY ds_soporte.id_ticket DESC";
    $result = mysqli_query($con, $sql);
    $numero = mysqli_num_rows($result);
?>

<h6 class="mt-2"><?=$TextBuscar;?></h6>

<div class="table-responsive">
<table id="tabla_sistemas_busqueda" class="custom-table mt-2" style="font-size: 14px;" width="100%">

<thead class="navbar-bg">		 
    <tr>
            <th class="align-middle text-center"># Ticket</th>
			<th class="align-middle text-center">Descripción</th>
            <th class="align-middle text-center">Prioridad</th>

            <th class="align-middle text-center">Fecha inicio</th>
            <th class="align-middle text-center">Fecha termino programado</th>

            <th class="align-middle text-center">Estado</th>    
            <th class="align-middle text-center">Porcentaje</th> 
            <th class="align-middle text-center">Responsable</th>         
            <th class="align-middle text-center">Fecha termino real</th>

            <th class="align-middle text-center" width="24px"><img src="<?=RUTA_IMG_ICONOS;?>detalle.png"></th>
            <th class="align-middle text-center" width="24px"><img src="<?=RUTA_IMG_ICONOS;?>comentarios.png"></th>
			<th class="align-middle text-center" width="24px"><img src="<?=RUTA_IMG_ICONOS;?>editar.png"></th>
			<th class="align-middle text-center" width="24px"><img src="<?=RUTA_IMG_ICONOS;?>eliminar.png"></th>
		</tr>
	</thead>

	<tbody>
	<?php

	if ($numero > 0) {

		while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
		$id_ticket = $row['id_ticket'];
		$descripcion = $row['descripcion'];
        $prioridad = $row['prioridad'];
        $porcentaje = $row['porcentaje'];
        $ToComentarios = $ClassContenido->ToComentarios($id_ticket);
        $idPersonalSoporte = $row['id_personal_soporte'];
        $PersonalSoporte = $ClassContenido->Responsable($idPersonalSoporte);
        $fechaterminoreal = $row['fecha_termino_real'];
              

        $explode = explode(' ',$row['fecha_creacion']);
        if($explode[0] == '0000-00-00'){
            $fechaCreacion = 'S/I';
        }else{
            $fechaCreacion = FormatoFecha($explode[0]).', '.date("g:i a",strtotime($explode[1]));
        }
        $colorPrioridad = '';
        if($prioridad == 'Baja'){
            $colorPrioridad = 'text-primary';
        }else if($prioridad == 'Media'){
            $colorPrioridad = 'text-warning';
        }else if($prioridad == 'Alta'){
            $colorPrioridad = 'text-danger';
        }

        if($row['estado'] == 0){

            $trColor = 'style="background-color: #fcfcda"';
            $estado = 'Creando';
            $Editar = '<a onclick="EditarTicket('.$id_ticket.')"><img src="'.RUTA_IMG_ICONOS.'editar.png" ></a>';
            $Eliminar = '<img src="'.RUTA_IMG_ICONOS.'eliminar.png" onclick="EliminarTicket('.$id_ticket.')">';

        }else if($row['estado'] == 1){

            $trColor = 'style="background-color: #ffffff"';
            $estado = 'Pendiente';
            $Editar = '<img class="grayscale" src="'.RUTA_IMG_ICONOS.'editar.png" >';
            $Eliminar = '<img class="grayscale" src="'.RUTA_IMG_ICONOS.'eliminar.png">';

        }else if($row['estado'] == 2){

            $trColor = 'style="background-color: #cfe2ff"';
            $estado = 'En proceso';
            $Editar = '<img class="grayscale" src="'.RUTA_IMG_ICONOS.'editar.png" >';
            $Eliminar = '<img class="grayscale" src="'.RUTA_IMG_ICONOS.'eliminar.png">';

        }else if($row['estado'] == 3){

            $trColor = 'style="background-color: #b0f2c2"';
            $estado = 'Finalizado';
            $Editar = '<img class="grayscale" src="'.RUTA_IMG_ICONOS.'editar.png" >';
            $Eliminar = '<img class="grayscale" src="'.RUTA_IMG_ICONOS.'eliminar.png">';

        }else if($row['estado'] == 4){

            $trColor = 'style="background-color: #ffb6af"';
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

          $explode3 = explode(' ',$row['fecha_termino_real']);
        if($explode3[0] == '0000-00-00'){
            $fechaterminoreal = 'S/I';
        }else{
            $fechaterminoreal = '<div class="text-success">'.FormatoFecha($explode3[0]).', '.date("g:i a",strtotime($explode3[1])).'</div>';
            $FechaCierreTicket = date("Y-m-d",strtotime($explode3[0]."+ 3 days"));
        }

        if($row['estado'] == 0){

            echo '<tr '.$trColor.'>';
            echo '<th class="align-middle text-center"><b>0'.$id_ticket.'</b></th>';
            echo '<td class="align-middle text-center">'.$descripcion.'</td>';
            echo '<td class="align-middle text-center '.$colorPrioridad.'"><small><b>'.$prioridad.'</b></small></td>';
    
            echo '<td class="align-middle text-center"><small class="text-dark"><b>'.$fechaInicio.'</b></small></td>';
            echo '<td class="align-middle text-center"><small class="text-dark"><b>'.$fechaTermino.'</b></small></td>';
    
            echo '<td class="align-middle text-center">'.$estado.'</td>';
            echo '<td class="align-middle text-center">'.$porcentaje.' %</td>';
            echo '<td class="align-middle text-center">'.$PersonalSoporte.'</td>';
            echo '<td class="align-middle text-center"><b>'.$fechaterminoreal.'</b></td>';  
    
            echo '<td class="align-middle text-center"><a onclick="ModalDetalle('.$id_ticket.')"><img src="'.RUTA_IMG_ICONOS.'detalle.png" ></a></td>';
            echo '<td class="align-middle text-center"><a onclick="ModalComentarios('.$id_ticket.')">'.$ToComent.'<img src="'.RUTA_IMG_ICONOS.'comentarios.png" ></a></td>';
            echo '<td class="align-middle text-center">'.$Editar.'</td>';
            echo '<td class="align-middle text-center">'.$Eliminar.'</td>';
            echo '</tr>';

        }else if($row['estado'] == 1){

            echo '<tr '.$trColor.'>';
            echo '<th class="align-middle text-center"><b>0'.$id_ticket.'</b></th>';
            echo '<td class="align-middle text-center">'.$descripcion.'</td>';
            echo '<td class="align-middle text-center '.$colorPrioridad.'"><small><b>'.$prioridad.'</b></small></td>';
    
            echo '<td class="align-middle text-center"><small class="text-dark"><b>'.$fechaInicio.'</b></small></td>';
            echo '<td class="align-middle text-center"><small class="text-dark"><b>'.$fechaTermino.'</b></small></td>';
    
            echo '<td class="align-middle text-center">'.$estado.'</td>';
            echo '<td class="align-middle text-center">'.$porcentaje.' %</td>';
            echo '<td class="align-middle text-center">'.$PersonalSoporte.'</td>';
            echo '<td class="align-middle text-center"><b>'.$fechaterminoreal.'</b></td>';  
    
            echo '<td class="align-middle text-center"><a onclick="ModalDetalle('.$id_ticket.')"><img src="'.RUTA_IMG_ICONOS.'detalle.png" ></a></td>';
            echo '<td class="align-middle text-center"><a onclick="ModalComentarios('.$id_ticket.')">'.$ToComent.'<img src="'.RUTA_IMG_ICONOS.'comentarios.png" ></a></td>';
            echo '<td class="align-middle text-center">'.$Editar.'</td>';
            echo '<td class="align-middle text-center">'.$Eliminar.'</td>';
            echo '</tr>';

        }else if($row['estado'] == 2){

            echo '<tr '.$trColor.'>';
            echo '<th class="align-middle text-center"><b>0'.$id_ticket.'</b></th>';
            echo '<td class="align-middle text-center">'.$descripcion.'</td>';
            echo '<td class="align-middle text-center '.$colorPrioridad.'"><small><b>'.$prioridad.'</b></small></td>';

            echo '<td class="align-middle text-center"><small class="text-dark"><b>'.$fechaInicio.'</b></small></td>';
            echo '<td class="align-middle text-center"><small class="text-dark"><b>'.$fechaTermino.'</b></small></td>';
    
            echo '<td class="align-middle text-center">'.$estado.'</td>';
            echo '<td class="align-middle text-center">'.$porcentaje.' %</td>';
            echo '<td class="align-middle text-center">'.$PersonalSoporte.'</td>';
            echo '<td class="align-middle text-center"><b>'.$fechaterminoreal.'</b></td>';  
    
            echo '<td class="align-middle text-center"><a onclick="ModalDetalle('.$id_ticket.')"><img src="'.RUTA_IMG_ICONOS.'detalle.png" ></a></td>';
            echo '<td class="align-middle text-center"><a onclick="ModalComentarios('.$id_ticket.')">'.$ToComent.'<img src="'.RUTA_IMG_ICONOS.'comentarios.png" ></a></td>';
            echo '<td class="align-middle text-center">'.$Editar.'</td>';
            echo '<td class="align-middle text-center">'.$Eliminar.'</td>';
            echo '</tr>';

        }else if($row['estado'] == 3){

            if($FechaCierreTicket >= $fecha_del_dia){

                echo '<tr '.$trColor.'>';
                echo '<th class="align-middle text-center"><b>0'.$id_ticket.'</b></th>';
                echo '<td class="align-middle text-center">'.$descripcion.'</td>';
                echo '<td class="align-middle text-center '.$colorPrioridad.'"><small><b>'.$prioridad.'</b></small></td>';
    
                echo '<td class="align-middle text-center"><small class="text-dark"><b>'.$fechaInicio.'</b></small></td>';
                echo '<td class="align-middle text-center"><small class="text-dark"><b>'.$fechaTermino.'</b></small></td>';
        
                echo '<td class="align-middle text-center">'.$estado.'</td>';
                echo '<td class="align-middle text-center">'.$porcentaje.' %</td>';
                echo '<td class="align-middle text-center">'.$PersonalSoporte.'</td>';
                echo '<td class="align-middle text-center"><b>'.$fechaterminoreal.'</b></td>';  
        
                echo '<td class="align-middle text-center"><a onclick="ModalDetalle('.$id_ticket.')"><img src="'.RUTA_IMG_ICONOS.'detalle.png" ></a></td>';
                echo '<td class="align-middle text-center"><a onclick="ModalComentarios('.$id_ticket.')">'.$ToComent.'<img src="'.RUTA_IMG_ICONOS.'comentarios.png" ></a></td>';
                echo '<td class="align-middle text-center">'.$Editar.'</td>';
                echo '<td class="align-middle text-center">'.$Eliminar.'</td>';
                echo '</tr>';
            
            }

        }else if($row['estado'] == 4){

            echo '<tr '.$trColor.'>';
                echo '<th class="align-middle text-center"><b>0'.$id_ticket.'</b></th>';
                echo '<td class="align-middle text-center">'.$descripcion.'</td>';
                echo '<td class="align-middle text-center '.$colorPrioridad.'"><small><b>'.$prioridad.'</b></small></td>';
        
                echo '<td class="align-middle text-center"><small class="text-dark"><b>'.$fechaInicio.'</b></small></td>';
                echo '<td class="align-middle text-center"><small class="text-dark"><b>'.$fechaTermino.'</b></small></td>';
        
                echo '<td class="align-middle text-center">'.$estado.'</td>';
                echo '<td class="align-middle text-center">'.$porcentaje.' %</td>';
                echo '<td class="align-middle text-center">'.$PersonalSoporte.'</td>';
                echo '<td class="align-middle text-center"><b>'.$fechaterminoreal.'</b></td>';  
        
                echo '<td class="align-middle text-center"><a onclick="ModalDetalle('.$id_ticket.')"><img src="'.RUTA_IMG_ICONOS.'detalle.png" ></a></td>';
                echo '<td class="align-middle text-center"><a onclick="ModalComentarios('.$id_ticket.')">'.$ToComent.'<img src="'.RUTA_IMG_ICONOS.'comentarios.png" ></a></td>';
                echo '<td class="align-middle text-center">'.$Editar.'</td>';
                echo '<td class="align-middle text-center">'.$Eliminar.'</td>';
                echo '</tr>';
            
        }

		}

	}

	?>
	</tbody> 
	</table>
    </div>
