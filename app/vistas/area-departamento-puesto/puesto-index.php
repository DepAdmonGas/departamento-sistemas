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
  <link rel="shortcut icon" href="<?= RUTA_IMG_ICONOS ?>/icono-web.png">
  <link rel="apple-touch-icon" href="<?= RUTA_IMG_ICONOS ?>/icono-web.png">
  <link rel="stylesheet" href="<?= RUTA_CSS ?>alertify.css">
  <link rel="stylesheet" href="<?= RUTA_CSS ?>themes/default.rtl.css">
  <link href="<?= RUTA_CSS ?>bootstrap.min.css" rel="stylesheet" />
  <link href="<?= RUTA_CSS ?>navbar-general.min.css" rel="stylesheet" />
  <link href="<?= RUTA_CSS ?>cards-utilities.min.css" rel="stylesheet" />

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
      contenidoPuesto();
    });

    function home() {
      window.location.href = "home";
    }

    function contenidoPuesto() {

      $('#contenidoPuesto').load('app/vistas/area-departamento-puesto/contenido-tabla-puesto.php', function() {
        $('#tabla-puestos').DataTable({
          "language": {
            "url": "<?= RUTA_JS ?>/es-ES.json"
          },
          "order": [
            [8, "asc"]
          ],
          "lengthMenu": [25, 50, 75, 100],
          "columnDefs": [{
              "orderable": false,
              "targets": [2]
            },
            {
              "searchable": false,
              "targets": [2]
            }
          ]
        });
      });

    }

    function nuevoPuesto() {
      var parametros = {
        "accion": "nuevo-puesto"
      };
      $.ajax({
        data: parametros,
        url: 'app/controlador/puesto.php',
        type: 'post',
        beforeSend: function() {},
        complete: function() {},
        success: function(response) {
          if (response == 0) {
            alertify.error('error');
          } else {
            contenidoPuesto();
            setTimeout(function() {
              var celda = document.getElementById("concepto-" + response);
              if (celda) {
                var divEditable = celda.querySelector('div');
                if (divEditable) {
                  divEditable.contentEditable = "true"; // Habilitar la edición
                  divEditable.focus(); // Poner el cursor en el div
                }
              }
            }, 100); // Ajusta el tiempo según sea necesario


          }

        }
      });

    }

    function editarPuesto(val, idPuesto) {

      var concepto = val.textContent;

      var parametros = {
        "accion": "editar-puesto",
        "idPuesto": idPuesto,
        "concepto": concepto
      };

      $.ajax({
        data: parametros,
        url: 'app/controlador/puesto.php',
        type: 'post',
        beforeSend: function() {},
        complete: function() {},
        success: function(response) {
          if (response != 1) {
            alertify.error('error');
          }
        }
      });

    }

    function eliminarPuesto(idPuesto) {
      var parametros = {
        "accion": "eliminar-puesto",
        "idPuesto": idPuesto
      };

      alertify.confirm("Confirmación", "¿Estás seguro de que deseas eliminar este elemento?",
        function() {
          // Si el usuario confirma, se ejecuta la solicitud AJAX
          $.ajax({
            data: parametros,
            url: 'app/controlador/puesto.php',
            type: 'post',
            beforeSend: function() {},
            complete: function() {},
            success: function(response) {
              if (response == 1) {
                alertify.success('Elemento eliminado correctamente');
                contenidoPuesto();
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

    function habilitarEdicion(celda) {
      var divEditable = celda.querySelector('div');

      if (divEditable) {
        // Verificar si el contenido es editable
        if (divEditable.contentEditable === "false") {
          divEditable.contentEditable = "true"; // Habilitar la edición
          divEditable.style.cursor = "text"; // Cambiar el cursor a escritura
          divEditable.focus(); // Poner el foco en el div para que el usuario pueda empezar a escribir
        } else {
          divEditable.contentEditable = "false"; // Deshabilitar la edición si ya estaba habilitada
          var nuevoValor = divEditable.textContent; // Obtener el nuevo valor
          // Aquí puedes realizar un AJAX o alguna acción para guardar el cambio en el servidor
        }
      }
    }
  </script>
</head>

<body>
  <div class="LoaderPage"></div>
  <div id="content">

    <!---------- NAV BAR (TOP) ---------->
    <?php require('app/vistas/navbar/navbar-perfil.php'); ?>

    <div class="contendAG">

      <div class="col-12">
        <div aria-label="breadcrumb" style="padding-left: 0; margin-bottom: 0;">
          <ol class="breadcrumb breadcrumb-caret">
            <li class="breadcrumb-item"><a onclick="home()" class="text-uppercase text-primary pointer"><i class="fa-solid fa-house"></i>
                Inicio</a></li>
            <li aria-current="page" class="breadcrumb-item active text-uppercase">
              Puesto
            </li>
          </ol>
        </div>
        <div class="row">
          <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
            <h3 class="text-secondary" style="padding-left: 0; margin-bottom: 0; margin-top: 0;">
              Puesto
            </h3>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
            <button type="button" class="btn btn-labeled2 btn-primary float-end ms-2"
              onclick="nuevoPuesto()">
              <span class="btn-label2"><i class="fa-solid fa-plus"></i></span>Agregar</button>
          </div>

        </div>
        <hr>
      </div>
      <div class="col-12">
        <div id="contenidoPuesto" class="mt-3"></div>
      </div>
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