<?php
include_once "../../help.php";
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
        <th>Solicitante</th>
        <th>Razon Social</th>
      </tr>
    </thead>
    <tbody class="bg-white">
      <?php
      $sql = "SELECT
    op_solicitud_cheque.id,
    op_solicitud_cheque.id_estacion,
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
      ?>
        <tr>
          <th class="text-center align-middle fw-bold"><?= $id ?></th>
          <td class="align-middle" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarCheque(this, <?= $id ?>, 1)"><?= $fecha ?></div>
          </td>
          <td class="align-middle" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarCheque(this, <?= $id ?>, 2)"><?= $hora ?></div>
          </td>
          <td class="align-middle" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarCheque(this, <?= $id ?>,3)"><?= $beneficiario ?></div>
         
          </td>
          <td class="align-middle" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarCheque(this, <?= $id ?>, 4)">$<?= number_format($monto, 2) ?></div>
            
          </td>
          <td class="align-middle" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarCheque(this, <?= $id ?>, 5)"><?= $no_factura ?></div>
            
          </td>
          <td class="align-middle" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarCheque(this, <?= $id ?>, 6)"><?= $concepto ?></div>
            
          </td>
          <td class="align-middle" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarCheque(this, <?= $id ?>, 7)"><?= $solicitante ?></div>
           
          </td>
          <td class="align-middle" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarCheque(this, <?= $id ?>, 8,<?=$row['id_estacion']?>)"><?= $razonSocial ?></div>
            
          </td>
        </tr>
      <?php  }
      ?>
    </tbody>
  </table>
</div>