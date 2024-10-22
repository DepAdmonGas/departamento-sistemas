<?php
include_once "app/help.php";
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
WHERE ds_soporte.id_ticket = '" . $idticket . "'  ";
$result = mysqli_query($con, $sql);
$numero = mysqli_num_rows($result);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
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

  $explode = explode(' ', $row['fecha_creacion']);
  if ($explode[0] == '0000-00-00') {
    $fechaCreacion = 'S/I';
  } else {
    $fechaCreacion = FormatoFecha($explode[0]) . ', ' . date("g:i a", strtotime($explode[1]));
  }

  if ($prioridad == 'Baja') {
    $colorPrioridad = 'text-primary';
  } else if ($prioridad == 'Media') {
    $colorPrioridad = 'text-warning';
  } else if ($prioridad == 'Alta') {
    $colorPrioridad = 'text-danger';
  }

  $explode1 = explode(' ', $row['fecha_inicio']);
  if ($explode1[0] == '0000-00-00') {
    $fechaInicio = '';
  } else {
    $fechaInicio = $explode1[0];
  }

  $explode2 = explode(' ', $row['fecha_termino']);
  if ($explode2[0] == '0000-00-00') {
    $fechaTermino = '';
  } else {
    $fechaTermino = $explode2[0];
  }

  if ($row['estado'] == 0) {

    $estado = 'Creando';
  } else if ($row['estado'] == 1) {

    $estado = 'Pendiente';
  } else if ($row['estado'] == 2) {

    $estado = 'En proceso';
  } else if ($row['estado'] == 3) {

    $estado = 'Finalizado';
  } else if ($row['estado'] == 4) {

    $estado = 'Cancelado';
  }

  if ($nomestacion == 'Comodines') {
    $EstacionDepartamento = $row['tipo_puesto'];
  } else {
    $EstacionDepartamento = $nomestacion;
  }
}

$sqlActividad = "SELECT * FROM ds_soporte_actividades WHERE id_ticket = '" . $idticket . "' ";
$resultActividad = mysqli_query($con, $sqlActividad);
$numeroActividad = mysqli_num_rows($resultActividad);

$sqlEvidencia = "SELECT * FROM ds_soporte_evidencia WHERE id_ticket = '" . $idticket . "' ";
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
  <link rel="shortcut icon" href="<?= RUTA_IMG_ICONOS ?>/icono-web.ico">
  <link rel="apple-touch-icon" href="<?= RUTA_IMG_ICONOS ?>/icono-web.ico">
  <link rel="stylesheet" href="<?= RUTA_CSS ?>alertify.css">
  <link rel="stylesheet" href="<?= RUTA_CSS ?>themes/default.rtl.css">
  <link href="<?= RUTA_CSS; ?>bootstrap.min.css" rel="stylesheet" />
  <link href="<?= RUTA_CSS; ?>navbar-general.min.css" rel="stylesheet" />

  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script type="text/javascript" src="<?= RUTA_JS ?>alertify.js"></script>

  <script type="text/javascript">
    $(document).ready(function($) {
      $(".LoaderPage").fadeOut("slow");
      ContenidoComentarios(<?= $idticket; ?>);
    });

    function regresarP() {
      window.history.back();
    }

    function ContenidoComentarios(idticket) {
      $('#ContenidoComentarios').load('../app/vistas/soporte/contenido-lista-comentarios.php?idticket=' + idticket);
    }

    function GuardarComentario(idticket) {

      var Comentario = $('#Comentario').val();

      if (Comentario != "") {
        $('#Comentario').css('border', '');

        let parametros = {
          "Accion": "guardar-comentario",
          "idticket": idticket,
          "comentario": Comentario
        };

        $.ajax({
          data: parametros,
          url: '../app/modelo/controlador-sistemas.php',
          type: 'post',
          beforeSend: function() {},
          complete: function() {

          },
          success: function(response) {

            ContenidoComentarios(idticket);


          }
        });

      } else {
        $('#Comentario').css('border', '2px solid #A52525');
      }

    }

    function EditarTicket(val, idticket, opcion) {
      let Detalle = val.value;

      let parametros = {
        "Accion": "editar-registro",
        "Detalle": Detalle,
        "idticket": idticket,
        "opcion": opcion
      };

      $.ajax({
        data: parametros,
        url: '../app/modelo/controlador-sistemas.php',
        type: 'post',
        beforeSend: function() {},
        complete: function() {

        },
        success: function(response) {

        }
      });

    }

    function EditarActividad(val, idActividad, opcion) {

      let Detalle = val.value;

      let parametros = {
        "Accion": "editar-actividad",
        "Detalle": Detalle,
        "idActividad": idActividad,
        "opcion": opcion
      };

      $.ajax({
        data: parametros,
        url: '../app/modelo/controlador-sistemas.php',
        type: 'post',
        beforeSend: function() {},
        complete: function() {

        },
        success: function(response) {

        }
      });
    }

    function FinalizarEdicion(idticket) {
      let Responsable = $('#Responsable').val();

      if (Responsable != 0) {
        $('#Responsable').css('border', '');
        regresarP();
      } else {
        $('#Responsable').css('border', '2px solid #A52525');
      }

    }
  </script>
  <style>
    .grayscale {
      filter: opacity(50%);
    }
  </style>
</head>

<body>
  <div class="LoaderPage"></div>
  <div id="content">

    <!---------- NAV BAR (TOP) ---------->
    <?php require('app/vistas/navbar/navbar-perfil.php'); ?>

    <div class="contendAG">

      <div class="bg-white p-2 container">

        <div aria-label="breadcrumb" style="padding-left: 0; margin-bottom: 0;">
          <ol class="breadcrumb breadcrumb-caret">
            <li class="breadcrumb-item"><a onclick="history.back()" class="text-uppercase text-primary pointer"><i
                  class="fa-solid fa-chevron-left"></i>
                Departamento Sistemas</a></li>
            <li aria-current="page" class="breadcrumb-item active text-uppercase">
              Responsable
            </li>
          </ol>
        </div>
        <div class="row">
          <div class="col-10">
            <h3 class="text-secondary" style="padding-left: 0; margin-bottom: 0; margin-top: 0;">
              Responsable
            </h3>
          </div>
        </div>

        <div class="row mt-3">
          <div class="col-2">
            <h6 class="fw-bold"># Ticket</h6>
            <div>0<?= $id_ticket; ?></div>
          </div>
          <div class="col-3">
            <h6 class="fw-bold">Fecha creación</h6>
            <div><?= $fechaCreacion; ?></div>
          </div>
          <div class="col-3">
            <h6 class="fw-bold">Estación o Departamento</h6>
            <div><?= $EstacionDepartamento; ?></div>
          </div>
          <div class="col-2">
            <h6 class="fw-bold">Solicitante</h6>
            <div><?= $solicitante; ?></div>
          </div>
          <div class="col-2">
            <h6 class="fw-bold">Prioridad</h6>
            <div class="<?= $colorPrioridad; ?>"><b><?= $prioridad; ?></b></div>
          </div>
        </div>

        <h6 class="mt-2 fw-bold">Descripción</h6>
        <div><?= $descripcion; ?></div>

        <hr>

        <h6 class="mt-2 fw-bold">Actividad:</h6>


        <div class="table-responsive">
          <table id="tabla-principal" class="custom-table " style="font-size: .8em;" width="100%">
            <thead class="tables-bg">
              <tr>
                <th class="align-middle">#</th>
                <th class="align-middle">Descripción de la actividad</th>
                <th class="align-middle">Fecha inicio</th>
                <th class="align-middle">Fecha termino</th>
                <th class="align-middle">Estado</th>
                <th class="align-middle text-center" width="24px"><img src="<?= RUTA_IMG_ICONOS; ?>descargar.png"></th>
              </tr>
            </thead>
            <tbody>
              <?php

              if ($numeroActividad > 0) {
                $numActividad = 1;

                while ($rowActividad = mysqli_fetch_array($resultActividad, MYSQLI_ASSOC)) {
                  $idActividad = $rowActividad['id'];
                  $descripcionActividad = $rowActividad['descripcion'];
                  $EstadoActividad = $rowActividad['estado'];

                  if ($rowActividad['fecha_inicio'] == '0000-00-00') {
                    $AtividadFechaInicio = '';
                  } else {
                    $AtividadFechaInicio = $rowActividad['fecha_inicio'];
                  }

                  if ($rowActividad['fecha_termino'] == '0000-00-00') {
                    $AtividadFechaTermino = '';
                  } else {
                    $AtividadFechaTermino = $rowActividad['fecha_termino'];
                  }

                  if ($rowActividad['estado'] == 0) {
                    $EstadoDetalle = 'Pendiente';
                  } else if ($rowActividad['estado'] == 1) {
                    $EstadoDetalle = 'En proceso';
                  } else if ($rowActividad['estado'] == 2) {
                    $EstadoDetalle = 'Finalizada';
                  }

                  if ($rowActividad['archivo'] == "") {
                    $Archivo = '<a><img src="' . RUTA_IMG_ICONOS . 'eliminar.png" ></a>';
                  } else {
                    $Archivo = '<a href="' . RUTA_ARCHIVOS . $rowActividad['archivo'] . '" download><img src="' . RUTA_IMG_ICONOS . 'descargar.png" ></a>';
                  }

                  echo '<tr>';
                  echo '<th class="align-middle">' . $numActividad . '</th>';
                  echo '<td class="align-middle">' . $descripcionActividad . '</td>';
                  echo '<td class="p-0"><input type="date" class="border-0 form-control" value="' . $AtividadFechaInicio . '" onchange="EditarActividad(this,' . $idActividad . ',1)"></td>';
                  echo '<td class="p-0"><input type="date" class="border-0 form-control" value="' . $AtividadFechaTermino . '" onchange="EditarActividad(this,' . $idActividad . ',2)"></td>';
                  echo '<td class="p-0">
                <select class="form-control rounded-0 border-0" onchange="EditarActividad(this,' . $idActividad . ',3)">
                    <option value="' . $EstadoActividad . '">' . $EstadoDetalle . '</option>
                    <option value="0">Pendiente</option>
                    <option value="1">En proceso</option>
                    <option value="2">Finalizada</option>
                </select>
            </div>';
                  echo '<td class="align-middle">' . $Archivo . '</td>';
                  echo '</tr>';

                  $numActividad++;
                }
              } else {
                echo "<tr><td colspan='5' class='text-center'><small>No se encontró información para mostrar</small></td></tr>";
              }

              ?>
            </tbody>
          </table>
        </div>
        <hr>

        <div class="row">
          <div class="col-6 mt-3">
            <h6 class="fw-bold">Responsable</h6>
            <select class="form-control rounded-0" onchange="EditarTicket(this,<?= $idticket; ?>,4)" id="Responsable">
              <option value="<?= $idPersonalSoporte; ?>"><?= $PersonalSoporte; ?></option>
              <?php

              $sql_resp = "SELECT id, nombre FROM tb_usuarios WHERE id_puesto = 2 ";
              $result_resp = mysqli_query($con, $sql_resp);
              $numero_resp = mysqli_num_rows($result_resp);
              while ($row_resp = mysqli_fetch_array($result_resp, MYSQLI_ASSOC)) {

                echo '<option value="' . $row_resp['id'] . '">' . $row_resp['nombre'] . '</option>';
              }

              ?>
            </select>
            <div class="text-end mt-3"><button type="button" class="btn btn-primary rounded-0" onclick="FinalizarEdicion(<?= $idticket; ?>)">Finalizar edición</button></div>
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

  <script src="<?= RUTA_JS ?>bootstrap.min.js"></script>

</body>

</html>