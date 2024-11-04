<?php
require('app/help.php');
$con = $ClassConexionBD->conectarBD();
function contarActividadesIncompletas($con, $opcion,$admin,$usuario): int
{
  $user = "";
  if($admin != 1){
    $user = "AND ds.id_personal_soporte =" .(int)$usuario;
  }
  // Define la condición en base a la opción recibida
  $condicion = "AND tb_puestos.tipo_puesto = 'Departamento Sistemas'";
  if ($opcion == 1) {
    $condicion = "AND tb_puestos.tipo_puesto <> 'Departamento Sistemas'";
  }

  // Consulta SQL para contar las actividades con porcentaje <> 100
  $sql = "SELECT COUNT(*) AS cantidad_incompletas
          FROM ds_soporte ds
          INNER JOIN tb_usuarios ON ds.id_personal = tb_usuarios.id
          INNER JOIN tb_estaciones ON tb_usuarios.id_gas = tb_estaciones.id
          INNER JOIN tb_puestos ON tb_usuarios.id_puesto = tb_puestos.id
          WHERE ds.porcentaje <> 100 $user $condicion AND ds.estado <> 4 AND ds.estado <> 0";

  $result = $con->query($sql);

  // Valor inicial de cantidad
  $cantidad = 0;
  if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $cantidad = (int)$row["cantidad_incompletas"];
  }

  return $cantidad;
}

// asignar porcentaje avance
function actualizarPorcentajeActividades($conexion, $fechaActual)
{
  // Consulta SQL para obtener actividades incompletas del ticket especificado
  $sql = "SELECT id_ticket, descripcion, fecha_inicio, fecha_termino, tiempo_solucion, porcentaje, id_personal_soporte 
          FROM ds_soporte
          WHERE porcentaje < 100 
          AND estado <> 4 
          AND estado <> 0 
          AND fecha_inicio != '0000-00-00 00:00:00'
          AND fecha_termino != '0000-00-00 00:00:00' ";

  $resultado = $conexion->query($sql);

  while ($actividad = $resultado->fetch_assoc()) {
    $id = $actividad['id_ticket'];
    $descripcion = $actividad['descripcion'];
    $fechaInicio = new DateTime($actividad['fecha_inicio']);
    $fechaTermino = new DateTime($actividad['fecha_termino']);
    $fechaActualDate = new DateTime($fechaActual);
    $tiempoSolucion = $actividad['tiempo_solucion'];
    $porcentajeActual = $actividad['porcentaje'];

    // Días transcurridos desde el inicio hasta la fecha actual
    $diasTranscurridos = $fechaInicio->diff($fechaActualDate)->days;
    // Si ya pasó la fecha de término, envía una alerta
    if ($fechaActualDate > $fechaTermino) {
      $diasRetraso = $fechaTermino->diff($fechaActualDate)->days;

      if ($diasRetraso > 1) {
        $actualizarPorcentaje = "UPDATE ds_soporte SET porcentaje = 60 ,estado = 5 WHERE id_ticket = $id";
        $conexion->query($actualizarPorcentaje);
      }
    }
    // Cálculo de porcentaje basado en la regla de tres
    if ($tiempoSolucion > 0 && $fechaActualDate <= $fechaTermino) { // Evitar división por cero
      $nuevoPorcentaje = ($diasTranscurridos / $tiempoSolucion) * 100;

      // Asegurar que el porcentaje no supere el 100%
      if ($nuevoPorcentaje > 100) {
        $nuevoPorcentaje = 100;
      }

      // Solo actualizar si el nuevo porcentaje es mayor al actual
      if ($nuevoPorcentaje > $porcentajeActual) {
        $actualizarPorcentaje = "UPDATE ds_soporte SET porcentaje = $nuevoPorcentaje WHERE id_ticket = $id";
        $conexion->query($actualizarPorcentaje);
      }
    }
  }
}
$fecha_del_dia = date("Y-m-d H:i:s");
actualizarPorcentajeActividades($con, $fecha_del_dia);
$admin = 0;
if($Session_IDUsuarioBD == 496){
  $admin = 1;
}
$sistemas = contarActividadesIncompletas($con,0,$admin,$Session_IDUsuarioBD);
$tickets = contarActividadesIncompletas($con,1,$admin,$Session_IDUsuarioBD);
?>
<!DOCTYPE html>
<html lang="es">

<head>
<meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Departamento Sistemas<?=$Session_IDUsuarioBD?></title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width initial-scale=1.0">
  <link rel="shortcut icon" href="<?= RUTA_IMG_ICONOS ?>/icono-web.png">
  <link rel="apple-touch-icon" href="<?= RUTA_IMG_ICONOS ?>/icono-web.png">
  <link rel="stylesheet" href="<?= RUTA_CSS ?>alertify.css">
  <link rel="stylesheet" href="<?= RUTA_CSS ?>themes/default.rtl.css">
  <link href="<?= RUTA_CSS ?>bootstrap.min.css" rel="stylesheet" />
  <link href="<?= RUTA_CSS ?>navbar-general.min.css" rel="stylesheet" />
  <link href="<?=RUTA_CSS?>cards-utilities.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script type="text/javascript" src="<?= RUTA_JS ?>alertify.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">

  <script>
    $(document).ready(function($) {
      $(".LoaderPage").fadeOut("slow");
    });

    function Soporte() {
      window.location.href = "sistemas";
    }

    function Actividades() {
      window.location.href = "actividades";
    }
    //Telegram
    function tokenTelegram(idUsuario) {
      $('#Modal').modal('show')
      $('#ContenidoModal').load('app/vistas/actividades/modal-token-telegram.php?idUsuario=' + idUsuario)
    }

    function actualizaTokenTelegram(idUsuario, dato) {
      let msg, msg2;

      if (dato == 0) {
        msg = "¿Deseas generar un nuevo codigo de verificacion?";
        msg2 = 'Nuevo token generado exitosamente';
        msg3 = 'Error al generar un nuevo codigo de verificación';
      } else {

        msg = "¿Deseas revocar el acceso a tu dispositivo movil que se encuentra registrado para la recepcion de tokens?";
        msg2 = 'Acceso revocado exitosamente';
        msg3 = 'Error al revocar el acceso';
      }

      var parametros = {
        "idUsuario": idUsuario
      };

      alertify.confirm('',
        function() {
          $.ajax({
            data: parametros,
            url: 'app/modelo/actualizar-token-telegram.php',
            type: 'post',
            beforeSend: function() {

            },
            complete: function() {

            },
            success: function(response) {
              if (response != 0) {
                tokenTelegram(idUsuario, response)
                alertify.success(msg2);

              } else {
                alertify.error(msg3);
              }

            }
          });
        },
        function() {

        }).setHeader('¡Alerta!').set({
        transition: 'zoom',
        message: msg,
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      }).show();
    }
  </script>
</head>

<body>
  <div class="LoaderPage"></div>

  <div class="wrapper">
    <!---------- SIDE BAR (LEFT) 
    <nav id="sidebar">
      <div class="sidebar-header text-center">
        <img class="" src="<?= RUTA_IMG_LOGO . "Logo.gif"; ?>" style="width: 100%;">
      </div>

      <ul class="list-unstyled components">
        <li>
          <a class="pointer" href="home">
            <i class="fa-solid fa-house" aria-hidden="true" style="padding-right: 10px;"></i>Inicio
          </a>
        </li>
      </ul>
    </nav>-->

    <!---------- DIV - CONTENIDO ---------->
    <div id="content" class="active">
      <!---------- NAV BAR - PRINCIPAL (TOP) ---------->

      <?php include_once "app/vistas/navbar/navbar-principal.php"; ?>
      <!---------- CONTENIDO PAGINA WEB---------->
      <div class="contendAG">

        <div class="row">
          <div class="col-xl-9 col-lg-9 col-md-12 col-sm-12">
            <h3 class="text-secondary" style="padding-left: 0; margin-bottom: 0; margin-top: 0;">Tickets y Actividades </h3>
          </div>
        </div>
        <hr>
      </div>


      <div class="row">


        <!----- 1. Soporte Estaciones ----->
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mt-1 mb-2" onclick="Soporte()">
          <section class="card3 plan2 shadow-lg">
            <div class="inner2">

              <div class="product-image"><img src="<?= RUTA_IMG_ICONOS; ?>soporte-tecnico.png" draggable="false" /></div>

              <div class="product-info">
                <p class="mb-0 pb-0">Tickets</p>
                <h2 class="mb-2">Soporte Estaciones</h2>

                <div class="row justify-content-center">
                  <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mt-2">
                  <span class="btn-label2"><i class="fa-solid fa-chart-simple"></i></span><?=$tickets?> Tickets
                  </div>

                </div>

              </div>

            </div>
          </section>
          
        </div>

        <!----- 2. Actividades Sistemas ----->
        <div class="col-xl-3 col-lg-4 col-md-6 col-sm-12 mt-1 mb-2" onclick="Actividades()">
          <section class="card3 plan2 shadow-lg">
            <div class="inner2">

              <div class="product-image"><img src="<?= RUTA_IMG_ICONOS; ?>sistemas-admin.png" draggable="false" /></div>

              <div class="product-info">
                <p class="mb-0 pb-0">Tickets</p>
                <h2 class="mb-2">Actividades</h2>

                <div class="row justify-content-center">
                  <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mt-2">
                  <span class="btn-label2"><i class="fa-solid fa-chart-simple"></i></span><?=$sistemas?> Tickets
                  </div>

                </div>

              </div>

            </div>
          </section>
        </div>


      </div>
    </div>
  </div>
  </div>

  <!----- MODAL Telegram ----->

  <div class="modal fade" id="Modal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <!-- Aquí se cargará el contenido dinámicamente -->
        <div id="ContenidoModal"></div>
      </div>
    </div>
  </div>

  <!---------- FUNCIONES - NAVBAR ---------->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
  <script src="<?= RUTA_JS ?>navbar-functions.js"></script>
  <script src="<?= RUTA_JS ?>bootstrap.min.js"></script>

</body>

</html>