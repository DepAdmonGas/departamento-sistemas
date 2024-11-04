<?php
include_once "../../help.php";
?>
<table class="table table-sm table-hover bg-white table-striped" id="tabla-cursos">
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
    <tbody>
    <?php
            $sql = "SELECT
            tb_cursos_temas.num_tema,
            tb_cursos_temas.titulo,
            tb_cursos_temas.archivo,
            tb_cursos_temas.categoria,
            tb_cursos_modulos.num_modulo,
            tb_cursos_modulos.titulo AS nomModulo
            FROM tb_cursos_temas 
            INNER JOIN tb_cursos_modulos 
            ON tb_cursos_temas.id_modulo = tb_cursos_modulos.id";
            $result = mysqli_query($con, $sql);
            $numero = mysqli_num_rows($result);
            while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
            echo '<tr>
            <td class="text-center align-middle">'.$row['num_tema'].'</td>
            <td class="align-middle">'.$row['nomModulo'].'</td>
            <td class="align-middle">'.$row['titulo'].'</td>
            <td class="align-middle">'.$row['categoria'].'</td>
            <td class="text-center align-middle"><i class="fa-solid fa-file-pdf"></i></td>

            <td class="text-center align-middle">

            <div class="btn-group">
            <button class="btn btn-sm" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
            <i class="fa-solid fa-ellipsis-vertical"></i>
            </button>
            <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
                <li><a class="dropdown-item"><i class="fa-solid fa-pencil"></i> Editar</a></li>
                <li><a class="dropdown-item"><i class="fa-solid fa-sliders"></i> Cuestionario</a></li>
            </ul>
            </div>
        
            </td>

            </tr>';
            }
    ?>
    </tbody>
</table>

