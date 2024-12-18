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
  $tiemposolucion = $row['tiempo_solucion'];
  $fechaterminoreal = $row['fecha_termino_real'];
  $Valorestado = $row['estado'];
  $nomestacion = $row['nomestacion'];
  $solicitante = $row['nombre'];
  $tiemposolucion = $row['tiempo_solucion'];
  $idPersonalSoporte = $row['id_personal_soporte'];
  $PersonalSoporte = $ClassContenido->Responsable($idPersonalSoporte);

  // se usa para mostrar las fechas en caso de que ya se hañlla asignado la atividad
  $explodeInicio = explode(' ', $row['fecha_inicio']);
  $fechainicio = '';
  if ($explodeInicio[0] != '0000-00-00') {
    $fechainicio = FormatoFecha($explodeInicio[0]);
  }

  $explodeTermino = explode(' ', $row['fecha_termino']);
  $fechatermino = '';
  if ($explodeTermino[0] != '0000-00-00') {
    $fechatermino = FormatoFecha($explodeTermino[0]);
  }


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
    $ocultar = "";
  } else {
    $fechaTermino = $explode2[0];
    $ocultar = "d-none";
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
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>

  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
  <script type="text/javascript" src="<?= RUTA_JS ?>alertify.js"></script>

  <script type="text/javascript">
    $(document).ready(function() {
      $(".LoaderPage").fadeOut("slow");
      $('#dias_habiles').focus();
      // JavaScript para detectar el cambio y actualizar automáticamente
      $('#dias_habiles').on('change', function() {
        $('#diasHabilesForm').submit();
        $('#dias_habiles').focus(); // Vuelve a enfocar el campo después de enviar el formulario
      });

    });


    function regresarP() {
      window.location.href = '../actividades';
    }

    function EditarTicket(Detalle, idticket, opcion) {
      if (Detalle == "") {
        alertify.error("Ingresar fecha termino")
      } else {
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
          complete: function() {},
          success: function(response) {
            if(Detalle != "" && opcion == 3){
              regresarP();
            }
          }
        });
      }
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
        complete: function() {},
        success: function(response) {

        }
      });
    }

    function FinalizarSoporte(idticket) {
      let fin = 2;
      if ('<?= $EstacionDepartamento ?>' == 'Departamento Sistemas') {
        fin = 3;
      }
      let parametros = {
        "Accion": "finalizar-soporte",
        "idticket": idticket,
        "finalizar": fin
      };

      alertify.confirm('',
        function() {

          $.ajax({
            data: parametros,
            url: '../app/modelo/controlador-sistemas.php',
            type: 'post',
            beforeSend: function() {},
            complete: function() {

            },
            success: function(response) {

              window.history.back();

            }
          });

        },
        function() {

        }).setHeader('Mensaje').set({
        transition: 'zoom',
        message: '¿Desea finalizar el soporte?',
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      }).show();

    }

    function fechasActividad(ticket, fecha) {
      $('#ModalFecha').modal('show');
      $('#DivModalFecha').load('../app/vistas/actividades/modal-fecha-actividades.php?idticket=' + ticket + '&fecha=' + fecha);
    }

    function FinalizarFechasAsignacion() {
      $('#ModalFecha').modal('hide');
      location.reload();
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
            <li class="breadcrumb-item"><a onclick="regresarP()" class="text-uppercase text-primary pointer"><i
                  class="fa-solid fa-chevron-left"></i>
                Departamento Sistemas</a></li>
            <li aria-current="page" class="breadcrumb-item active text-uppercase">
              Seguimiento
            </li>
          </ol>
        </div>
        <div class="row">
          <div class="col-10">
            <h3 class="text-secondary" style="padding-left: 0; margin-bottom: 0; margin-top: 0;">
              Seguimiento
            </h3>
          </div>
        </div>

        <div class="row mt-3 text-center d-flex justify-content-around">
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
        <div class="ms-5">
          <h6 class="mt-2 fw-bold">Descripción</h6>
          <div><?= $descripcion; ?></div>
        </div>


        <hr>

        <?php
        if ($fechatermino == '') {
          $UltimoRegistro = $ClassContenido->UltimoRegistro($Session_IDUsuarioBD);

          if ($UltimoRegistro['nomestacion'] == 'Comodines') {
            $EstacionDepartamentoUltimoRegistro = $UltimoRegistro['tipopuesto'];
          } else {
            $EstacionDepartamentoUltimoRegistro = $UltimoRegistro['nomestacion'];
          }
          // Verificacion si tiene fechas asignadas
          if ($UltimoRegistro['fechatermino'] != "") {
            $explode3 = explode(' ', $UltimoRegistro['fechatermino']);
            if ($explode3[0] == '0000-00-00') {
              $FechaUltimoRegistro = FormatoFecha($fecha_del_dia);
            } else {
              $FechaUltimoRegistro = FormatoFecha($explode3[0]);
            }
            $advertencia = '<div class="alert alert-warning" role="alert">
                  Información del ultimo registro agregado en la base de datos.</br>
                  # Ticket <b>0' . $UltimoRegistro['idticket'] . '</b>, Fecha termino: <b>' . $FechaUltimoRegistro . '</b>, Estación o Departamento:  <b>' . $EstacionDepartamentoUltimoRegistro . '</b> 
                  </div>';
          } else {
            $explode3[0] = '0000-00-00';
            $advertencia = '<div class="alert alert-warning" role="alert">
                  No hay ultimo registro agregado en la Base de Datos.</br></div>';
          }


          echo $advertencia;

          //Codigo que permite validar los dias habiles-->
          // Obtén la fecha de hoy
          $fechaHoy = new DateTime();

          // Crea un objeto DateTime con el valor de explode3[0]
          $fechaExplode = new DateTime($explode3[0]);

          // Compara las fechas
          if ($fechaExplode <= $fechaHoy) {
            // Si la fecha es menor o igual a hoy, asigna fecha de hoy +1 día
            $fechaInicio = clone $fechaHoy;
            $fechaInicio->modify('+1 day');
          } else {
            // Si la fecha es futura, usa la fecha original de explode3[0]
            $fechaInicio = $fechaExplode->modify('+1 day');
          }


          // Validación para que, si la fecha de inicio cae en sábado o domingo, empiece desde el próximo lunes
          if ($fechaInicio->format('N') == 6) {
            // Si es sábado, mover al lunes (2 días más)
            $fechaInicio->modify('+2 days');
          } elseif ($fechaInicio->format('N') == 7) {
            // Si es domingo, mover al lunes (1 día más)
            $fechaInicio->modify('+1 day');
          }
          // Verifica si se ha enviado el número de días hábiles desde el formulario
          $diasHabiles = isset($_GET['dias_habiles']) ? (int)$_GET['dias_habiles'] : 2; // Valor predeterminado: 2 días

          // Contador de días hábiles
          $contador = 0;
          $fechaFin = clone $fechaInicio;

          while ($contador < $diasHabiles) {
            // Si es día de la semana (lunes a viernes), contamos el día
            if ($fechaFin->format('N') < 6) {
              $contador++;
            }
            // Sumamos un día (independientemente de si es hábil o no)
            $fechaFin->modify('+1 day');
          }

          // Restamos un día al final porque el bucle suma un día extra
          $fechaFin->modify('-1 day');
          $onclick = "onclick='fechasActividad($idticket, \"" . $fechaInicio->format('Y-m-d') . "\")'";
        } else {
          $onclick = "";
        }
        if ($numeroActividad > 0) {
        ?>
          <div class="text-end p-3">
            <button type="button" class="btn btn-labeled2 btn-primary float-end" <?= $onclick ?>>
              <span class="btn-label2">
                <i class="fa-regular fa-calendar-check"></i>
              </span>Asignar Fechas
            </button>
          </div>
          <h6 class="mt-2 text-secondary">Actividad:</h6>
          <div class="table-responsive">
            <table id="tabla-sistemas" class="custom-table mt-2" style="font-size: 14px;" width="100%">
              <thead class="navbar-bg">
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
                $numActividad = 1;

                while ($rowActividad = mysqli_fetch_array($resultActividad, MYSQLI_ASSOC)) {
                  $idActividad = $rowActividad['id'];
                  $descripcionActividad = $rowActividad['descripcion'];
                  $EstadoActividad = $rowActividad['estado'];

                  if ($rowActividad['fecha_inicio'] == '0000-00-00') {
                    $AtividadFechaInicio = 'S/I';
                  } else {
                    $AtividadFechaInicio = FormatoFecha($rowActividad['fecha_inicio']);
                  }

                  if ($rowActividad['fecha_termino'] == '0000-00-00') {
                    $AtividadFechaTermino = 'S/I';
                  } else {
                    $AtividadFechaTermino = FormatoFecha($rowActividad['fecha_termino']);
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
                  echo '<td class="p-0"> ' . $AtividadFechaInicio . '</td>';
                  echo '<td class="p-0"> ' . $AtividadFechaTermino . '</td>';
                  echo '<td class="p-0">
                            <select class="form-control rounded-0 border-0" onchange="EditarActividad(this,' . $idActividad . ',3)">
                                <option value="' . $EstadoActividad . '">' . $EstadoDetalle . '</option>
                                <option value="0">Pendiente</option>
                                <option value="1">En proceso</option>
                                <option value="2">Finalizada</option>
                            </select>
                          </td>';
                  echo '<td class="align-middle">' . $Archivo . '</td>';
                  echo '</tr>';

                  $numActividad++;
                }


                ?>
              </tbody>
            </table>
          </div>
          <hr>
        <?php } ?>

        <div class="row">
          <?php if ($numeroActividad > 0) {
            $idActividad = '';
            $sqlActividad = "SELECT fecha_termino FROM ds_soporte_actividades WHERE id_ticket = '" . $idticket . "' ORDER BY id ASC";
            $resultActividad = mysqli_query($con, $sqlActividad);

            while ($rowActividad = mysqli_fetch_array($resultActividad, MYSQLI_ASSOC)) {
              $idActividad = $rowActividad['fecha_termino'];
            }
            $fechaTerminoGlobal = 'S/I';
            $fechaFinGuardar = '';
            if ($idActividad != '0000-00-00') {
              $fechaTerminoGlobal = FormatoFecha($idActividad);
              $fechaFinGuardar = $idActividad;
            }
          } elseif ($numeroActividad == 0) {
            $idActividad = '';
            $sqlActividad = "SELECT fecha_termino FROM ds_soporte WHERE id_ticket = '" . $idticket . "' ORDER BY id_ticket ASC";
            $resultActividad = mysqli_query($con, $sqlActividad);

            while ($rowActividad = mysqli_fetch_array($resultActividad, MYSQLI_ASSOC)) {
              $idActividad = $rowActividad['fecha_termino'];
            }
            $fechaTerminoGlobal = 'S/I';
            if ($idActividad != '0000-00-00 00:00:00') {
              $fechaTerminoGlobal = FormatoFecha($idActividad);
              $fechaFinGuardar = $idActividad;
            } else {
              $fechaTerminoGlobal = FormatoFecha($fechaFin->format('Y-m-d'));
              $fechaFinGuardar = $fechaFin->format('Y-m-d');
          ?>
              <div class="col-3 mt-3">
                <form method="get" id="diasHabilesForm">
                  <h6 class="text-secondary" for="dias_habiles">Tiempo solucion</h6>
                  <?php if ($fechatermino == '') : ?>
                    <input type="number" name="dias_habiles" id="dias_habiles" value="<?php echo $diasHabiles; ?>" min="1" style="text-align: right;">
                  <?php else : echo $tiemposolucion;
                  endif; ?>
                </form>
              </div>
            <?php

            }
          } else {
            $fechaTerminoGlobal = FormatoFecha($fechaFin->format('Y-m-d'));
            $fechaFinGuardar = $fechaFin->format('Y-m-d');

            ?>

            <div class="col-3 mt-3">
              <form method="get" id="diasHabilesForm">
                <h6 class="text-secondary" for="dias_habiles">Tiempo solucion</h6>
                <?php if ($fechatermino == '') : ?>
                  <input type="number" name="dias_habiles" id="dias_habiles" value="<?php echo $diasHabiles; ?>" min="1" style="text-align: right;">
                <?php else : echo $tiemposolucion;
                endif; ?>
              </form>
            </div>
          <?php } ?>

          <div class="col-3 mt-3">
            <h6 class="text-secondary">Fecha inicio</h6>
            <?php if ($fechatermino == '') {
              echo FormatoFecha($fechaInicio->format('Y-m-d'));
            } else {
              echo $fechainicio;
            }
            ?>
          </div>
          <div class="col-3 mt-3">
            <h6 class="text-secondary">Fecha termino</h6>
            <?php if ($fechatermino == '') {
              echo $fechaTerminoGlobal;
            } else {
              echo $fechatermino;
            }
            ?>
          </div>
          <div class="col-3 mt-3">
            <h6 class="text-secondary">Responsable</h6>
            <?= $PersonalSoporte; ?>
          </div>
        </div>

        <div class="text-end mt-3">
          <?php if ($fechaTermino == '') { ?>
            <button type="button" class="btn btn-labeled2 btn-success"
              onclick="
              EditarTicket('<?= $fechaInicio->format('Y-m-d') ?>',<?= $idticket; ?>,2);
              EditarTicket('<?= $fechaFinGuardar ?>',<?= $idticket; ?>,3)">
              <span class="btn-label2"><i class="fa-solid fa-check"></i></span>Finalizar edicion</button>
          <?php } else { ?>
            <button type="button" class="btn btn-labeled2 btn-success" onclick="FinalizarSoporte(<?= $idticket; ?>)">
              <span class="btn-label2"><i class="fa-solid fa-check"></i></span>Finalizar soporte</button>
          <?php } ?>
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

  <div class="modal" id="ModalFecha">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div id="DivModalFecha"></div>
      </div>
    </div>
  </div>


  <script src="<?= RUTA_JS ?>bootstrap.min.js"></script>

</body>

</html>