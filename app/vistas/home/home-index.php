<?php
require('app/help.php');
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
  <link href="<?= RUTA_CSS; ?>bootstrap.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="<?= RUTA_CSS ?>alertify.css">
  <link rel="stylesheet" href="<?= RUTA_CSS ?>themes/default.rtl.css">
  <link href="<?= RUTA_CSS; ?>navbar-utilities.min.css" rel="stylesheet" />
  <link href="<?= RUTA_CSS; ?>cards-utilities.min.css" rel="stylesheet" />
  <script src="<?= RUTA_JS ?>size-window.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script type="text/javascript" src="<?= RUTA_JS ?>alertify.js"></script>

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
    <!---------- SIDE BAR (LEFT) ---------->
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
    </nav>

    <!---------- DIV - CONTENIDO ---------->
    <div id="content">
      <!---------- NAV BAR - PRINCIPAL (TOP) ---------->

      <?php include_once "app/vistas/navbar/navbar-principal.php"; ?>
      <!---------- CONTENIDO PAGINA WEB---------->
      <div class="contendAG">

        <div class="row">
          <div class="col-4">
            <div class="bg-white rounded-top pointer" onclick="Soporte()">
              <div class="p-4">
                <div class="fs-3 text-center text-secondary float-center">SOPORTE SISTEMAS</div>
              </div>
              <div class="text-primary text-end pb-1 pe-3">
                <small>8 de 10 Tickets</small>
              </div>
              <div class="progress rounded-0">
                <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>

          <div class="col-4">
            <div class="bg-white rounded-top pointer" onclick="Actividades()">
              <div class="p-4">
                <div class="fs-3 text-center text-secondary float-center">ACTIVIDADES</div>
              </div>
              <div class="text-primary text-end pb-1 pe-3">
                <small>8 de 10 Tickets</small>
              </div>
              <div class="progress rounded-0">
                <div class="progress-bar progress-bar-striped bg-success" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
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