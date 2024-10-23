<?php
require_once "../../help.php";
$con = $ClassConexionBD->conectarBD();
$idticket = $_GET['idticket'];

$sql_comen = "SELECT * FROM ds_soporte_comentarios WHERE id_ticket = '" . $idticket . "' ORDER BY id DESC ";
$result_comen = mysqli_query($con, $sql_comen);
$numero_comen = mysqli_num_rows($result_comen);

$soporteContenido = $ClassContenido->soporteContenido($idticket);

if ($soporteContenido['estado'] == 3 || $soporteContenido['estado'] == 4) {

  $explode = explode(' ', $soporteContenido['fechaterminoreal']);
  $FechaCierreTicket = date("Y-m-d", strtotime($explode[0] . "+ 3 days"));

  if ($FechaCierreTicket >= $fecha_del_dia) {
    $BotonComentarios = '
    <tr class="bg-success" onclick="GuardarComentario(' . $idticket . ')">
        <th class="text-center bg-success text-white p-2">
          <i class="fa-regular fa-comment"></i> Guardar comentario
        </th>
      </tr>';
  } else {
    $BotonComentarios = '
    <tr class="bg-success" disabled>
        <th class="text-center bg-success text-white p-2">
          <i class="fa-regular fa-comment"></i> Guardar comentario
        </th>
      </tr>';
  }
} else {
  $BotonComentarios = '
  <tr class="bg-success" onclick="GuardarComentario(' . $idticket . ')">
        <th class="text-center bg-success text-white p-2">
          <i class="fa-regular fa-comment"></i> Guardar comentario
        </th>
      </tr>';
}

?>
<div class="table-responsive mt-3">
  <table class="custom-table" width="100%">
    <thead class="tables-bg">
      <tr>
        <th class="align-middle text-center">Comentarios</th>
      </tr>
    </thead>

    <tbody class="bg-white">

      <tr>
        <th class="text-start no-hover p-0 fw-normal">
          <div class="border-0 p-3" style="height: 350px;overflow: auto;">

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
                  <div class="title-table-bg text-white" style="border-radius: 30px;">
                    <p><?= $comentario; ?></p>
                  </div>
                  <div class="text-end" style="font-size: .7em;margin-top: -10px"><?= $FechaFormato; ?>, <?= $HoraFormato; ?></div>


                </div>

            <?php
              }
            } else {
              echo "<div class='text-center' style='margin-top: 140px;'><small>No se encontraron comentarios</small></div>";
            }
            ?>

        </th>
      </tr>

      <tr>
        <th class="text-start no-hover p-0 ">
          <textarea class="form-control rounded-0 border-0" id="Comentario" placeholder="Escribe tu comentario aquí..." style="height: 100px;"></textarea>
        </th>
      </tr>
      <?= $BotonComentarios; ?>
      </tr>
    </tbody>
  </table>
</div>

