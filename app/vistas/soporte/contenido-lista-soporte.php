<?php   
include_once "../../help.php";
$con = $ClassConexionBD->conectarBD();
 
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
        WHERE ds_soporte.id_personal = '".$Session_IDUsuarioBD."' ORDER BY ds_soporte.fecha_termino_real ASC";
        $result = mysqli_query($con, $sql);
        $numero = mysqli_num_rows($result);
?>


<div class="table-responsive">
<table id="tabla_sistemas" class="custom-table mt-2" style="font-size: 14px;" width="100%">

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
            <th class="align-middle text-center" width="24px"><img src="<?=RUTA_IMG_ICONOS;?>comentarios.png"></th>
            <th class="align-middle text-center" width="20"><i class="fas fa-ellipsis-v"></i></th>
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
        $categoria = $row['categoria'];
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
        $Editar = '<a class="dropdown-item grayscale"><i class="fa-solid fa-pencil"></i> Editar</a>';
        $Eliminar = '<a class="dropdown-item grayscale" ><i class="fa-regular fa-trash-can"></i> Eliminar</a>';
        $Detalle = '<a class="dropdown-item" onclick="ModalDetalle('.$id_ticket.')"><i class="fa-regular fa-eye"></i> Detalle</a>';
        if($row['estado'] == 0){

            $trColor = 'style="background-color: #fcfcda"';
            $estado = 'Creando';
            $Editar = '<a class="dropdown-item" onclick="EditarTicket('.$id_ticket.')"><i class="fa-solid fa-pencil"></i> Editar</a>';
            $Eliminar = '<a class="dropdown-item" onclick="EliminarTicket('.$id_ticket.')"><i class="fa-regular fa-trash-can"></i> Eliminar</a>';

        }else if($row['estado'] == 1){

            $trColor = 'style="background-color: #ffffff"';
            $estado = 'Pendiente';
            

        }else if($row['estado'] == 2){
            
            $trColor = 'style="background-color: #cfe2ff"';
            $estado = 'En proceso';
            

        }else if($row['estado'] == 3){

            $trColor = 'style="background-color: #b0f2c2"';
            $estado = 'Finalizado';
            

        }else if($row['estado'] == 4){

            $trColor = 'style="background-color: #ffb6af"';
            $estado = 'Cancelado';
            

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
            echo '<td class="align-middle text-center"><a onclick="ModalComentarios('.$id_ticket.')">'.$ToComent.'<img src="'.RUTA_IMG_ICONOS.'comentarios.png" ></a></td>';
            echo '<td class="align-middle text-center"> 
            <div class="dropdown">
            <a class="btn btn-sm btn-icon-only text-dropdown-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                ' . $Detalle . '
                ' . $Editar . '
                ' . $Eliminar . '
            </div>
            </div>
            </td>';
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
    
            echo '<td class="align-middle text-center"><a onclick="ModalComentarios('.$id_ticket.')">'.$ToComent.'<img src="'.RUTA_IMG_ICONOS.'comentarios.png" ></a></td>';
            echo '<td class="align-middle text-center"> 
            <div class="dropdown">
            <a class="btn btn-sm btn-icon-only text-dropdown-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                ' . $Detalle . '
                ' . $Editar . '
                ' . $Eliminar . '
            </div>
            </div>
            </td>';
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
    
            echo '<td class="align-middle text-center"><a onclick="ModalComentarios('.$id_ticket.')">'.$ToComent.'<img src="'.RUTA_IMG_ICONOS.'comentarios.png" ></a></td>';
            echo '<td class="align-middle text-center"> 
            <div class="dropdown">
            <a class="btn btn-sm btn-icon-only text-dropdown-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fas fa-ellipsis-v"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                ' . $Detalle . '
                ' . $Editar . '
                ' . $Eliminar . '
            </div>
            </div>
            </td>';
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
        
                echo '<td class="align-middle text-center"><a onclick="ModalComentarios('.$id_ticket.')">'.$ToComent.'<img src="'.RUTA_IMG_ICONOS.'comentarios.png" ></a></td>';
                echo '<td class="align-middle text-center"> 
                <div class="dropdown">
                <a class="btn btn-sm btn-icon-only text-dropdown-light" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="fas fa-ellipsis-v"></i>
                </a>
                <div class="dropdown-menu dropdown-menu-right dropdown-menu-arrow">
                    ' . $Detalle . '
                    ' . $Editar . '
                    ' . $Eliminar . '
                </div>
                </div>
                </td>';
            echo '</tr>';
            
            }

        }
                
		}

	}

	?>
	</tbody> 
	</table>
    </div>

