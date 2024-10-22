<?php 
include_once "app/help.php";

$InformacionTicket = $ClassContenido->soporteContenido($GET_IdRegistro);

if($InformacionTicket['estado'] != 0){
header("location:../soporte");
}

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
  <link href="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.snow.css" rel="stylesheet" />
  
  <script type="text/javascript">
  
  $(document).ready(function($){
  $(".LoaderPage").fadeOut("slow");

    ContenidoActividad(<?=$GET_IdRegistro;?>);
    ContenidoEvidencia(<?=$GET_IdRegistro;?>);

  });
 
  function ContenidoActividad(idRegistro){
    $('#ContenidoActividad').load('../app/vistas/soporte/contenido-lista-actividades.php?idRegistro=' + idRegistro);
  }
  function ContenidoEvidencia(idRegistro){
    $('#ContenidoEvidencia').load('../app/vistas/soporte/contenido-lista-evidencia.php?idRegistro=' + idRegistro);
  }

  function EditarDescripcion(val,idRegistro){
    let Desceipcion = val.value;

    let parametros = {
    "Accion" : "editar-descripcion",
    "idRegistro" : idRegistro,
    "Dato" : Desceipcion
    };

    $.ajax({
    data:  parametros,
    url:   '../app/modelo/controlador-sistemas.php',
    type:  'post',
    beforeSend: function() {
    },
    complete: function(){

    },
    success:  function (response) {

    }
    });

  }

  function EditarPrioridad(idRegistro){
    let Prioridad = $('#Prioridad').val();

    let parametros = {
    "Accion" : "editar-prioridad",
    "idRegistro" : idRegistro,
    "Dato" : Prioridad
    };

    $.ajax({
    data:  parametros,
    url:   '../app/modelo/controlador-sistemas.php',
    type:  'post',
    beforeSend: function() {
    },
    complete: function(){

    },
    success:  function (response) {

    }
    });

  }

  function ModalActividad(idRegistro){
    $('#Modal').modal('show');
    $('#ModalContenido').load('../app/vistas/soporte/modal-formulario-actividades.php?idRegistro=' + idRegistro);
  }
  function ModalEvidencia(idRegistro){
    $('#Modal').modal('show');
    $('#ModalContenido').load('../app/vistas/soporte/modal-formulario-evidencias.php?idRegistro=' + idRegistro);
  }

  function ActividadAgregar(idRegistro){

    var data = new FormData();
    let ActividadDescripcion = quill.root.innerHTML;
    Archivo = document.getElementById("ActividadArchivo");
    Archivo_file = Archivo.files[0];
    Archivo_filePath = Archivo.value;

    if(ActividadDescripcion != ""){
    $('#ActividadDescripcion').css('border',''); 
    data.append('Accion', 'agregar-actividad');
    data.append('idRegistro', idRegistro);
    data.append('ActividadDescripcion', ActividadDescripcion);
    data.append('Archivo_file', Archivo_file);
 
    $(".LoaderPage").show();
   
        let url = '../app/modelo/controlador-sistemas.php';
        $.ajax({
        url: url,
        type: 'POST',
        contentType: false,
        data: data,
        processData: false,
        cache: false
        }).done(function(data){
          console.log(data)
       if(data == 1){

            $(".LoaderPage").hide();
            $('#Modal').modal('hide');
            ContenidoActividad(idRegistro);

        }else{
        $(".LoaderPage").hide();
        alertify.error('Error al crear la actividad'); 
        }
        
        }); 

    }else{
    $('#ActividadDescripcion').css('border','2px solid #A52525');   
    }

  }

  function EvidenciaAgregar(idRegistro){

  var data = new FormData();
  let EvidenciaDescripcion = $('#EvidenciaDescripcion').val();
  Archivo = document.getElementById("EvidenciaArchivo");
  Archivo_file = Archivo.files[0];
  Archivo_filePath = Archivo.value;

  if(Archivo_filePath != ""){
  $('#EvidenciaArchivo').css('border',''); 
  data.append('Accion', 'agregar-evidencia');
  data.append('idRegistro', idRegistro);
  data.append('EvidenciaDescripcion', EvidenciaDescripcion);
  data.append('Archivo_file', Archivo_file);

$(".LoaderPage").show();

    let url = '../app/modelo/controlador-sistemas.php';
    $.ajax({
    url: url,
    type: 'POST', 
    contentType: false,
    data: data,
    processData: false,
    cache: false
    }).done(function(data){
    if(data == 1){
        
        $(".LoaderPage").hide();
        $('#Modal').modal('hide');
        ContenidoEvidencia(idRegistro);

    }else{
    $(".LoaderPage").hide();
    alertify.error('Error al crear la evidencia'); 
    }
    
    }); 

}else{
$('#EvidenciaArchivo').css('border','2px solid #A52525');   
}

}

function EliminarActividad(idRegistro,idActividad){

let parametros = {
"Accion" : "eliminar-actividad",
"idRegistro" : idRegistro,
"idActividad" : idActividad
};

$.ajax({
data:  parametros,
url:   '../app/modelo/controlador-sistemas.php',
type:  'post',
beforeSend: function() {
},
complete: function(){

},
success:  function (response) {

  if(response == 1){

  ContenidoActividad(idRegistro);

  }else{
  $(".LoaderPage").hide();
  alertify.error('Error al eliminar'); 
  }

}
});
}

function EliminarEvidencia(idRegistro,idActividad){
    let parametros = {
  "Accion" : "eliminar-evidencia",
  "idRegistro" : idRegistro,
  "idActividad" : idActividad
  };

  $.ajax({
  data:  parametros,
  url:   '../app/modelo/controlador-sistemas.php',
  type:  'post',
  beforeSend: function() {
  },
  complete: function(){

  },
  success:  function (response) {

    if(response == 1){

      ContenidoEvidencia(idRegistro);

    }else{
    $(".LoaderPage").hide();
    alertify.error('Error al eliminar'); 
    }

  }
  });
}

function Finalizar(idRegistro,idUsuario){

    let parametros = {
    "Accion" : "finalizar-registro",
    "idRegistro" : idRegistro,
    "usuario":idUsuario,
    "ticket":<?=$GET_IdRegistro?>
    };

    alertify.confirm('',
	function(){

    $.ajax({
    data:  parametros,
    url:   '../app/modelo/controlador-sistemas.php',
    type:  'post',
    beforeSend: function() {
    },
    complete: function(){

    },
    success:  function (response) {

      window.history.back();

    }
    });

	},
	function(){

	}).setHeader('Mensaje').set({transition:'zoom',message: '¿Desea finalizar el registro?',labels:{ok:'Aceptar', cancel: 'Cancelar'}}).show();
}

  </script>
  </head>
   
  <body>
  <div class="LoaderPage"></div>
  <div id="content">

  <!---------- NAV BAR (TOP) ---------->  
  <?php require('app/vistas/navbar/navbar-perfil.php');?>

  <div class="contendAG">
  <div class="container bg-white p-3">

    <h4 class="text-primary">Crear Registro</h4>
    <h5 class="fw-bold"># Ticket: 0<?=$GET_IdRegistro;?></h5>
    <div class="m-2">

    </div>
    <h6 class="mt-2 fw-bold text-secondary">Breve Descripción del Ticket:</h6>
    <textarea class="form-control rounded-0" id="Descripcion" onkeyup="EditarDescripcion(this,<?=$GET_IdRegistro;?>)"><?=$InformacionTicket['descripcion'];?></textarea>

    <h6 class="mt-2 fw-bold text-secondary">Prioridad:</h6>
    <select class="form-select rounded-0" id="Prioridad" onchange="EditarPrioridad(<?=$GET_IdRegistro;?>)">
    <option value="<?=$InformacionTicket['prioridad'];?>"><?=$InformacionTicket['prioridad'];?></option>
    <option value="Baja">Baja</option>
    <option value="Media">Media</option>
    <option value="Alta">Alta</option>
    </select>


    <h6 class="mt-2 fw-bold text-secondary">Detalle de la Actividad:</h6>
    <div class="mb-2" style="height: 300px;font-size: 1em;" id="editor"></div>
    <h6 class="fw-bold text-secondary">Agrege evidencias en el formato de tu elección, el cual puede ser: PDF, Excel, Word, JPG o PNG.</h6>
    <input class="form-control rounded-0" type="file" id="ActividadArchivo">
    <hr>

    <div class="row">
    <div class="col-8">

    </div>

    <div class="col-4">
    <button type="button" class="btn btn-labeled2 btn-primary float-end mb-3" onclick="ActividadAgregar(<?=$GET_IdRegistro;?>)">
    <span class="btn-label2"><i class="fa-solid fa-plus"></i></span>Agregar</button>
    </div>
    </div>

    <div class="mt-2" id="ContenidoActividad"></div>

    <hr>
  
    <!----- BOTON DE FINALIZAR ----->
    <div class="row">
    <div class="col-12">
    <button type="button" class="btn btn-labeled2 btn-success float-end" onclick="Finalizar(<?=$GET_IdRegistro?>,<?=$Session_IDUsuarioBD?>)">
    <span class="btn-label2"><i class="fa-solid fa-check"></i></span>Finalizar registro</button>
    </div>

    </div>


  </div>

  </div>

  </div>
  </div>


    <div class="modal fade bd-example-modal-lg" id="Modal" tabindex="-1" role="dialog" aria-labelledby="myExtraLargeModalLabel" aria-hidden="true">  
    <div class="modal-dialog modal-lg" role="document">
    <div class="modal-content">
    <div id="ModalContenido"></div>
    </div>
    </div>
    </div>

  <script src="<?=RUTA_JS ?>bootstrap.min.js"></script>
  <script src="https://cdn.jsdelivr.net/npm/quill@2.0.2/dist/quill.js"></script>
  <script>
  const quill = new Quill('#editor', {
    modules: { toolbar: true },
    theme: 'snow'
  });
 </script>
  </body>
  </html> 