<?php
require "app/help.php";
$numLista = isset($_GET['numLista']) ? $_GET['numLista'] : 0;
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

  <!--Selectize-->
  <link href="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/css/selectize.default.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/js/standalone/selectize.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function($) {
      $(".LoaderPage").fadeOut("slow");
      contenidoPersonal(<?= $numLista ?>);
    });

    function home() {
      window.location.href = "home";
    }

    function contenidoPersonal(numLista) {

      $('#contenidoPersonal').load('app/vistas/personal/contenido-tabla-personal.php?numLista=' + numLista, function() {
        $('#tabla-personal').DataTable({
          "language": {
            "url": "<?= RUTA_JS ?>/es-ES.json"
          },
          "order": [
            [8, "asc"]
          ],
          "lengthMenu": [25, 50, 75, 100],
          "columnDefs": [{
              "orderable": false,
              "targets": [6]
            },
            {
              "searchable": false,
              "targets": [6]
            },
            {
              "targets": [1, 2, 3, 4, 5], // Asegúrate de incluir las columnas con inputs
              "searchable": true // Hacer que las columnas con inputs sean buscables
            }
          ]
        });
      });


    }

    function modalAgregarPersonal() {
      $('#ModalPersonal').modal('show');
      $('#DivModalPersonal').load('app/vistas/personal/modal-agregar-personal.php');
    }

    function agregarPersonal() {
      var nombre = $('#nombre').val();
      var puesto = $('#puesto').val();
      var selectizePuesto = $('#puesto')[0].selectize;
      var usuario = $('#usuario').val();
      var password = $('#password').val();
      var estacion = $('#estacion').val();
      var selectizeEstacion = $('#estacion')[0].selectize;
      var email = $('#email').val();
      var telefono = $('#telefono').val();
      if (nombre != "") {
        $('#nombre').css('border', '');
        if (puesto != "") {
          $(selectizePuesto.$control).css('border', '');
          if (usuario != "") {
            $('#usuario').css('border', '');
            if (password != "") {
              $('#password').css('border', '');
              if (estacion != "") {
                $(selectizeEstacion.$control).css('border', '');

                var parametros = {
                  "accion": "nuevo-personal",
                  "nombre": nombre,
                  "puesto": puesto,
                  "usuario": usuario,
                  "password": password,
                  "estacion": estacion,
                  "email": email,
                  "telefono": telefono
                };
                $.ajax({
                  data: parametros,
                  url: 'app/controlador/personal.php',
                  type: 'post',
                  beforeSend: function() {},
                  complete: function() {},
                  success: function(response) {
                    if (response == 1) {
                      alertify.success('Personal agregado correctamente');
                      $('#ModalPersonal').modal('hide');
                      contenidoPersonal(0)
                    } else {
                      alertify.error('Hubo un error');
                    }

                  }
                });


              } else {
                $(selectizeEstacion.$control).css('border', '2px solid #A52525');
              }
            } else {
              $('#password').css('border', '2px solid #A52525');
            }
          } else {
            $('#usuario').css('border', '2px solid #A52525');
          }
        } else {
          $(selectizePuesto.$control).css('border', '2px solid #A52525');
        }
      } else {
        $('#nombre').css('border', '2px solid #A52525');
      }
    }

    function eliminarPersonal(id) {
      var parametros = {
        "accion": "eliminar-personal",
        "id": id
      };

      alertify.confirm("Confirmación", "¿Estás seguro de que deseas eliminar este elemento?",
        function() {
          // Si el usuario confirma, se ejecuta la solicitud AJAX
          $.ajax({
            data: parametros,
            url: 'app/controlador/personal.php',
            type: 'post',
            beforeSend: function() {},
            complete: function() {},
            success: function(response) {
              if (response == 1) {
                alertify.success('Elemento eliminado correctamente')
                contenidoPersonal(0)
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
          console.log("Valor actualizado: " + nuevoValor);
          // Aquí puedes realizar un AJAX o alguna acción para guardar el cambio en el servidor
        }
      }
    }


    function editarPersonal(celda,id, columna) {
      concepto = celda.textContent;
      switch (columna) {
        case 1:
          columna = "nombre";
          break;
        case 2:
          columna = "usuario";
          break;
        case 3:
          columna = "password";
          break;
      }

      var parametros = {
        "accion": "editar-personal",
        "id": id,
        "concepto": concepto,
        "columna": columna
      };

      $.ajax({
        data: parametros,
        url: 'app/controlador/personal.php',
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
              Personal
            </li>
          </ol>
        </div>
        <div class="row">
          <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
            <h3 class="text-secondary" style="padding-left: 0; margin-bottom: 0; margin-top: 0;">
              Personal
            </h3>
          </div>

          <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2 text-end">


            <button class="btn btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              Estaciones
            </button>
            <div class="dropdown-menu">
              <?php
              $sql = "SELECT numlista, nombre FROM tb_estaciones WHERE estatus = 0";
              $result = mysqli_query($con, $sql);
              while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
              ?>
                <a class="dropdown-item pointer" onclick="contenidoPersonal(<?= $row['numlista'] ?>)"><?= $row['nombre'] ?></a>
              <?php } ?>
            </div>

            <button type="button" class="btn btn-labeled2 btn-primary float-end ms-2"
              onclick="modalAgregarPersonal()">
              <span class="btn-label2"><i class="fa-solid fa-plus"></i></span>Agregar</button>
          </div>

        </div>
        <hr>
      </div>
      <div class="col-12">
        <div id="contenidoPersonal" class="mt-3"></div>
      </div>
    </div>
    <!--Modal Agregar Personal-->
    <div class="modal fade" id="ModalPersonal" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" id="DivModalPersonal">
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