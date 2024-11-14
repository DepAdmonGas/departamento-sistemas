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
      contenidoSolicitudCheque();
    });

    function contenidoSolicitudCheque() {

      $('#contenidoSolicitudCheque').load('app/vistas/solicitud-cheque/contenido-tabla-solicitud-cheque.php', function() {
        $('#tabla-solicitud-cheque').DataTable({
          "language": {
            "url": "<?= RUTA_JS ?>/es-ES.json"
          },
          "order": [
            [0, "desc"]
          ],
          "lengthMenu": [25, 50, 75, 100],
          "columnDefs": [{
              "orderable": false,
              "targets": [8]
            },
            {
              "searchable": false,
              "targets": [8]
            }
          ]
        });
      });
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
          console.log("Valor actualizado: " + nuevoValor);
          // Aquí puedes realizar un AJAX o alguna acción para guardar el cambio en el servidor
        }
      }
    }

    function editarCheque(celda, id, columna , id_estacion = 0) {
      concepto = celda.textContent;
      switch (columna) {
        case 1:
          columna = "fecha";
          break;
        case 2:
          columna = "hora";
          break;
        case 3:
          columna = "beneficiario";
          break;
        case 4:
          columna = "monto";
          break;
        case 5:
          columna = "no_factura";
          break;
        case 6:
          columna = "concepto";
          break;
        case 7:
          columna = "solicitante";
          break;
        case 8:
          if(id_estacion == 8){
            columna = "razonSocialSolicitud";
          }
          columna = "razonSocialEstacion";
          break;
      }

      var parametros = {
        "accion": "editar-cheque",
        "id": id,
        "id_estacion" : id_estacion,
        "concepto": concepto,
        "columna": columna
      };

      $.ajax({
        data: parametros,
        url: 'app/controlador/controladorCheque.php',
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
  </script>

<body>
  <div class="LoaderPage"></div>
  <div id="content">

    <!---------- NAV BAR (TOP) ---------->
    <?php require('app/vistas/navbar/navbar-perfil.php'); ?>

    <div class="contendAG">

      <div aria-label="breadcrumb">
        <ol class="breadcrumb breadcrumb-caret">
          <li class="breadcrumb-item"><a onclick="history.back()" class="text-uppercase text-primary pointer"><i class="fa-solid fa-house"></i> Inicio</a></li>
          <li aria-current="page" class="breadcrumb-item active text-uppercase">Solicitud Cheque</li>
        </ol>
      </div>

      <h3 class="text-secondary">Solicitud Cheque</h3>
      <div id="contenidoSolicitudCheque" class="mt-3"></div>

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