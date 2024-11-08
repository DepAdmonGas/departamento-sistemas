<?php
include_once "../../help.php";
?>
<div class="table-responsive">
  <table id="tabla-estaciones" class="custom-table" style="font-size: .8em;" width="100%">
    <thead class="bg-primary text-white">
      <tr>
        <th class="text-center align-middle">#</th>
        <th class="align-middle">Nombre</th>
        <th class="align-middle">Permiso cre</th>
        <th class="align-middle">Razon Social</th>
        <th class="align-middle">RFC</th>
        <th class="align-middle">Dirección</th>
        <th class="align-middle">Apoderado Legal</th>
        <th class="text-center align-middle" width="30px"><i class="fa-solid fa-ellipsis-vertical"></i></th>
      </tr>
    </thead>
    <tbody class="bg-white">
      <?php
      $sql = "SELECT id, numlista, nombre, permisocre, razonsocial, rfc, direccioncompleta, apoderado_legal FROM tb_estaciones WHERE estatus = 0 ORDER BY numlista ASC";
      $result = mysqli_query($con, $sql);
      $numero = mysqli_num_rows($result);
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $numeroLista = $row['numlista'];
        $nombre = $row['nombre'];
        $permisoCRE = $row['permisocre'];
        $razonSocial = $row['razonsocial'];
        $rfc = $row['rfc'];
        $direccion = $row['direccioncompleta'];
        $apoderadoLegal = $row['apoderado_legal'];
        if($permisoCRE == ""){
          $permisoCRE = "CRE";
        }
        if($razonSocial == ""){
          $razonSocial = "RAZON SOCIAL";
        }
        if($rfc == ""){
          $rfc = "RFC";
        }
        if($direccion == ""){
          $direccion = "DIRECCION";
        }
        if($apoderadoLegal == ""){
          $apoderadoLegal = "APODERADO LEGAL";
        }
      ?>

        <tr>
          <th class="text-center align-middle fw-bold"><?= $numeroLista ?></th>
          <td class="align-middle p-1" style="height: 100%; padding: 0;" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarEstacion(this, <?= $numeroLista; ?>, 1)"><?= $nombre ?></div>
          </td>
          <td class="align-middle p-1" style="height: 100%; padding: 0;" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarEstacion(this, <?= $numeroLista; ?>, 2)"><?= $permisoCRE ?></div>
          </td>
          <td class="align-middle p-1" style="height: 100%; padding: 0;" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarEstacion(this, <?= $numeroLista; ?>, 3)"><?= $razonSocial ?></div>
          </td>
          <td class="align-middle p-1" style="height: 100%; padding: 0;" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarEstacion(this, <?= $numeroLista; ?>, 3)"><?= $rfc ?></div>
          </td>
          <td class="align-middle p-1" style="height: 100%; padding: 0;" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarEstacion(this, <?= $numeroLista; ?>, 3)"><?= $direccion ?></div>

          </td>
          <td class="align-middle p-1" style="height: 100%; padding: 0;" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em;" contenteditable="false" oninput="editarEstacion(this, <?= $numeroLista; ?>, 3)"><?= $apoderadoLegal ?></div>

          </td>
          <th class="text-center align-middle">

            <div class="btn-group">
              <button class="btn" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-ellipsis-vertical"></i>
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li onclick="personalEstacion(<?= $numeroLista ?>)"><a class="dropdown-item"><i class="fa-solid fa-users"></i> Personal</a></li>
                <li onclick="eliminarEstacion(<?= $numeroLista ?>)"><a class="dropdown-item"><i class="fa-solid fa-trash-can"></i> Eliminar</a></li>
              </ul>
            </div>

          </th>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>