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
  <link rel="stylesheet" href="<?= RUTA_CSS ?>alertify.css">
  <link rel="stylesheet" href="<?= RUTA_CSS ?>themes/default.rtl.css">
  <link href="<?= RUTA_CSS; ?>bootstrap.min.css" rel="stylesheet" />
  <link href="<?= RUTA_CSS; ?>navbar-general.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
  <link href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.0.3/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/datatables.min.css" rel="stylesheet">
  <script type="text/javascript">
    $(document).ready(function($) {
    $(".LoaderPage").fadeOut("slow");
    ContenidoActividades();
    });

    function ContenidoActividades(){
        $('#ContenidoActividades').load('app/vistas/actividades/contenido-tabla-actividades.php', function() {
        // Una vez que se carguen los datos en la tabla, inicializa DataTables
        $('#tabla-actividades').DataTable({
          "language": {
            "url": "<?= RUTA_JS ?>/es-ES.json"
          },
          "order": [
            [8, "asc"]
          ], // Ordenar por la novena columna de forma ascendente (comienza desde 0)
          "lengthMenu": [25, 50, 75, 100], // Número de registros que se mostrarán
          "columnDefs": [{
              "orderable": false
            }, // Deshabilitar ordenación en las columnas 1, 2 y 3 (comenzando desde 0)
            {
              "searchable": false
            } // Deshabilitar filtrado en las columnas 1, 2 y 3 (comenzando desde 0)
          ]
        });
      });
    }

    function nuevo(){

        var parametros = {
        "Accion": "nuevo-folio",
        "Categoria": "Sistemas"
      };

      $.ajax({
        data: parametros,
        url: 'app/modelo/controlador-sistemas.php',
        type: 'post',
        beforeSend: function() {},
        complete: function() {

        },
        success: function(response) {

          if (response != 0) {
            window.location.href = "nueva-actividad/" + response;
          } else {
            alertify.error('Error al crear');
          }

        }
      });

    }

    function modalBuscar(){

    }
  </script>
 </head>
 <body>
  <div class="LoaderPage"></div>
  <div id="content">
    <!---------- NAV BAR (TOP) ---------->
    <?php require('app/vistas/navbar/navbar-perfil.php'); ?>

    <div class="contendAG">

      <!-- Inicio -->
      <div class="float-end">
      <div class="dropdown dropdown-sm d-inline ms-2">
      <button type="button" class="btn dropdown-toggle btn-secondary rounded-0" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
      <i class="fa-solid fa-ellipsis"></i>
      </button>
      <ul class="dropdown-menu rounded-0">
      <li onclick="nuevo()"><a class="dropdown-item c-pointer"> <i class="fa-solid fa-plus"></i> Agregar</a></li>
      <li onclick="modalBuscar()"><a class="dropdown-item c-pointer"> <i class="fa-solid fa-magnifying-glass"></i> Buscar</a></li>
      </ul>
      </div>
      </div>
      <!-- Fin -->

      <div id="ContenidoActividades"></div>

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
    <!---------- LIBRERIAS DEL DATATABLE ---------->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.0.3/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/datatables.min.js"></script>

</body>
</html>