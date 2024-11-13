<?php
include_once "../../help.php";

$con = $ClassConexionBD->conectarBD();
$idticket = $_GET['idticket'];

$sql = "SELECT
ds_soporte.id_ticket, 
ds_soporte.id_personal,
ds_soporte.descripcion,
ds_soporte.prioridad,
ds_soporte.fecha_creacion,
ds_soporte.fecha_inicio,        
ds_soporte.fecha_termino,
ds_soporte.tiempo_solucion,
ds_soporte.fecha_termino_real,
ds_soporte.porcentaje,
ds_soporte.id_personal_soporte,
ds_soporte.estado,
tb_usuarios.id AS idUsuario,
tb_usuarios.nombre,
tb_estaciones.nombre AS nomestacion,
tb_puestos.tipo_puesto
FROM ds_soporte 
INNER JOIN tb_usuarios 
ON ds_soporte.id_personal = tb_usuarios.id
INNER JOIN tb_estaciones
ON tb_usuarios.id_gas = tb_estaciones.id
INNER JOIN tb_puestos
ON tb_usuarios.id_puesto = tb_puestos.id
WHERE ds_soporte.id_ticket = '" . $idticket . "'  ";
$result = mysqli_query($con, $sql);
$numero = mysqli_num_rows($result);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
  $id_ticket = $row['id_ticket'];
  $descripcion = $row['descripcion'];
  $prioridad = $row['prioridad'];
  $porcentaje = $row['porcentaje'];
  $fechainicio = $row['fecha_inicio'];
  $fechatermino = $row['fecha_termino'];
  $tiemposolucion = $row['tiempo_solucion'];
  $fechaterminoreal = $row['fecha_termino_real'];
  $Valorestado = $row['estado'];
  $nomestacion = $row['nomestacion'];
  $solicitante = $row['nombre'];
  $idPersonalSoporte = $row['id_personal_soporte'];
  $PersonalSoporte = $ClassContenido->Responsable($idPersonalSoporte);

  $explode = explode(' ', $row['fecha_creacion']);
  if ($explode[0] == '0000-00-00') {
    $fechaCreacion = 'S/I';
  } else {
    $fechaCreacion = FormatoFecha($explode[0]) . ', ' . date("g:i a", strtotime($explode[1]));
  }

  if ($prioridad == 'Baja') {
    $colorPrioridad = 'text-primary';
  } else if ($prioridad == 'Media') {
    $colorPrioridad = 'text-warning';
  } else if ($prioridad == 'Alta') {
    $colorPrioridad = 'text-danger';
  }

  $explode1 = explode(' ', $row['fecha_inicio']);
  if ($explode1[0] == '0000-00-00') {
    $fechaInicio = 'S/I';
  } else {
    $fechaInicio = FormatoFecha($explode1[0]);
  }

  $explode2 = explode(' ', $row['fecha_termino']);
  if ($explode2[0] == '0000-00-00') {
    $fechaTermino = 'S/I';
  } else {
    $fechaTermino = FormatoFecha($explode2[0]);
  }

  if ($row['estado'] == 0) {

    $estado = 'Creando';
  } else if ($row['estado'] == 1) {

    $estado = 'Pendiente';
  } else if ($row['estado'] == 2) {

    $estado = 'En proceso';
  } else if ($row['estado'] == 3) {

    $estado = 'Finalizado';
  } else if ($row['estado'] == 4) {

    $estado = 'Cancelado';
  }

  if ($nomestacion == 'Comodines') {
    $EstacionDepartamento = $row['tipo_puesto'];
  } else {
    $EstacionDepartamento = $nomestacion;
  }
}

$sqlActividad = "SELECT * FROM ds_soporte_actividades WHERE id_ticket = '" . $idticket . "' ";
$resultActividad = mysqli_query($con, $sqlActividad);
$numeroActividad = mysqli_num_rows($resultActividad);
?>
<div class="modal-header">
  <h5 class="modal-title">Asignar Personal</h5>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>
<div class="modal-body">

  <div class="row">
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-2">
      <h6 class="text-secondary"># Ticket</h6>
      <div>0<?= $id_ticket; ?></div>
    </div>
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-2">
      <h6 class="text-secondary">Prioridad</h6>
      <div class="<?= $colorPrioridad; ?>"><b><?= $prioridad; ?></b></div>
    </div>
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-2">
      <h6 class="text-secondary">Estación o Departamento</h6>
      <div><?= $EstacionDepartamento; ?></div>
    </div>
    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-2">
      <h6 class="text-secondary">Solicitante</h6>
      <div><?= $solicitante; ?></div>
    </div>
    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 mb-2">
      <h6 class="text-secondary">Fecha creación</h6>
      <div><?= $fechaCreacion; ?></div>
    </div>
    <div class="text-start col-12">
      <h6 class="mt-2 text-secondary">Descripción</h6>
      <div><?= $descripcion; ?></div>
    </div>
    <div class="col-xl-8 col-lg-8 col-md-12 col-sm-12 mb-2">
      <h6 class="text-secondary">Responsable</h6>
      <select class="form-select rounded-0" onchange="EditarResponsable(this,<?= $idticket; ?>,4)" id="Responsable">
        <option value="<?= $idPersonalSoporte; ?>"><?= $PersonalSoporte; ?></option>
        <?php

        $sql_resp = "SELECT id, nombre FROM tb_usuarios WHERE id_puesto = 25 AND estatus = 0";
        $result_resp = mysqli_query($con, $sql_resp);
        $numero_resp = mysqli_num_rows($result_resp);
        while ($row_resp = mysqli_fetch_array($result_resp, MYSQLI_ASSOC)) {

          echo '<option value="' . $row_resp['id'] . '">' . $row_resp['nombre'] . '</option>';
        }

        ?>
      </select>
    </div>
  </div>

</div>
<div class="modal-footer">
    <button type="button" class="btn btn-labeled2 btn-primary" onclick="FinalizarEdicion(<?= $idticket ?>)">
      <span class="btn-label2"><i class="fa fa-check"></i></span>Finalizar</button>
</div>