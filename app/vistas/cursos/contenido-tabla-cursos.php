<?php
include_once "../../help.php";
?>
<div class="table-responsive">
  <table id="tabla-cursos" class="custom-table" style="font-size: .8em;" width="100%">
    <thead class="bg-primary text-white">
      <tr>
        <th class="text-center">#</th>
        <th>Modulo</th>
        <th>Nombre titulo</th>
        <th>Categoria</th>
        <th class="text-center"><i class="fa-solid fa-file-pdf"></i></th>
        <th class="text-center align-middle" width="30px"><i class="fa-solid fa-ellipsis-vertical"></i></th>
      </tr>
    </thead>
    <tbody class="bg-white">
      <?php
      $sql = "SELECT
            tb_cursos_temas.num_tema,
            tb_cursos_temas.titulo,
            tb_cursos_temas.archivo,
            tb_cursos_temas.categoria,
            tb_cursos_temas.id_modulo,
            tb_cursos_modulos.titulo AS nomModulo
            FROM tb_cursos_temas 
            INNER JOIN tb_cursos_modulos 
            ON tb_cursos_temas.id_modulo = tb_cursos_modulos.id";
      $result = mysqli_query($con, $sql);
      $numero = mysqli_num_rows($result);
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $idModulo = $row['id_modulo'];
        $numTema = $row['num_tema'];
        $nombreModulo = $row['nomModulo'];
        $nombreTitulo = $row['titulo'];
        $categoria = $row['categoria'];
      ?>
        <tr>
          <th class="no-hover text-center align-middle p-0"><?= $numTema ?></th>
          <td class="align-middle p-0" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em; width:auto;" contenteditable="false" oninput="editarModulo(this,<?=$idModulo?>)"><?= $nombreModulo ?></div>
          </td>
          <td class="align-middle p-0" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em; width:auto;" contenteditable="false" oninput="editarCurso(this, <?=$idModulo?> ,<?= $numTema ?>,1)"><?= $nombreTitulo ?></div>
          </td>
          <td class="no-hover align-middle p-0" ondblclick="habilitarEdicion(this)">
            <div class="form-control border-0 text-start" style="font-size: 1em; width:auto;" contenteditable="false" oninput="editarCurso(this, <?=$idModulo?> ,<?= $numTema ?>,2)"><?= $categoria ?></div>
          </td>
          <td class="no-hover text-center align-middle p-0"><i class="fa-solid fa-file-pdf"></i></td>

          <td class="no-hover text-center align-middle p-0">

            <div class="btn-group">
              <button class="btn btn-sm" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
                <i class="fa-solid fa-ellipsis-vertical"></i>
              </button>
              <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item"><i class="fa-solid fa-sliders"></i> Cuestionario</a></li>
              </ul>
            </div>

          </td>

        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>