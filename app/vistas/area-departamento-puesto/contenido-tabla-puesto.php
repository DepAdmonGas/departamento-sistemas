<?php
include_once "../../help.php";
?>
<div class="table-responsive">
  <table id="tabla-puestos" class="custom-table" style="font-size: .8em;" width="100%">
    <thead class="bg-primary text-white">
      <tr>
        <th class="text-center align-middle">#</th>
        <th class="align-middle">Nombre puesto</th>
        <th class="text-center align-middle" width="20">
          <img src="<?= RUTA_IMG_ICONOS ?>eliminar.png">
        </th>
      </tr>
    </thead>
    <tbody class="bg-white">
      <?php
      $sql = "SELECT * FROM tb_puestos WHERE estatus = 0 ORDER BY id DESC";
      $result = mysqli_query($con, $sql);
      $numero = mysqli_num_rows($result);
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $id = $row['id'];
        $puesto = $row['tipo_puesto'];
      ?>
        <tr>
          <th class="align-middle text-center fw-bold"><?= $id ?></th>
          <td class="align-middle p-1" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarPuesto(this, <?= $id; ?>)"><?= $puesto ?></div>
          </td>
          <td class="text-center align-middle" onclick="eliminarPuesto(<?= $id ?>)">
            <img src="<?= RUTA_IMG_ICONOS ?>eliminar.png">
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>