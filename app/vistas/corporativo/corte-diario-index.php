<?php
require "app/help.php";

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
  <!---------- LIBRERIAS DEL DATATABLE ---------->
  <link href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.0.3/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/datatables.min.css" rel="stylesheet">
  <script type="text/javascript">
    $(document).ready(function($) {
      $(".LoaderPage").fadeOut("slow");
      contenidoCorteDiario();
    });

    function contenidoCorteDiario(){

        $('#contenidoCorteDiario').load('app/vistas/corporativo/contenido-tabla-corte-diario.php', function() {
        $('#tabla-corte-diario').DataTable({
          "language": {
            "url": "<?= RUTA_JS ?>/es-ES.json"
          },
          "order": [
            [0, "desc"]
          ],
          "lengthMenu": [50, 75, 100, 150],
          "columnDefs": [{
              "orderable": false,
              "targets": [3]
            },
            {
              "searchable": false,
              "targets": [3]
            }
          ]
        });
      });

    }
    function activarCorte(idDias,usuario){
      Detalle ="Sistemas";
      var parametros = {
        "accion":"activar-corte",
        "idDias": idDias,
        "Detalle": Detalle,
        "usuario":usuario
      };
      alertify.confirm("Confirmación", "¿Estás seguro de que deseas activar este corte?",
        function() {
          // Si el usuario confirma, se ejecuta la solicitud AJAX
          $.ajax({
            data: parametros,
            url: 'app/controlador/controladorCorteDiario.php',
            type: 'post',
            beforeSend: function() {},
            complete: function() {},
            success: function(response) {
              if (response == 1) {
                alertify.success('Elemento activado correctamente');
                contenidoCorteDiario();
              }
            }
          });
        },
        function() {
          // Si el usuario cancela, se muestra un mensaje opcional o no se hace nada
          //alertify.error('Operación cancelada');
        }
      );
    }
    function finalizarCorte(idDias){
      Detalle ="Sistemas";
      var parametros = {
        "accion":"finalizar-corte",
        "idDias": idDias
      };
      alertify.confirm("Confirmación", "¿Estás seguro de que deseas finalizar este corte?",
        function() {
          // Si el usuario confirma, se ejecuta la solicitud AJAX
          $.ajax({
            data: parametros,
            url: 'app/controlador/controladorCorteDiario.php',
            type: 'post',
            beforeSend: function() {},
            complete: function() {},
            success: function(response) {
              if (response == 1) {
                alertify.success('Elemento finalizado correctamente');
                contenidoCorteDiario();
              }
            }
          });
        },
        function() {
          // Si el usuario cancela, se muestra un mensaje opcional o no se hace nada
          //alertify.error('Operación cancelada');
        }
      );
    }
    
  </script>
</head>

<body>
  <div class="LoaderPage"></div>
  <div id="content">

    <!---------- NAV BAR (TOP) ---------->
    <?php require('app/vistas/navbar/navbar-perfil.php'); ?>

    <div class="contendAG">

    <div aria-label="breadcrumb">
    <ol class="breadcrumb breadcrumb-caret">
    <li class="breadcrumb-item"><a onclick="history.back()" class="text-uppercase text-primary pointer"><i class="fa-solid fa-house"></i> Inicio</a></li>
    <li aria-current="page" class="breadcrumb-item active text-uppercase">Corte Diario</li>
    </ol>
    </div>

    <h3 class="text-secondary">Corte Diario</h3>

    <div id="contenidoCorteDiario" class="mt-3"></div>

    </div>

  </div>
  </div>

  <script src="<?= RUTA_JS ?>bootstrap.min.js"></script>
  <!---------- LIBRERIAS DEL DATATABLE ---------->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.0.3/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/datatables.min.js"></script>

</body>

</html>