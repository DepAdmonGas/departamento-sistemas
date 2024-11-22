<?php
include_once "app/help.php";
/*
echo $GET_IdModulo;
echo $GET_IdTema;
*/
// Nombre del curso cuestionario

$sql = "SELECT titulo FROM tb_cursos_temas WHERE id = $GET_IdTema";
$result = mysqli_query($con, $sql);
$numero = mysqli_num_rows($result);

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
  $titulo_tema = $row['titulo'];
}

$sql = "SELECT * FROM tb_cursos_temas_preguntas WHERE id_tema = $GET_IdTema ";
$result = mysqli_query($con, $sql);
$numero = mysqli_num_rows($result);

while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
  $row['titulo'];
}

?>
<html lang="es">

<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Dirección de operaciones</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width initial-scale=1.0">
  <link rel="shortcut icon" href="<?= RUTA_IMG_ICONOS ?>/icono-web.png">
  <link rel="apple-touch-icon" href="<?= RUTA_IMG_ICONOS ?>/icono-web.png">
  <link rel="stylesheet" href="<?= RUTA_CSS ?>alertify.css">
  <link rel="stylesheet" href="<?= RUTA_CSS ?>themes/default.rtl.css">
  <link href="<?= RUTA_CSS ?>bootstrap.min.css" rel="stylesheet" />
  <link href="<?= RUTA_CSS ?>navbar-general.min.css" rel="stylesheet" />
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.0/umd/popper.min.js"></script>
  <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
  <script type="text/javascript" src="<?= RUTA_JS ?>alertify.js"></script>
  <link href="https://fonts.googleapis.com/css?family=Montserrat" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.7.0/animate.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/2.9.2/umd/popper.min.js"></script>

  <!--Selectize-->
  <link href="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/css/selectize.default.min.css" rel="stylesheet">
  <script src="https://cdn.jsdelivr.net/npm/selectize@0.12.6/dist/js/standalone/selectize.min.js"></script>

  <script type="text/javascript">
    $(document).ready(function($) {
      $(".LoaderPage").fadeOut("slow");
      contenidoPreguntas(<?= $GET_IdTema ?>)
    });

    function contenidoPreguntas(idTema) {
      $('#contenidoPreguntas').load('../app/vistas/cursos/contenido-preguntas.php?idTema=' + idTema)
    }

    function Regresar() {
      window.location.href = "../../cursos";
    }

    function modalPregunta(idTema) {
      $('#Pregunta').modal('show');
      $('#DivPregunta').load('../app/vistas/cursos/modal-pregunta.php?idTema=' + idTema);
    }

    function modalRespuesta(idTema, id_pregunta = 0) {
      $('#Respuesta').modal('show');
      $('#DivRespuesta').load('../app/vistas/cursos/modal-respuesta.php?idTema=' + idTema + '&idPregunta=' + id_pregunta);
    }

    function cargaRespuestas(idTema) {
      var idPregunta = $('#pregunta').val();
      modalRespuesta(idTema, idPregunta)
    }

    function agregarPregunta(idTema) {
      var pregunta = $('#nueva-pregunta').val();
      if (pregunta != "") {
        $('#nueva-pregunta').css('border', '');

        var parametros = {
          "accion": "agregar-pregunta",
          "titulo": pregunta,
          "id_tema":idTema
        };

        $.ajax({
          data: parametros,
          url: '../app/controlador/controladorCurso.php',
          type: 'post',
          beforeSend: function() {},
          complete: function() {},
          success: function(response) {
            if (response == 1) {
              modalPregunta(idTema)
              contenidoPreguntas(idTema)
            }
          }
        });

      } else {
        $('#nueva-pregunta').css('border', '2px solid #A52525');
      }
    }

    function agregarRespuesta(idTema, idPregunta) {

      var respuesta = $('#respuesta').val();

      if (respuesta != "") {
        $('#respuesta').css('border', '');

        var parametros = {
          "accion": "nueva-respuesta",
          "idTema": idPregunta,
          "respuesta": respuesta
        };
        $.ajax({
          data: parametros,
          url: '../app/controlador/controladorCurso.php',
          type: 'post',
          beforeSend: function() {},
          complete: function() {},
          success: function(response) {
            if (response != 0) {
              alertify.success('Respuesta agregado correctamente')
              modalRespuesta(idTema, idPregunta)
              contenidoPreguntas(idTema)
            } else {
              alertify.error('Hubo un error')
            }
          }
        });

      } else {
        $('#respuesta').css('border', '2px solid #A52525');
      }


    }

    function editarPregunta(celda, id) {
      concepto = celda.textContent;
      columna = "titulo";

      var parametros = {
        "accion": "editar-pregunta",
        "id": id,
        "concepto": concepto,
        "columna": columna
      };

      $.ajax({
        data: parametros,
        url: '../app/controlador/controladorCurso.php',
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

    function GuardarRespuesta(id, id_pregunta) {

      var parametros = {
        "accion": "editar-pregunta-respuesta",
        "id": id,
        "id_pregunta": id_pregunta
      };

      $.ajax({
        data: parametros,
        url: '../app/controlador/controladorCurso.php',
        type: 'post',
        beforeSend: function() {},
        complete: function() {},
        success: function(response) {
          if (response == 1) {
            alertify.success('Respuesta asignado correctamente');
          }
        }
      });

    }

    function habilitarEdicion(celda) {
      // Selecciona solo el <b> dentro del div donde se hizo doble clic
      var divEditable = celda.querySelector('b');

      if (divEditable) {
        // Verificar si el contenido es editable
        if (celda.contentEditable === "true") {
          celda.contentEditable = "false"; // Deshabilitar la edición si ya estaba habilitada
          celda.style.cursor = "default"; // Restaurar el cursor
        } else {
          celda.contentEditable = "true"; // Habilitar la edición
          celda.style.cursor = "text"; // Cambiar el cursor a escritura
          celda.focus(); // Poner el foco en el div para que el usuario pueda empezar a escribir
        }
      }
    }
  </script>

</head>

<body>
  <div class="LoaderPage"></div>


  <!---------- DIV - CONTENIDO ---------->
  <div id="content">
    <!---------- NAV BAR - PRINCIPAL (TOP) ---------->
    <?php require('app/vistas/navbar/navbar-perfil.php'); ?>
    <!---------- CONTENIDO PAGINA WEB---------->
    <div class="contendAG container">
      <div class="row">
        <div id="contenidoCuestionario" class="col-12 mb-3">
          <div class="cardAG">
            <div class="border-0 p-3">
              <div class="row">
                <div class="col-12">
                  <div aria-label="breadcrumb" style="padding-left: 0; margin-bottom: 0;">
                    <ol class="breadcrumb breadcrumb-caret">
                      <li class="breadcrumb-item"><a onclick="history.back()"
                          class="text-uppercase text-primary pointer"><i class="fa-solid fa-chevron-left"></i>
                          Cursos</a></li>
                      <li aria-current="page" class="breadcrumb-item active">FORMULARIO (<?= $titulo_tema ?>)</li>
                    </ol>
                  </div>

                  <div class="row">
                    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12">
                      <h3 class="text-secondary" style="padding-left: 0; margin-bottom: 0; margin-top: 0;">Formulario (<?= $titulo_tema ?>)</h3>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12 mt-2">
                      <button type="button" class="float-end ms-2 btn dropdown-toggle btn-primary" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-screwdriver-wrench"></i></span>
                      </button>
                      <ul class="dropdown-menu">
                        <li onclick="modalRespuesta(<?= $GET_IdTema ?>)"><a class="dropdown-item pointer"><i class="fa-solid fa-list"></i> Respuesta</a></li>
                        <li onclick="modalPregunta(<?= $GET_IdTema ?>)"><a class="dropdown-item pointer"><i class="fa-solid fa-plus"></i> Pregunta</a></li>
                      </ul>
                    </div>
                  </div>

                  <hr>
                </div>
                <div class="col-12">
                  <div id="contenidoPreguntas"></div>
                </div>
              </div>

            </div>
          </div>
        </div>

      </div>
    </div>

  </div>


  <div class="modal" id="Respuesta">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div id="DivRespuesta"></div>
      </div>
    </div>
  </div>

  <div class="modal" id="Pregunta">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div id="DivPregunta"></div>
      </div>
    </div>
  </div>

  <!---------- FUNCIONES - NAVBAR ---------->
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

  <script src="<?= RUTA_JS ?>bootstrap.min.js"></script>

</body>

</html>