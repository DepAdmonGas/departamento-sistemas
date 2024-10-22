<?php
require '../../help.php';
$con = $ClassConexionBD->conectarBD();
$idUsuario = $_GET['idUsuario'];
$result2  = "";
$divContador = "";
$alertaRegistro  = '';
$diferencia = 0;
$sql = "SELECT fecha_creacion, token, chat_id, estatus FROM op_token_telegram WHERE id_usuario = '" . $idUsuario . "' ORDER BY id DESC LIMIT 1";
$result = mysqli_query($con, $sql);
$numero_lista = mysqli_num_rows($result);

if($numero_lista > 0){

while ($row_lista = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
$fecha_inicio = $row_lista['fecha_creacion'];
$token = $row_lista['token'];
$chat_id = $row_lista['chat_id'];
$estatus = $row_lista['estatus'];
}

// Convertir la fecha de inicio a segundos
$fecha_creacion = strtotime($fecha_inicio);
$fecha_actual = time();

// Calcular la diferencia en segundos (120 segundos = 2 minutos)
$diferencia = 120 - ($fecha_actual - $fecha_creacion);

// Asegurarse de que la diferencia no sea negativa
if ($diferencia < 0) {
$diferencia = 0;
}


if($chat_id == 0 OR $estatus == 0){
$digits = str_split($token);
$result2 .=  '<div class="mt-2 mb-2 text-secondary center"><b>3. CODIGO DE VERIFICACIÓN:</b> <br> El siguiente código de verificación debe ser ingresado en el chat de recepción de tokens.</div>';
$result2 .=  '<ul class="code-number text-center">';
// Iterar sobre cada dígito y hacer lo que necesites con ellos
foreach ($digits as $digit) {
$result2 .= "<li class='fw-bold'>" . $digit . "</li>"; // Imprimir cada dígito en una nueva línea
}
$result2 .= '</ul>';

$result2 .=  '<div class="mt-2 text-danger center"><b>Nota:</b> Ten en cuenta que este código tiene un límite de tiempo de 2 minutos para ser utilizado, pero puedes generar un nuevo codigo en caso de que el anterior expire.</div>';
$nameBoton = 'Generar un nuevo token';
$dato = 0;

$divContador = '<div class="col-12">
<div id="contador" class="text-center"></div>
</div>';

}else{

$result2 .=  '<div class="mt-2 mb-2 text-secondary center"><b>3. REVOCAR ACCESO:</b> <br>Si deseas quitar el acceso al dispositivo móvil registrado y habilitar la recepción en otro dispositivo, deberás hacer clic en el botón "Revocar Autorización" y seguir nuevamente los pasos indicados en el manual.</div>';
$alertaRegistro  = '<div class="alert alert-danger text-center mb-0 mt-3" role="alert">¡Se ha completado la verificación!</div>';
$nameBoton = 'Revocar Autorización';
$dato = 1;

}



}else{
$nameBoton = 'Generar un nuevo token';
$result2 .=  '<div class="mt-2 mb-2 text-secondary center"><b>3. CODIGO DE VERIFICACIÓN:</b> <br> Para configurar la recepción de tokens, deberás generar tu token por primera vez. Haz clic en el botón "Generar un nuevo token" para iniciar el proceso.</div>';
$dato = 0;
}
?>

<script>
// Inicializar la variable de tiempo en segundos desde PHP
var segundos = <?php echo $diferencia; ?>;

// Función para formatear los minutos y segundos
function formatearTiempo(segundos) {
var minutos = Math.floor(segundos / 60);
var segundosRestantes = segundos % 60;

// Añadir un cero delante de los segundos si son menores a 10
if (segundosRestantes < 10) {
segundosRestantes = '0' + segundosRestantes;
}
return minutos + ":" + segundosRestantes;
}

// Función para actualizar el contador en tiempo real
function actualizarContador() {
if (segundos > 0) {
document.getElementById('contador').innerHTML = formatearTiempo(segundos);
segundos--;
setTimeout(actualizarContador, 1000);
}else if (segundos == 0){

} else {
document.getElementById('contador').innerHTML = "El tiempo ha expirado.";
}
}

// Iniciar el contador automáticamente
actualizarContador();
</script>

<style>
.code-number {
padding: 0;
margin: 0 0 20px;
list-style: none;
}

.code-number li {
color: #000;
background: #F2F2F2;
font-size: 18px;
font-weight: 400;
line-height: 40px;
width: 40px;
height: 40px;
margin: 0 10px;
border-radius: 50%;
display: inline-block;
}

/* Estilos del reloj digital */
#contador {
font-size: 35px;
color: #000; /* Color verde típico de relojes digitales */
}

@media only screen and (max-width: 476px) {
.code-number li {
font-size: 16px;
line-height: 40px;
height: 40px;
width: 40px;
margin: 0 7px;
}
}
</style>

<div class="modal-header">
<h5 class="modal-title">Telegram Token</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


<div class="modal-body">
 
<div class="row">

<div class="col-12 mb-3">
<div class="mb-3 text-secondary"><b>1. DESCARGA DE MANUAL:</b> <br> En el siguiente botón, podrás descargar el manual que contiene los pasos detallados para llevar a cabo la configuración de recepción de tokens mediante la app de Telegram.</div>

<div class="d-flex justify-content-center">
<a href="<?=RUTA_ARCHIVOS?>manuales-portal/manualToken.pdf" download>
<button type="button" class="btn btn-labeled2 btn-primary float-center">
<span class="btn-label2">
<i class="fa-solid fa-file-arrow-down"></i>
</span>
Descarga Manual de Configuración de Tokens
</button>
</a>
</div>

</div>


<div class="col-12">
<div class="text-secondary"><b>2. ESCANEAR CODIGO QR:</b> <br> AL escanear el siguiente código QR, te direccionara al chat de recepción de tokens.</div>

<div class="d-flex justify-content-center"> 
<img src="<?= RUTA_IMG . "logo/telegramQR.png" ?>" class="img-fluid w-50" />
</div>
</div>


<div class="col-12">
<?=$result2?>
</div>


<div class="col-12">
<?=$alertaRegistro?>
</div>


<?=$divContador?>

</div>
</div>

<div class="modal-footer"><button type="button" class="btn btn-labeled2 btn-success" onclick="actualizaTokenTelegram(<?=$idUsuario?>,<?=$dato?>)">
<span class="btn-label2"><i class="fa fa-check"></i></span><?=$nameBoton?></button></div>
 

 