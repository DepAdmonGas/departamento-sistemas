<?php 
include_once "../../help.php";
$con = $ClassConexionBD->conectarBD();
$idticket = $GET_IdRegistro;

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
tb_usuarios.id AS idUsuario,
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
    $fechaterminoreal = $row['fecha_termino_real'];
    $Valorestado = $row['estado'];
    $nomestacion = $row['nomestacion'];
    $solicitante = $row['nombre'];
    $idPersonalSoporte = $row['id_personal_soporte'];
    $PersonalSoporte = $ClassContenido->Responsable($idPersonalSoporte);

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
            $fechaInicio = FormatoFecha($explode1[0]);
        }

        $explode2 = explode(' ',$row['fecha_termino']);
        if($explode2[0] == '0000-00-00'){
            $fechaTermino = 'S/I';
        }else{
            $fechaTermino = FormatoFecha($explode2[0]);
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

        if($nomestacion == 'Comodines'){
            $EstacionDepartamento = $row['tipo_puesto'];
          }else{
            $EstacionDepartamento = $nomestacion;
          }
}

$sqlActividad = "SELECT * FROM ds_soporte_actividades WHERE id_ticket = '".$idticket."' ";
$resultActividad = mysqli_query($con, $sqlActividad);
$numeroActividad = mysqli_num_rows($resultActividad);

$sqlEvidencia = "SELECT * FROM ds_soporte_evidencia WHERE id_ticket = '".$idticket."' ";
$resultEvidencia = mysqli_query($con, $sqlEvidencia);
$numeroEvidencia = mysqli_num_rows($resultEvidencia);
?>
  
<!DOCTYPE html>
<html lang="es">
  
  <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Portal AdmonGas</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width initial-scale=1.0">
  <link rel="shortcut icon" href="<?=RUTA_IMG_ICONOS?>/icono-web.ico">
  <link rel="apple-touch-icon" href="<?=RUTA_IMG_ICONOS?>/icono-web.ico">
  <link rel="stylesheet" href="<?=RUTA_CSS ?>alertify.css">
  <link rel="stylesheet" href="<?=RUTA_CSS ?>themes/default.rtl.css">
  <link href="<?=RUTA_CSS;?>bootstrap.min.css" rel="stylesheet" />
  <link href="<?=RUTA_CSS;?>navbar-general.min.css" rel="stylesheet" />
 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script type="text/javascript" src="<?=RUTA_JS?>alertify.js"></script>
  
  <script type="text/javascript">
  
  $(document).ready(function($){
  $(".LoaderPage").fadeOut("slow");
  ContenidoComentarios(<?=$idticket;?>);
  });

  function ContenidoComentarios(idticket){
    $('#ContenidoComentarios').load('../app/vistas/soporte/contenido-lista-comentarios.php?idticket=' + idticket);
  }

  function GuardarComentario(idticket){

var Comentario = $('#Comentario').val();

if(Comentario != ""){
$('#Comentario').css('border',''); 

let parametros = {
"Accion" : "guardar-comentario",
"idticket" : idticket,
"comentario" : Comentario
};

$.ajax({
data:  parametros,
url:   '../app/modelo/controlador-sistemas.php',
type:  'post',
beforeSend: function() {
},
complete: function(){

},
success:  function (response) {

    ContenidoComentarios(idticket);


}
});

}else{
$('#Comentario').css('border','2px solid #A52525'); 
}

}

    function EditarTicket(val, idticket, opcion){
    let Detalle = val.value;

    let parametros = {
    "Accion" : "editar-registro",
    "Detalle" : Detalle,
    "idticket" : idticket,
    "opcion" : opcion
    };

    $.ajax({
    data:  parametros,
    url:   '../app/modelo/controlador-sistemas.php',
    type:  'post',
    beforeSend: function() {
    },
    complete: function(){

    },
    success:  function (response) {
    }
    });

    }

    function FinalizarSoporte(idticket){

    let parametros = {
    "Accion" : "finalizar-soporte",
    "idticket" : idticket
    };

    alertify.confirm('',
	function(){

    $.ajax({
    data:  parametros,
    url:   '../app/modelo/controlador-sistemas.php',
    type:  'post',
    beforeSend: function() {
    },
    complete: function(){

    },
    success:  function (response) {
     
        window.history.back();

    }
    });

	},
	function(){

	}).setHeader('Mensaje').set({transition:'zoom',message: '¿Desea finalizar el soporte?',labels:{ok:'Aceptar', cancel: 'Cancelar'}}).show();

    }

    function EditarActividad(val,idActividad,opcion){
    
    let Detalle = val.value;

    let parametros = {
    "Accion" : "editar-actividad",
    "Detalle" : Detalle,
    "idActividad" : idActividad,
    "opcion" : opcion
    };

    $.ajax({
    data:  parametros,
    url:   '../app/modelo/controlador-sistemas.php',
    type:  'post',
    beforeSend: function() {
    },
    complete: function(){

    },
    success:  function (response) {

    }
    });
    }
  </script>
    <style>
    .grayscale {
    filter: opacity(50%); 
  }
    .bg-sistemas{
    background : #D8EFDF;
  }
  .bg-personal{
    background : #D8E3EF;
  }
  </style>
  </head>
  
  <body>
  <div class="LoaderPage"></div>
  <div id="content">

  <!---------- NAV BAR (TOP) ---------->  
  <?php require('app/vistas/navbar/navbar-perfil.php');?>

  <div class="contendAG">     

  <div class="bg-white p-2 container">

  <div class="row">
    <div class="col-2">
        <h6 class="text-secondary"># Ticket</h6>
        <div>0<?=$id_ticket;?></div>
    </div>
    <div class="col-3">
        <h6 class="text-secondary">Fecha creación</h6>
        <div><?=$fechaCreacion;?></div>
    </div>
    <div class="col-3">
        <h6 class="text-secondary">Estación o Departamento</h6>
        <div><?=$EstacionDepartamento;?></div>
    </div>
    <div class="col-2">
        <h6 class="text-secondary">Solicitante</h6>
        <div><?=$solicitante;?></div>
    </div>
    <div class="col-2">
        <h6 class="text-secondary">Prioridad</h6>
        <div class="<?=$colorPrioridad;?>"><b><?=$prioridad;?></b></div>
    </div>
  </div>

    <h6 class="mt-2 text-secondary">Descripción</h6>
    <div><?=$descripcion;?></div>

    <hr>

    <h6 class="mt-2 text-secondary">Actividad:</h6>
    <table class="table table-sm table-bordered mt-1 mb-1 pb-1" style="font-size: .8em;">
        <thead class="table-light">
            <tr>
                <th class="align-middle">#</th>
                <th class="align-middle">Descripción de la actividad</th>
                <th class="align-middle">Fecha inicio</th>
                <th class="align-middle">Fecha termino</th>
                <th class="align-middle">Estado</th>
                <th class="align-middle text-center" width="24px"><img src="<?=RUTA_IMG_ICONOS;?>descargar.png"></th>
            </tr>
        </thead>
        <tbody>
        <?php

        if ($numeroActividad > 0) {
            $numActividad = 1;

            while($rowActividad = mysqli_fetch_array($resultActividad, MYSQLI_ASSOC)){
            $idActividad = $rowActividad['id'];
            $descripcionActividad = $rowActividad['descripcion'];
            $EstadoActividad = $rowActividad['estado'];

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
            echo '<td class="align-middle">'.$numActividad.'</td>';
            echo '<td class="align-middle">'.$descripcionActividad.'</td>';
            echo '<td class="align-middle">'.$AtividadFechaInicio.'</td>';
            echo '<td class="align-middle">'.$AtividadFechaTermino.'</td>';
            echo '<td class="p-0">
            <select class="form-control rounded-0 border-0" onchange="EditarActividad(this,'.$idActividad.',3)">
                <option value="'.$EstadoActividad.'">'.$EstadoDetalle.'</option>
                <option value="0">Pendiente</option>
                <option value="1">En proceso</option>
                <option value="2">Finalizada</option>
            </select>
        </div>';
            echo '<td class="align-middle">'.$Archivo.'</td>';
            echo '</tr>';

            $num++;
            }

        }else{
        echo "<tr><td colspan='5' class='text-center'><small>No se encontró información para mostrar</small></td></tr>";
        }

        ?>
        </tbody> 
        </table>

        <h6 class="text-secondary mt-2">Evidencia:</h6>
        <table class="table table-sm table-bordered mt-1 mb-1 pb-1" style="font-size: .8em;">
            <thead class="table-light">
                <tr>
                    <th class="align-middle">#</th>
                    <th class="align-middle">Descripción de la evidencia</th>
                    <th class="align-middle text-center" width="24px"><img src="<?=RUTA_IMG_ICONOS;?>descargar.png"></th>
                </tr>
            </thead>
            <tbody>
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

                $num++;
                }

            }else{
            echo "<tr><td colspan='4' class='text-center'><small>No se encontró información para mostrar</small></td></tr>";
            }

            ?>
            </tbody> 
            </table>

            <hr>

            <div class="row">
            <div class="col-7">
          
            <div class="row">
                <div class="col-6 mt-3">
                    <h6 class="text-secondary">Fecha inicio</h6>
                    <?=$fechaInicio;?>
                </div>
                <div class="col-6 mt-3">
                    <h6 class="text-secondary">Fecha termino</h6>
                    <?=$fechaTermino;?>
                </div>
                <div class="col-6 mt-3">
                <h6 class="text-secondary">Responsable</h6>
                <?=$PersonalSoporte;?>
                 </div>
                </div>

                <div class="row">
                <div class="col-6 mt-3">
                <h6 class="text-secondary">Porcentaje</h6>
                <?php 
              
                    if($Valorestado == 3 || $Valorestado == 4){
                        echo $porcentaje.' %';                    
                    }else{                    
                        echo '<select class="form-control rounded-0" onchange="EditarTicket(this,'.$idticket.',5)">';
                        echo '<option value="'.$porcentaje.'">'.$porcentaje.' %</option>';
                        for ($i = 1; $i <= 10; $i++) {
                        echo '<option value="'.$i.'0">'.$i.'0 %</option>';
                        }
                        echo '</select>';              
                    }
                ?>
                </div>
                <div class="col-6 mt-3">
                    <h6 class="text-secondary">Estado</h6>
                    <?php
                        if($Valorestado == 3 || $Valorestado == 4){
                            echo $estado;
                        }else{  
                            echo '<select class="form-control rounded-0" onchange="EditarTicket(this,'.$idticket.',1)">';
                            echo '<option value="'.$Valorestado.'">'.$estado.'</option>';
                                if($estado != 'Pendiente'){
                                echo '<option value="1">Pendiente</option>';
                                }
    
                                if($estado != 'En proceso'){
                                echo '<option value="2">En proceso</option>';
                                }
                            echo '</select>'; 
                        }
                    ?>
                </div>
            </div>
            <?php  
                if($Valorestado == 3 || $Valorestado == 4){

                }else{
                    echo '<div class="text-end mt-5"><button type="button" class="btn btn-primary rounded-0" onclick="FinalizarSoporte('.$idticket.')">Finalizar soporte</button></div>';
                }
            ?>
            
                </div>
                <div class="col-5">
                    <h6 class="text-secondary">Comentarios</h6>
                    <div id="ContenidoComentarios"></div>
                </div>
            </div>
            </div>

  </div>  
  </div>

    <div class="modal" id="ModalComentario">
        <div class="modal-dialog">
        <div class="modal-content" style="margin-top: 83px;">
        <div id="DivContenidoComentario"></div>
        </div>
        </div>
    </div>

    <div class="modal fade bd-example-modal-lg" id="ModalActividades" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">  
    <div class="modal-dialog modal-lg" role="document" style="margin-top: 83px;">
    <div class="modal-content">
    <div id="DivModalActividades"></div>
    </div>
    </div>
    </div>

  <script src="<?=RUTA_JS ?>bootstrap.min.js"></script>

  </body>
  </html> 