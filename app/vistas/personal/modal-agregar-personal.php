<?php
require('../../help.php');

?>
<script>
  $(document).ready(function() {
    $('#puesto').selectize({
      create: false,
      sortField: 'text'
    });
    $('#estacion').selectize({
      create: false,
      sortField: 'text'
    });
  });
</script>

<div class="modal-header">
  <h5 class="modal-title">Nuevo Personal</h5>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">

  <div class="row">

    <div class="col-12 mb-2">
      <div class="mb-1 mt-2 text-secondary fw-bold">* NOMBRE COMPLETO:</div>
      <input type="text" class="form-control rounded-0" id="nombre">
    </div>

    <div class="col-12 mb-2">
      <div class="mb-1 text-secondary fw-bold">* PUESTO:</div>
      <select id="puesto">
        <option value="">Selecciona una opción...</option>
        <?php
        $sql_area = "SELECT id, tipo_puesto FROM tb_puestos WHERE estatus = 0";
        $result_area = mysqli_query($con, $sql_area);
        while ($row_area = mysqli_fetch_array($result_area, MYSQLI_ASSOC)) {
          $id = $row_area['id'];
          $area = $row_area['tipo_puesto'];
          echo "<option value='" . $id . "'>" . $area . "</option>";
        }
        ?>
      </select>
    </div>


    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-2">
      <div class="mb-1 text-secondary fw-bold">* USUARIO:</div>
      <input type="text" class="form-control rounded-0" id="usuario">
    </div>

    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-2">
      <div class="mb-1 text-secondary fw-bold">* CONTRASEÑA:</div>
      <input type="text" class="form-control rounded-0" id="password">
    </div>

    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-2">
      <div class="mb-1 text-secondary fw-bold">TELEFONO:</div>
      <input type="text" class="form-control rounded-0" id="telefono">
    </div>

    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-2">
      <div class="mb-1 text-secondary fw-bold">CORREO:</div>
      <input type="text" class="form-control rounded-0" id="email">
    </div>

    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-2">
      <div class="mb-1 text-secondary fw-bold">* ESTACION:</div>
      <select class="" id="estacion">
        <option value="">Selecciona una opción...</option>
        <?php
        $sql_area = "SELECT numlista, nombre FROM tb_estaciones WHERE estatus = 0";
        $result_area = mysqli_query($con, $sql_area);
        while ($row_area = mysqli_fetch_array($result_area, MYSQLI_ASSOC)) {
          $id = $row_area['numlista'];
          $estacion = $row_area['nombre'];
          echo "<option value='" . $id . "'>" . $estacion . "</option>";
        }
        ?>
      </select>
    </div>

  </div>

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-labeled2 btn-success" onclick="agregarPersonal()">
    <span class="btn-label2"><i class="fa fa-check"></i></span>Agregar</button>
</div>