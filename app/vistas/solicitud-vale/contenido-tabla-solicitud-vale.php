<?php
include_once "../../help.php";
?>
<div class="table-responsive">
  <table id="tabla-solicitud-vale" class="custom-table" style="font-size: .8em;" width="100%">
    <thead class="bg-primary text-white">
      <tr>
        <th class="align-middle">Folio</th>
        <th class="align-middle" width="100px">Fecha</th>
        <th class="align-middle" width="100px">Hora</th>
        <th class="align-middle">Monto</th>
        <th class="align-middle">Concepto</th>
        <th class="align-middle">Solicitante</th>
        <th class="align-middle">Autorizado</th>
        <th class="align-middle">Metodo Autorización</th>
        <th class="align-middle">Razon Social</th>
        <th class="align-middle">Cuenta</th>
      </tr>
    </thead>
    <tbody class="bg-white">
      <?php
      $sql = "SELECT 
    op_solicitud_vale.id,
    op_solicitud_vale.folio,
    op_solicitud_vale.fecha,
    op_solicitud_vale.hora,
    op_solicitud_vale.monto,
    op_solicitud_vale.concepto,
    op_solicitud_vale.solicitante,
    op_solicitud_vale.autorizado_por,
    op_solicitud_vale.metodo_autorizacion,

    op_solicitud_vale.cuenta,
    tb_estaciones.nombre,
    tb_estaciones.razonsocial,
    tb_usuarios.nombre AS nombreUsuario
    FROM op_solicitud_vale 
    INNER JOIN tb_estaciones 
    ON op_solicitud_vale.id_estacion = tb_estaciones.id
    INNER JOIN tb_usuarios
    ON op_solicitud_vale.id_usuario = tb_usuarios.id";
      $result = mysqli_query($con, $sql);
      $numero = mysqli_num_rows($result);
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $folio = $row['folio'];
        $fecha = $row['fecha'];
        $hora = $row['hora'];
        $monto = $row['monto'];
        $concepto = $row['concepto'];
        $solicitante = $row['solicitante'];
        $autorizado_por = $row['autorizado_por'];
        $metodo_autorizacion = $row['metodo_autorizacion'];
        $razon_social = $row['razonsocial'];
        $cuenta = $row['cuenta'];
      ?>
        <tr>
          <td class="text-center align-middle fw-bold">00<?= $folio ?></td>
          <td class="align-middle"><?= $fecha ?></td>
          <td class="align-middle"><?= $hora ?></td>
          <td class="align-middle" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarVale(this, <?= $folio ?>, 1)">$<?= number_format($monto, 2) ?></div>
          </td>
          <td class="align-middle" ondblclick="habilitarEdicion(this)">
          <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarVale(this, <?= $folio ?>, 2)"><?=$concepto?></div>
          </td>
          <td class="align-middle" ondblclick="habilitarEdicion(this)">
          <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarVale(this, <?= $folio ?>, 3)"><?= $solicitante ?></div>
          </td>
          <td class="align-middle" ondblclick="habilitarEdicion(this)">
          <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarVale(this, <?= $folio ?>, 4)"><?= $autorizado_por ?></div>

          </td>
          <td class="align-middle" ondblclick="habilitarEdicion(this)">
          <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarVale(this, <?= $folio ?>, 5)"><?= $metodo_autorizacion ?></div>

          </td>
          <td class="align-middle" ondblclick="habilitarEdicion(this)">
          <?= $razon_social ?>
          </td>
          <td class="align-middle" ondblclick="habilitarEdicion(this)">
          <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarVale(this, <?= $folio ?>, 7)"><?= $cuenta ?></div>
  
        </td>
        </tr>
      <?php }
      ?>
    </tbody>
  </table>
</div>