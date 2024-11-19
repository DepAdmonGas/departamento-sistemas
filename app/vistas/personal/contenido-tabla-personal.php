<?php
include_once "../../help.php";
$numLista = $_GET['numLista'];
$condicion = "";
if ($numLista != 0) {
  $condicion = "AND tb_estaciones.numlista = $numLista";
}
?>
<div class="table-responsive">
  <table id="tabla-personal" class="custom-table" style="font-size: .8em;" width="100%">
    <thead class="bg-primary text-white">
      <tr>
        <th>#</th>
        <th>Nombre</th>
        <th>Puesto</th>
        <th>Usuario</th>
        <th>Contraseña</th>
        <th>Bitacora</th>
        <th>Estación</th>
        <th class="text-center align-middle" width="30px"><i class="fa-solid fa-ellipsis-vertical"></i></th>
      </tr>
    </thead>
    <tbody class="bg-white">
      <?php
      $sql = "SELECT
    tb_usuarios.id,
    tb_usuarios.nombre,
    tb_usuarios.usuario,
    tb_usuarios.bitacora_app,
    tb_usuarios.password,
    tb_puestos.tipo_puesto,   
    tb_estaciones.id AS id_estacion,
    tb_estaciones.nombre AS estacion
    FROM tb_usuarios
    INNER JOIN tb_puestos
    ON tb_usuarios.id_puesto = tb_puestos.id
    INNER JOIN tb_estaciones 
    ON tb_usuarios.id_gas = tb_estaciones.id WHERE tb_usuarios.estatus = 0 $condicion";
      $result = mysqli_query($con, $sql);
      $numero = mysqli_num_rows($result);
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $id = $row['id'];
        $idestacion = $row['id_estacion'];
        $estacion = $row['estacion'];
        $nombre = $row['nombre'];
        $puesto = $row['tipo_puesto'];
        $usuario = $row['usuario'];
        $contrasenia = $row['password'];
        $bitacora = $row['bitacora_app'];
      ?>
        <tr>
          <th class="p-0 no-hover text-center align-middle fw-bold"><?= $id ?></th>
          <td class="no-hover p-0" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start p-0 ms-1" style="font-size: 1em; width:auto;" contenteditable="false" oninput="editarPersonal(this, <?= $id; ?>, 1)"><?= $nombre ?></div>
          </td>
          <td class="no-hover p-0">
            <input onchange="datosRazonSocial(this,4,5)" class="form-control border-0 text-start p-0 ms-1" type="text" value="<?= $puesto ?>" style="font-size: 1em;" disabled>
          </td>
          <td class="no-hover p-0" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start p-0 ms-1" style="font-size: 1em; width:auto;" contenteditable="false" oninput="editarPersonal(this, <?= $id; ?>, 2)"><?= $usuario ?></div>
          </td>
          <td class="no-hover p-0" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start p-0 ms-1" style="font-size: 1em; width:auto;" contenteditable="false" oninput="editarPersonal(this, <?= $id; ?>, 3)"><?= $contrasenia ?></div>
          </td>
          <td class="no-hover p-0" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start p-0 ms-1" style="font-size: 1em; width:auto;" contenteditable="false" oninput="editarPersonal(this, <?= $id; ?>, 4)"><?= $bitacora ?></div>
          </td>
          <td class="no-hover p-0">
            <input onchange="datosRazonSocial(this,4,5)" class="form-control border-0 text-start p-0 ms-1" type="text" value="<?= $estacion ?>" style="font-size: 1em;" disabled>
          </td>

          <td class="p-0 no-hover text-center align-middle">

            <div class="btn-group">
              <button class="btn" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-ellipsis-vertical"></i>
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item"><i class="fa-solid fa-sliders"></i> Permisos</a></li>
                <li onclick="eliminarPersonal(<?= $id ?>)"><a class="dropdown-item"><i class="fa-solid fa-trash-can"></i> Eliminar</a></li>
              </ul>
            </div>

          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>