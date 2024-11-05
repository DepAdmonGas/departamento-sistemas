<?php
include_once "../../help.php";
$con = $ClassConexionBD->conectarBD();
$idticket = $_GET['idticket'];
$usuario = $_GET['usuario'];

$soporteContenido = $ClassContenido->soporteContenido($idticket);

if ($soporteContenido['estado'] == 3 || $soporteContenido['estado'] == 4) {

  $explode = explode(' ', $soporteContenido['fechaterminoreal']);
  $FechaCierreTicket = date("Y-m-d", strtotime($explode[0] . "+ 3 days"));

  if ($FechaCierreTicket >= $fecha_del_dia) {
    $BotonComentarios = '
        <button type="button" class="btn btn-labeled2 btn-success" onclick="GuardarComentario(' . $idticket . ',' . $usuario . ')">
            <span class="btn-label2"><i class="fa fa-check"></i></span>Guardar</button>';
  } else {
    $BotonComentarios = '<button type="button" class="btn btn-success rounded-0" disabled >Guardar</button>';
  }
} else {
  $BotonComentarios = '
    <button type="button" class="btn btn-labeled2 btn-success" onclick="GuardarComentario(' . $idticket . ',' . $usuario . ')">
            <span class="btn-label2"><i class="fa fa-check"></i></span>Guardar</button>
    ';
}
// Comentarios
$sql_comen = "SELECT * FROM ds_soporte_comentarios WHERE id_ticket = '" . $idticket . "' ORDER BY id DESC ";
$result_comen = mysqli_query($con, $sql_comen);
$numero_comen = mysqli_num_rows($result_comen);
// Detalle ticket
$sql = "SELECT descripcion,prioridad,porcentaje FROM ds_soporte WHERE id_ticket = '" . $idticket . "'";
$result = mysqli_query($con, $sql);
while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
  $descripcion = $row['descripcion'];
  $prioridad = $row['prioridad'];
  $porcentaje = $row['porcentaje'];
}
?>

<div class="modal-header">
  <h5 class="modal-title">Comentarios</h5>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">

  <div class="" style="height: 300px;overflow: auto;">

    <div style="font-size: .75em;" class="mb-1 text-secondary text-center fw-bold">Información</div>
    <div class="bg-light fw-light pt-2 pb-1 ps-3 pe-3 mb-3" style="font-size: .85em; border-radius: 25px;">
      <div class="text-center d-flex justify-content-around">
        <div><b>Ticket:</b> <?= $idticket ?></div>
        <div><b>Prioridad:</b> <?= $prioridad ?></div>
        <div><b>Porcentaje:</b> <?= $porcentaje ?> %</div>
      </div>
      <div><b>Descripción:</b> <?= $descripcion ?> <br></div>
    </div>



    <div>
      <?php
      if ($numero_comen > 0) {
        while ($row_comen = mysqli_fetch_array($result_comen, MYSQLI_ASSOC)) {
          $idUsuario = $row_comen['id_personal'];
          $comentario = $row_comen['comentario'];

          $NomUsuario = $ClassContenido->Responsable($idUsuario);

          if ($Session_IDUsuarioBD == $idUsuario) {
            $margin = "margin-left: 40px;margin-right: 5px;";
            $bgcolor = 'bg-sistemas';
          } else {
            $margin = "margin-right: 40px;margin-left: 5px;";
            $bgcolor = 'bg-personal';
          }

          $fechaExplode = explode(" ", $row_comen['fecha_hora']);
          $FechaFormato = FormatoFecha($fechaExplode[0]);
          $HoraFormato = date("g:i a", strtotime($fechaExplode[1]));
      ?>
          <div class="mt-1" style="<?= $margin; ?>">

            <div style="font-size: .7em;" class="mb-1"><?= $NomUsuario; ?></div>
            <div class="<?= $bgcolor; ?>" style="border-radius: 20px;">
              <div class="p-1 pb-1"><?= $comentario; ?></div>
            </div>
            <div class="text-end text-secondary" style="font-size: .7em;margin-top: 1px"><?= $FechaFormato; ?>, <?= $HoraFormato; ?></div>

          </div>
      <?php
        }
      }
      ?>
    </div>
  </div>
</div>
<div class="border-top">
  <textarea class="form-control rounded-0" id="Comentario" placeholder="* Comentarios"></textarea>
</div>

<div class="modal-footer">
  <?= $BotonComentarios; ?>
</div>