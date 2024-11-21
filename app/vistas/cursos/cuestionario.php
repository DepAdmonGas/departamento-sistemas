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
  <script type="text/javascript" src="<?php echo RUTA_JS ?>signature_pad.js"></script>

  <script type="text/javascript">
    $(document).ready(function($) {
      $(".LoaderPage").fadeOut("slow");
    });

    function Regresar() {
      window.location.href = "../../cursos";
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
        <div class="col-12 mb-3">
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
                    <div class="col-8">
                      <h3 class="text-secondary" style="padding-left: 0; margin-bottom: 0; margin-top: 0;">Formulario (<?= $titulo_tema ?>)</h3>
                    </div>
                    <div class="col-4">
                      <button type="button" class="btn btn-labeled2 btn-primary float-end ms-2"
                        onclick="modalAgregarPersonal()">
                        <span class="btn-label2"><i class="fa-solid fa-plus"></i></span>Agregar</button>
                    </div>
                  </div>

                  <hr>
                </div>

                <?php
                echo '<div class="row">';

                $sqlPregunta = "SELECT * FROM tb_cursos_temas_preguntas WHERE id_tema = '" . $GET_IdTema . "' ";
                $resultPregunta = mysqli_query($con, $sqlPregunta);
                $numeroPregunta  = mysqli_num_rows($resultPregunta);
                while ($rowPregunta = mysqli_fetch_array($resultPregunta, MYSQLI_ASSOC)) {
                  echo '<div class="col-xl-6 col-lg-6 col-md-6 col-sm-12"><b>' . $rowPregunta['num_pregunta'] . '.- ' . $rowPregunta['titulo'] . '</b>';
                  echo '<div class="p-3"><ol style="list-style-type:lower-alpha">';
                  $sqlRespuesta = "SELECT * FROM tb_cursos_temas_preguntas_respuestas WHERE id_pregunta = '" . $rowPregunta['id'] . "' ";
                  $resultRespuesta = mysqli_query($con, $sqlRespuesta);
                  $numeroRespuesta  = mysqli_num_rows($resultRespuesta);
                  while ($rowRespuesta = mysqli_fetch_array($resultRespuesta, MYSQLI_ASSOC)) {
                    echo '<li> <input type="radio" name="preg' . $rowPregunta['num_pregunta'] . '" onclick="GuardarRespuesta(' . $GET_IdTema . ',' . $rowPregunta['num_pregunta'] . ',' . $rowRespuesta['valor'] . ')" > ' . $rowRespuesta['titulo'] . '</li>';
                  }
                  echo '</ol></div>';
                  echo '</div>';
                }

                echo '</div>';
                ?>


              </div>

            </div>
          </div>
        </div>

      </div>
    </div>

  </div>


  <div class="modal" id="Modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div id="ContenidoModal"></div>
      </div>
    </div>
  </div>

  <!---------- FUNCIONES - NAVBAR ---------->
  <script
    src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>

  <script src="<?= RUTA_JS ?>bootstrap.min.js"></script>

</body>

</html>