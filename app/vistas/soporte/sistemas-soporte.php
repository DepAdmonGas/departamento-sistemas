<?php
require 'app/help.php';
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

  <!---------- LIBRERIAS DEL DATATABLE ---------->
  <link href="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.0.3/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/datatables.min.css" rel="stylesheet">
  <script src="https://cdn.ckeditor.com/ckeditor5/35.0.1/classic/ckeditor.js"></script>
  <script type="text/javascript" src="<?= RUTA_JS ?>alertify.js"></script>


  <script type="text/javascript">
    $(document).ready(function($) {
      $(".LoaderPage").fadeOut("slow");

      ContenidoSoporte();

    });


    function ContenidoSoporte() {
      $('#ContenidoSoporte').load('app/vistas/soporte/contenido-lista-soporte.php', function() {
        // Una vez que se carguen los datos en la tabla, inicializa DataTables
        $('#tabla_sistemas').DataTable({
          "language": {
            "url": "<?= RUTA_JS ?>/es-ES.json"
          },
          "order": [
            [8, "asc"]
          ], // Ordenar por la novena columna de forma ascendente (comienza desde 0)
          "lengthMenu": [25, 50, 75, 100], // Número de registros que se mostrarán
          "columnDefs": [{
              "orderable": false,
              "targets": [9, 10]
            }, // Deshabilitar ordenación en las columnas 1, 2 y 3 (comenzando desde 0)
            {
              "searchable": false,
              "targets": [9, 10]
            } // Deshabilitar filtrado en las columnas 1, 2 y 3 (comenzando desde 0)
          ]
        });
      });
    }



    function NuevoRegistro() {

      var parametros = {
        "Accion": "nuevo-folio",
        "Categoria": "Actividad"
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
            window.location.href = "nuevo-registro/" + response;
          } else {
            alertify.error('Error al crear');
          }

        }
      });
    }

    function EditarTicket(idticket, categoria) {
      window.location.href = "nuevo-registro/" + idticket;
    }

    function EliminarTicket(idticket) {

      let parametros = {
        "Accion": "cancelar-ticket",
        "idticket": idticket
      };

      alertify.confirm('',
        function() {

          $.ajax({
            data: parametros,
            url: 'app/modelo/controlador-sistemas.php',
            type: 'post',
            beforeSend: function() {},
            complete: function() {

            },
            success: function(response) {

              ContenidoSoporte();

            }
          });

        },
        function() {

        }).setHeader('Mensaje').set({
        transition: 'zoom',
        message: '¿Desea cancelar el registro?',
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      }).show();

    }

    function ModalComentarios(idticket,usuario) {
      $('#ModalComentario').modal('show');
      $('#DivContenidoComentario').load('app/vistas/soporte/modal-comentarios-ticket.php?idticket=' + idticket + '&usuario=' + usuario);
    }

    function GuardarComentario(idticket,usuario) {

      var Comentario = $('#Comentario').val();

      if (Comentario != "") {
        $('#Comentario').css('border', '');

        let parametros = {
          "Accion": "guardar-comentario",
          "idticket": idticket,
          "comentario": Comentario,
          "opcion": 1,
        };

        $.ajax({
          data: parametros,
          url: 'app/modelo/controlador-sistemas.php',
          type: 'post',
          beforeSend: function() {},
          complete: function() {

          },
          success: function(response) {

            ContenidoSoporte();
            $('#DivContenidoComentario').load('app/vistas/soporte/modal-comentarios-ticket.php?idticket=' + idticket);

          }
        });

      } else {
        $('#Comentario').css('border', '2px solid #A52525');
      }

    }

    function ModalDetalle(idticket) {
      $('#ModalDetalle').modal('show');
      $('#DivModalDetalle').load('app/vistas/soporte/modal-detalle-ticket.php?idticket=' + idticket);
    }

    function ModalBuscar() {
      $('#ModalComentario').modal('show');
      $('#DivContenidoComentario').load('app/vistas/soporte/modal-buscar-soporte.php');
    }

    function BuscarSoporte() {
      let EstadoSoporte = $('#EstadoSoporte').val();

      if (EstadoSoporte != "") {
        $('#EstadoSoporte').css('border', '');

        ContenidoBuscarSoporte(EstadoSoporte);

      } else {
        $('#EstadoSoporte').css('border', '2px solid #A52525');
      }

    }


    function ContenidoBuscarSoporte(estado) {
      $('#ModalComentario').modal('hide');

      $('#ContenidoSoporte').load('app/vistas/soporte/contenido-lista-buscar-soporte.php?estado=' + estado, function() {
        // Una vez que se carguen los datos en la tabla, inicializa DataTables
        $('#tabla_sistemas_busqueda').DataTable({
          "language": {
            "url": "<?= RUTA_JS ?>/es-ES.json" // Corregido "lenguage" a "language"
          },
          "order": [
            [0, "desc"]
          ], // Ordenar por la primera columna de forma descendente
          "lengthMenu": [25, 50, 75, 100], // Número de registros que se mostrarán
          "columnDefs": [{
              "orderable": false,
              "targets": [9, 10]
            }, // Deshabilitar ordenación en las columnas 1, 2 y 3 (comenzando desde 0)
            {
              "searchable": false,
              "targets": [9, 10]
            } // Deshabilitar filtrado en las columnas 1, 2 y 3 (comenzando desde 0)
          ]
        });
      });
    }

    function FinalizarSoporte(idticket) {

      let parametros = {
        "Accion": "finalizar-soporte",
        "idticket": idticket,
        "finalizar": 3
      };

      alertify.confirm('',
        function() {

          $.ajax({
            data: parametros,
            url: 'app/modelo/controlador-sistemas.php',
            type: 'post',
            beforeSend: function() {},
            complete: function() {

            },
            success: function(response) {
              $('#ModalDetalle').modal('hide');
              ContenidoSoporte();
            }
          });

        },
        function() {

        }).setHeader('Mensaje').set({
        transition: 'zoom',
        message: '¿Desea finalizar el soporte?',
        labels: {
          ok: 'Aceptar',
          cancel: 'Cancelar'
        }
      }).show();

    }
    window.addEventListener('pageshow', function(event) {
      if (event.persisted) {
        // Si la página está en la caché del navegador, recargarla
        window.location.reload();
        sizeWindow();
      }
    });
  </script>

</head>


<body>
  <div class="LoaderPage"></div>
  <div id="content">

    <!---------- NAV BAR (TOP) ---------->
    <?php require('app/vistas/navbar/navbar-perfil.php'); ?>

    <div class="contendAG">

      <div class="row">

        <div class="col-12">

          <div aria-label="breadcrumb" style="padding-left: 0; margin-bottom: 0;">
            <ol class="breadcrumb breadcrumb-caret">
              <li class="breadcrumb-item"><a onclick="history.back()" class="text-uppercase text-primary pointer"><i class="fa-solid fa-house"></i> Inicio</a></li>
              <li aria-current="page" class="breadcrumb-item active text-uppercase">Departamento de Sistemas</li>
            </ol>
          </div>


          <div class="row">

            <div class="col-12">
              <h3 class="text-secondary" style="padding-left: 0; margin-bottom: 0; margin-top: 0;">Departamento de Sistemas</h3>
            </div>

            <div class="col-12">
              <div class="row">

                <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 mt-3">
                  <span class="text-secondary">Aquí podrás crear tus solicitudes para el área de sistemas y tener el seguimiento de la solución a dichas alertas.
                  </span>
                </div>

                <div class="col-xl-4 col-lg-4 col-md-12 col-sm-12">

                  <div class="text-end">
                    <div class="dropdown d-inline ms-2">

                      <button type="button" class="btn dropdown-toggle btn-primary" type="button" id="dropdownMenuButton1" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa-solid fa-screwdriver-wrench"></i></span>
                      </button>

                      <ul class="dropdown-menu">
                        <li onclick="NuevoRegistro()"><a class="dropdown-item pointer"> <i class="fa-solid fa-calendar-check"></i> Crear Actividad</a></li>
                        <li onclick="ModalBuscar()"><a class="dropdown-item pointer"> <i class="fa-solid fa-magnifying-glass text-dark"></i> Buscar Registro</a></li>
                      </ul>

                    </div>
                  </div>

                </div>

              </div>
            </div>

          </div>
        </div>

      </div>

      <hr>

      <div class="col-12 mb-2">
        <div id="ContenidoSoporte"></div>
      </div>

    </div>

  </div>
  </div>

  <div class="modal" id="ModalComentario">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div id="DivContenidoComentario"></div>
      </div>
    </div>
  </div>

  <div class="modal fade bd-example-modal-lg" id="ModalDetalle" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
      <div class="modal-content">
        <div id="DivModalDetalle"></div>
      </div>
    </div>
  </div>


  <!---------- FUNCIONES - NAVBAR ---------->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/malihu-custom-scrollbar-plugin/3.1.5/jquery.mCustomScrollbar.concat.min.js"></script>
  <script src="<?= RUTA_JS ?>bootstrap.min.js"></script>

  <!---------- LIBRERIAS DEL DATATABLE ---------->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
  <script src="https://cdn.datatables.net/v/bs5/jszip-3.10.1/dt-2.0.3/b-3.0.1/b-colvis-3.0.1/b-html5-3.0.1/b-print-3.0.1/datatables.min.js"></script>

</body>

</html>