<?php
require('../../help.php');

?>
<script>
  $(document).ready(function() {
    $('#modulo').selectize({
      create: false,
      sortField: 'text'
    });
  });
</script>

<div class="modal-header">
  <h5 class="modal-title">Temas</h5>
  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body">

  <div class="row">

    <div class="col-xl-6 col-lg-6 col-md-12 col-sm-12 mb-2">
      <div class="mb-1 text-secondary fw-bold">Modulo:</div>
      <select class="" id="modulo">
        <?php
        $sql_area = "SELECT num_modulo, titulo FROM tb_cursos_modulos ORDER BY id";
        $result_area = mysqli_query($con, $sql_area);
        while ($row_area = mysqli_fetch_array($result_area, MYSQLI_ASSOC)) {
          $id = $row_area['num_modulo'];
          $titulo = $row_area['titulo'];
          echo "<option value='" . $id . "'>" . $titulo . "</option>";
        }
        ?>
      </select>
    </div>
    <div class="col-12 mb-2">
      <div class="mb-1 mt-2 text-secondary fw-bold">* NOMBRE TEMA:</div>
      <input type="text" class="form-control rounded-0" id="nombre_tema">
    </div>
  </div>

</div>

<div class="modal-footer">
  <button type="button" class="btn btn-labeled2 btn-success" onclick="agregarTema()">
    <span class="btn-label2"><i class="fa fa-check"></i></span>Agregar</button>
</div>