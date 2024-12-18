<?php
include_once "../../help.php";

function FirmaSolicitud($idReporte, $con)
{
  $sql_firma = "SELECT * FROM op_solicitud_cheque_firma WHERE id_solicitud = '" . $idReporte . "' ";
  $result_firma = mysqli_query($con, $sql_firma);
  $numero_firma = mysqli_num_rows($result_firma);

  return $numero_firma;
}
?>
<div class="table-responsive">
  <table id="tabla-solicitud-cheque" class="custom-table" style="font-size: .8em;" width="100%">
    <thead class="bg-primary text-white">
      <tr>
        <th>#</th>
        <th width="100px">Fecha</th>
        <th width="100px">Hora</th>
        <th>Beneficiario</th>
        <th>Monto</th>
        <th>No. Factura</th>
        <th>Concepto</th>
        <th class="text-center align-middle" width="20">
          <img src="<?= RUTA_IMG_ICONOS ?>icon-firmar-w.png">
        </th>
        <th>Solicitante</th>
        <th>Razon Social</th>
      </tr>
    </thead>
    <tbody class="bg-white">
      <?php
      $sql = "SELECT
    op_solicitud_cheque.id,
    op_solicitud_cheque.id_estacion,
    op_solicitud_cheque.status,
    op_solicitud_cheque.fecha,
    op_solicitud_cheque.hora,
    op_solicitud_cheque.beneficiario,
    op_solicitud_cheque.monto,
    op_solicitud_cheque.no_factura,
    op_solicitud_cheque.razonsocial AS razonSocialSolicitud,
    op_solicitud_cheque.concepto,
    op_solicitud_cheque.solicitante,

    tb_estaciones.razonsocial AS razonSocialEstacion
    FROM op_solicitud_cheque 
    INNER JOIN tb_estaciones 
    ON op_solicitud_cheque.id_estacion = tb_estaciones.id";
      $result = mysqli_query($con, $sql);
      $numero = mysqli_num_rows($result);
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

        $razonSocial = ($row['id_estacion'] == 8) ? $row['razonSocialSolicitud'] : $row['razonSocialEstacion'];
        $id = $row['id'];
        $fecha = $row['fecha'];
        $hora = $row['hora'];
        $beneficiario = $row['beneficiario'];
        $monto = $row['monto'];
        $no_factura = $row['no_factura'];
        $concepto = $row['concepto'];
        $solicitante = $row['solicitante'];

        $Firmas = FirmaSolicitud($id, $con);

        if ($Firmas == 1) {
          $Firmar = '<img class="pointer" src="' . RUTA_IMG_ICONOS . 'icon-firmar.png" onclick="firmar(' . $id . ',1)" data-toggle="tooltip" data-placement="top" title="Firmar solicitud">';
        } else if ($Firmas == 2) {
          $Firmar = '<img class="pointer" src="' . RUTA_IMG_ICONOS . 'icon-firmar-vb.png" onclick="firmar(' . $id . ',2)" data-toggle="tooltip" data-placement="top" title="Firmar solicitud">';
        } else if ($Firmas == 3 || $row['status'] == 2) {
          $Firmar = '<img class="grayscale" src="' . RUTA_IMG_ICONOS . 'icon-firmar-ao.png" onclick="firmar(' . $id . ',3)" data-toggle="tooltip" data-placement="top" title="Firmar solicitud">';
        }
      ?>
        <tr>
          <th class="no-hover p-0 text-center align-middle fw-bold"><?= $id ?></th>
          <td class="align-middle no-hover p-0">
            <div class="form-control border-0 text-start" style="font-size: 1em; width:auto;" contenteditable="false"><?= $fecha ?></div>
          </td>
          <td class="align-middle no-hover p-0">
            <div class="form-control border-0 text-start" style="font-size: 1em; width:auto;" contenteditable="false"><?= $hora ?></div>
          </td>
          <td class="align-middle no-hover p-0" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em; width:auto;" contenteditable="false" oninput="editarCheque(this, <?= $id ?>,3)"><?= $beneficiario ?></div>

          </td>
          <td class="align-middle no-hover p-0" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em; width:auto;" contenteditable="false" oninput="editarCheque(this, <?= $id ?>, 4)">$<?= number_format($monto, 2) ?></div>

          </td>
          <td class="align-middle no-hover p-0" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em; width:auto;" contenteditable="false" oninput="editarCheque(this, <?= $id ?>, 5)"><?= $no_factura ?></div>

          </td>
          <td class="align-middle no-hover p-0" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em; width:auto;" contenteditable="false" oninput="editarCheque(this, <?= $id ?>, 6)"><?= $concepto ?></div>

          </td>
          <td class="align-middle no-hover text-center"><?=$Firmar?></td>
          <td class="align-middle no-hover p-0">
            <div class="form-control border-0 text-start" style="font-size: 1em; width:auto;" contenteditable="false"><?= $solicitante ?></div>

          </td>
          <td class="align-middle no-hover p-0">
            <div class="form-control border-0 text-start" style="font-size: 1em; width:auto;" contenteditable="false"><?= $razonSocial ?></div>

          </td>
        </tr>
      <?php  }
      ?>
    </tbody>
  </table>
</div>