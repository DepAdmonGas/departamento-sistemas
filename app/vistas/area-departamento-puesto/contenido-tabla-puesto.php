<?php
include_once "../../help.php";
?>
<table class="table table-sm table-hover bg-white table-striped" id="tabla-puestos">
    <thead class="bg-primary text-white">
        <tr>
            <th class="text-center align-middle">#</th>
            <th class="align-middle">Nombre puesto</th>
            <th class="text-center align-middle" width="30px"><i class="fa-solid fa-ellipsis-vertical"></i></th>
        </tr>
    </thead>
    <tbody>
    <?php 
    $sql = "SELECT * FROM tb_puestos";
    $result = mysqli_query($con, $sql);
    $numero = mysqli_num_rows($result);
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
        echo '<tr>
        <td class="align-middle text-center fw-bold">'.$row['id'].'</td>
        <td class="align-middle">'.$row['tipo_puesto'].'</td>
        <td class="text-center align-middle">

        <div class="btn-group">
        <button class="btn btn-sm p-0" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item"><i class="fa-solid fa-pencil"></i> Editar</a></li>
            <li><a class="dropdown-item"><i class="fa-solid fa-trash-can"></i> Eliminar</a></li>
        </ul>
        </div>
       
        </td>
        </tr>';
    }
    ?>
    </tbody>
</table>