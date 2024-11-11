<?php
include_once "../../help.php";
$con = $ClassConexionBD->conectarBD();
function actualizaFechaInicioActividad($fechaInicio,$idActividad,$con){
  $sql = "UPDATE ds_soporte_actividades SET fecha_inicio = '$fechaInicio' WHERE id = $idActividad";
  mysqli_query($con, $sql);
}
$idticket = $_GET['idticket'];
$fechaInicio = $_GET['fecha'];
$sql = "SELECT descripcion,prioridad,fecha_creacion,categoria
FROM ds_soporte
WHERE id_ticket = '" . $idticket . "'  ";
$result = mysqli_query($con, $sql);
$numero = mysqli_num_rows($result);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
  $descripcion = $row['descripcion'];
  $prioridad = $row['prioridad'];
  $categoria = $row['categoria'];

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

}

$sqlActividad = "SELECT * FROM ds_soporte_actividades WHERE id_ticket = '" . $idticket . "' ";
$resultActividad = mysqli_query($con, $sqlActividad);
$numeroActividad = mysqli_num_rows($resultActividad);
/*
$sqlEvidencia = "SELECT * FROM ds_soporte_evidencia WHERE id_ticket = '" . $idticket . "' ";
$resultEvidencia = mysqli_query($con, $sqlEvidencia);
$numeroEvidencia = mysqli_num_rows($resultEvidencia);
*/
?>
<div class="modal-header">
  <h5 class="modal-title">Detalle</h5>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>


<div class="modal-body">
  <div class="text-center d-flex justify-content-around ">

    <div>
      <h6 class="text-secondary"># Ticket</h6>
      0<?= $idticket; ?>
    </div>


    <div>
      <h6 class="text-secondary">Fecha creación</h6>
      <?= $fechaCreacion; ?>
    </div>

    <div class="<?= $colorPrioridad; ?>">
      <h6 class="text-secondary">Prioridad</h6>
      <b><?= $prioridad; ?></b>
    </div>
  </div>


  <div class="text-start d-flex ms-5">
    <div>
      <h6 class="mt-2 text-secondary">Descripción</h6>
      <?= $descripcion; ?>
    </div>
  </div>


  <hr>
  <?php if ($categoria == "Actividad"): ?>
    <h6 class="mt-2 text-secondary">Actividad:</h6>


    <div class="table-responsive">
      <table class="custom-table" style="font-size: 12px;" width="100%">

        <thead class="navbar-bg">
          <tr>
            <th class="align-middle">#</th>
            <th class="align-middle">Descripción de la actividad</th>
            <th class="align-middle">Fecha inicio</th>
            <th class="align-middle">Fecha termino</th>
            <th class="align-middle text-center" width="24px"><img src="<?= RUTA_IMG_ICONOS; ?>descargar.png"></th>
          </tr>
        </thead>

        <tbody class="bg-light">
          <?php

          if ($numeroActividad > 0) {
            $numActividad = 1;

            while ($rowActividad = mysqli_fetch_array($resultActividad, MYSQLI_ASSOC)) {
              $idActividad = $rowActividad['id'];
              $descripcionActividad = $rowActividad['descripcion'];
              if ($rowActividad['fecha_inicio'] == '0000-00-00') {
                actualizaFechaInicioActividad($fechaInicio,$idActividad,$con);
                $AtividadFechaInicio = $fechaInicio;
              } else {
                $AtividadFechaInicio = $rowActividad['fecha_inicio'];
              }

              if ($rowActividad['fecha_termino'] == '0000-00-00') {
                $AtividadFechaTermino = '';
              } else {
                $AtividadFechaTermino = $rowActividad['fecha_termino'];
              }

              if ($rowActividad['archivo'] == "") {
                $Archivo = '<a><img src="' . RUTA_IMG_ICONOS . 'eliminar.png" ></a>';
              } else {
                $Archivo = '<a href="' . RUTA_ARCHIVOS . $rowActividad['archivo'] . '" download><img src="' . RUTA_IMG_ICONOS . 'descargar.png" ></a>';
              }

              echo '<tr>';
              echo '<th class="align-middle">' . $numActividad . '</th>';
              echo '<td class="align-middle">' . $descripcionActividad . '</td>';
              echo '<td class="p-0"><input type="date" class="border-0 form-control" value="' . $AtividadFechaInicio . '" onchange="EditarActividad(this,' . $idActividad . ',1)" ></td>';
              echo '<td class="p-0"><input type="date" class="border-0 form-control" value="' . $AtividadFechaTermino . '" onchange="EditarActividad(this,' . $idActividad . ',2)" ></td>';
              echo '<td class="align-middle">' . $Archivo . '</td>';
              echo '</tr>';

              $numActividad++;
            }
          } else {
            echo "<tr style='background-color: #f8f9fa'><th colspan='9' class='text-center text-secondary'><small>No se encontró información para mostrar </small></th></tr>";
          }

          ?>
        </tbody>
      </table>
    </div>
  <?php else: ?>
    <h6 class="text-secondary mt-2">Evidencia:</h6>

    <div class="table-responsive">
      <table class="custom-table" style="font-size: 12px;" width="100%">
        <thead class="navbar-bg">
          <tr>
            <th class="align-middle">#</th>
            <th class="align-middle">Descripción de la evidencia</th>
            <th class="align-middle text-center" width="24px"><img src="<?= RUTA_IMG_ICONOS; ?>descargar.png"></th>
          </tr>
        </thead>

        <tbody class="bg-light">
          <?php

          if ($numeroEvidencia > 0) {

            $numEvidencia = 1;

            while ($rowEvidencia = mysqli_fetch_array($resultEvidencia, MYSQLI_ASSOC)) {
              $descripcionEvidencia = $rowEvidencia['descripcion'];

              echo '<tr>';
              echo '<td class="align-middle">' . $numEvidencia . '</td>';
              echo '<td class="align-middle">' . $descripcionEvidencia . '</td>';
              echo '<td class="align-middle"><a href="' . RUTA_ARCHIVOS . $rowEvidencia['evidencia'] . '" download><img src="' . RUTA_IMG_ICONOS . 'descargar.png" ></a></td>';
              echo '</tr>';

              $numEvidencia++;
            }
          } else {
            echo "<tr style='background-color: #f8f9fa'><th colspan='9' class='text-center text-secondary'><small>No se encontró información para mostrar </small></th></tr>";
          }

          ?>
        </tbody>
      </table>
    </div>
  <?php endif; ?>
</div>
<div class="modal-footer">
    <button type="button" class="btn btn-labeled2 btn-primary" onclick="FinalizarFechasAsignacion(<?= $idticket ?>)">
      <span class="btn-label2"><i class="fa-solid fa-check"></i></span>Finalizar</button>
  
</div>