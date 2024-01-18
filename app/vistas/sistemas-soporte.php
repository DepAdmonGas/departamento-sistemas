<?php 
include_once "app/help.php";
   
?>
  
<!DOCTYPE html>
<html lang="es">
  
  <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
  <title>Portal AdmonGas</title>
  <meta name="description" content="">
  <meta name="viewport" content="width=device-width initial-scale=1.0">
  <link rel="shortcut icon" href="<?=RUTA_IMG_ICONOS?>/icono-web.ico">
  <link rel="apple-touch-icon" href="<?=RUTA_IMG_ICONOS?>/icono-web.ico">
  <link rel="stylesheet" href="<?=RUTA_CSS ?>alertify.css">
  <link rel="stylesheet" href="<?=RUTA_CSS ?>themes/default.rtl.css">
  <link href="<?=RUTA_CSS;?>bootstrap.min.css" rel="stylesheet" />
  <link href="<?=RUTA_CSS;?>navbar-general.min.css" rel="stylesheet" />
 
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css">
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.3/jquery.min.js"></script>
  <script type="text/javascript" src="<?=RUTA_JS?>alertify.js"></script>
  
  <script type="text/javascript">
  
  $(document).ready(function($){
  $(".LoaderPage").fadeOut("slow");

  ContenidoSoporte(1);

  });

  function ContenidoSoporte(page){
  $('#ContenidoSoporte').load('app/vistas/contenido-lista-soporte.php?page=' + page);
  }

  function NuevoRegistro(){

    var parametros = {
    "Accion" : "nuevo-folio"
    };

    $.ajax({
    data:  parametros,
    url:   'app/modelo/controlador-sistemas.php',
    type:  'post',
    beforeSend: function() {
    },
    complete: function(){

    },
    success:  function (response) {
    
    if (response != 0) {
    window.location.href = "nuevo-registro/" + response;   
    }else{
    alertify.error('Error al crear');  
    }

    }
    });
   }

  function EditarTicket(idticket){
  window.location.href = "nuevo-registro/" + idticket; 
  }

  function EliminarTicket(idticket){

    let parametros = {
    "Accion" : "cancelar-ticket",
    "idticket" : idticket
    };

    alertify.confirm('',
	function(){

    $.ajax({
    data:  parametros,
    url:   'app/modelo/controlador-sistemas.php',
    type:  'post',
    beforeSend: function() {
    },
    complete: function(){

    },
    success:  function (response) {

      ContenidoSoporte(1);

    }
    });

	},
	function(){

	}).setHeader('Mensaje').set({transition:'zoom',message: '¿Desea cancelar el registro?',labels:{ok:'Aceptar', cancel: 'Cancelar'}}).show();

  }

  function ModalComentarios(idticket){
    $('#ModalComentario').modal('show');  
    $('#DivContenidoComentario').load('app/vistas/modal-comentarios-ticket.php?idticket=' + idticket);
  }

  function GuardarComentario(idticket){

    var Comentario = $('#Comentario').val();

    if(Comentario != ""){
    $('#Comentario').css('border',''); 

    let parametros = {
    "Accion" : "guardar-comentario",
    "idticket" : idticket,
    "comentario" : Comentario,
    "opcion" : 1,
    };

    $.ajax({
    data:  parametros,
    url:   'app/modelo/controlador-sistemas.php',
    type:  'post',
    beforeSend: function() {
    },
    complete: function(){

    },
    success:  function (response) {

      ContenidoSoporte(1);
      $('#DivContenidoComentario').load('app/vistas/modal-comentarios-ticket.php?idticket=' + idticket);

    }
    });

  }else{
    $('#Comentario').css('border','2px solid #A52525'); 
    }

  }

  function ModalDetalle(idticket){
    $('#ModalDetalle').modal('show');  
    $('#DivModalDetalle').load('app/vistas/modal-detalle-ticket.php?idticket=' + idticket);
  }

  function ModalBuscar(){
    $('#ModalComentario').modal('show');  
    $('#DivContenidoComentario').load('app/vistas/modal-buscar-soporte.php');
  }

  function BuscarSoporte(){
    let EstadoSoporte = $('#EstadoSoporte').val();
    if(EstadoSoporte != ""){
    $('#EstadoSoporte').css('border',''); 

    ContenidoBuscarSoporte(1,EstadoSoporte);

    }else{
    $('#EstadoSoporte').css('border','2px solid #A52525'); 
    }

  }

  function ContenidoBuscarSoporte(page,estado){
    $('#ContenidoSoporte').load('app/vistas/contenido-lista-buscar-soporte.php?page=' + page + '&estado=' + estado);
    $('#ModalComentario').modal('hide'); 
  }

  </script>
  <style>
    .grayscale {
    filter: opacity(50%); 
  }
  .bg-sistemas{
    background : #D8EFDF;
  }
  .bg-personal{
    background : #D8E3EF;
  }
  </style>
  </head>
  
 
  <body>
  <div class="LoaderPage"></div>
  <div id="content">

  <!---------- NAV BAR (TOP) ---------->  
  <?php require('app/vistas/navbar/navbar-perfil.php');?>

  <div class="contendAG">     

  <div class="float-end">
    <img class="me-2" src="<?=RUTA_IMG_ICONOS?>buscar.png" onclick="ModalBuscar()">
    <img class="me-2" src="<?=RUTA_IMG_ICONOS?>agregar.png" onclick="NuevoRegistro()">
  </div>
  
  <h3>Departamento de Sistemas</h3>
  Aquí podrás crear tus solicitudes de pendientes para el área de sistemas y tener el seguimiento de la solución a dichas alertas.

  <div id="ContenidoSoporte"></div>
 
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

    <div class="modal fade bd-example-modal-lg" id="ModalDetalle" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">  
    <div class="modal-dialog modal-lg" role="document" style="margin-top: 83px;">
    <div class="modal-content">
    <div id="DivModalDetalle"></div>
    </div>
    </div>
    </div>

  <script src="<?=RUTA_JS ?>bootstrap.min.js"></script>

  </body>
  </html> 