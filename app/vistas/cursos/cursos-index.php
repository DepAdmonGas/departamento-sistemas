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

  <!--Selectize-->
  <link href="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/css/selectize.default.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/js/standalone/selectize.min.js"></script>
  <script type="text/javascript">
    $(document).ready(function($) {
      $(".LoaderPage").fadeOut("slow");
      contenidoCursos();
    });

    function home() {
      window.location.href = "home";
    }

    function modalModulos() {
      $('#CursoModulos').modal('show');
      $('#DivCursoModulos').load('app/vistas/cursos/modal-modulos.php');
    }

    function modalTemas() {
      $('#CursoTemas').modal('show');
      $('#DivCursoTemas').load('app/vistas/cursos/modal-tema.php');
    }

    function agregarModulo() {
      var nombre = $('#titulo').val();
      if (nombre != "") {
        $('#titulo').css('border', '');

        var parametros = {
          "accion": "agregar-modulo",
          "titulo": nombre
        };

        $.ajax({
          data: parametros,
          url: 'app/controlador/controladorCurso.php',
          type: 'post',
          beforeSend: function() {},
          complete: function() {},
          success: function(response) {
            console.log(response)
            if (response == 1) {
              modalModulos()
            }
          }
        });

      } else {
        $('#titulo').css('border', '2px solid #A52525');
      }
    }
    function cuestionario(idTema){
      window.location.href = "cursos-cuestionario/"+idTema;
    }
    function agregarTema() {
      var modulo = $('#modulo').val();
      var selectizemodulo = $('#modulo')[0].selectize;
      var nombre_tema = $('#nombre_tema').val();

      if (modulo != "") {
        $(selectizemodulo.$control).css('border', '');
        if (nombre_tema != "") {
          $('#nombre_tema').css('border', '');

          var parametros = {
            "accion": "nuevo-tema",
            "modulo": modulo,
            "nombre_tema": nombre_tema
          };
          $.ajax({
            data: parametros,
            url: 'app/controlador/controladorCurso.php',
            type: 'post',
            beforeSend: function() {},
            complete: function() {},
            success: function(response) {
              if (response != 0) {
                alertify.success('Tema agregado correctamente')
                $('#CursoTemas').modal('hide')
                cuestionario(response)
              } else {
                alertify.error('Hubo un error')
              }
            }
          });

        } else {
          $('#nombre_tema').css('border', '2px solid #A52525');
        }
      } else {
        $(selectizetema.$control).css('border', '2px solid #A52525');
      }

    }


    function contenidoCursos() {

      $('#contenidoCursos').load('app/vistas/cursos/contenido-tabla-cursos.php', function() {
        $('#tabla-cursos').DataTable({
          "language": {
            "url": "<?= RUTA_JS ?>/es-ES.json"
          },
          "order": [
            [8, "asc"]
          ],
          "lengthMenu": [25, 50, 75, 100],
          "columnDefs": [{
              "orderable": false,
              "targets": [4, 5]
            },
            {
              "searchable": false,
              "targets": [4, 5]
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
          // Aquí puedes realizar un AJAX o alguna acción para guardar el cambio en el servidor
        }
      }
    }

    function editarCurso(celda, id, numTema, columna) {
      concepto = celda.textContent;
      switch (columna) {
        case 1:
          columna = "titulo";
          break;
        case 2:
          columna = "categoria";
          break;
      }

      var parametros = {
        "accion": "editar-tema",
        "id": id,
        "concepto": concepto,
        "columna": columna,
        "numTema": numTema
      };

      $.ajax({
        data: parametros,
        url: 'app/controlador/controladorCurso.php',
        type: 'post',
        beforeSend: function() {},
        complete: function() {},
        success: function(response) {
          console.log(response)
          if (response != 1) {
            alertify.error('error');
          }
        }
      });
    }

    function editarModulo(celda, id) {

      concepto = celda.textContent;
      var parametros = {
        "accion": "editar-modulo",
        "id": id,
        "concepto": concepto,
      };

      $.ajax({
        data: parametros,
        url: 'app/controlador/controladorCurso.php',
        type: 'post',
        beforeSend: function() {},
        complete: function() {},
        success: function(response) {
          console.log(response)
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
              Cursos
            </li>
          </ol>
        </div>
        <div class="row">
          <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
            <h3 class="text-secondary" style="padding-left: 0; margin-bottom: 0; margin-top: 0;">
              Cursos
            </h3>
          </div>
          <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
            <button type="button" class="float-end ms-2 btn dropdown-toggle btn-primary" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="fa-solid fa-screwdriver-wrench"></i></span>
            </button>
            <ul class="dropdown-menu">
              <li onclick="modalModulos()"><a class="dropdown-item pointer"><i class="fa-solid fa-list"></i> Modulos</a></li>
              <li onclick="modalTemas()"><a class="dropdown-item pointer"><i class="fa-solid fa-plus"></i> Nuevo Tema</a></li>
            </ul>
          </div>

        </div>
        <hr>
      </div>
      <div class="col-12">
        <div id="contenidoCursos" class="mt-3"></div>
      </div>
    </div>

    <!--Modal Curso Modulos-->
    <div class="modal fade" id="CursoModulos" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" id="DivCursoModulos">
        </div>
      </div>
    </div>
    <!--Modal Curso Temas-->
    <div class="modal fade" id="CursoTemas" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
      <div class="modal-dialog modal-lg">
        <div class="modal-content" id="DivCursoTemas">
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