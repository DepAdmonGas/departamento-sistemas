<?php
include_once "../../help.php";
?>
<table class="table table-bordered table-sm table-hover bg-white table-striped" id="tabla-estaciones">
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
    <tbody>
    <?php
    $sql = "SELECT id, numlista, nombre, permisocre, razonsocial, rfc, direccioncompleta, apoderado_legal FROM tb_estaciones WHERE estatus = 1 ORDER BY numlista ASC";
    $result = mysqli_query($con, $sql);
    $numero = mysqli_num_rows($result);
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    
        echo '<tr>
        <td class="text-center align-middle fw-bold">'.$row['numlista'].'</td>
        <td class="align-middle">'.$row['nombre'].'</td>
        <td class="align-middle">'.$row['permisocre'].'</td>
        <td class="align-middle">'.$row['razonsocial'].'</td>
        <td class="align-middle">'.$row['rfc'].'</td>
        <td class="align-middle">'.$row['direccioncompleta'].'</td>
        <td class="align-middle">'.$row['apoderado_legal'].'</td>
        <td class="text-center align-middle">

        <div class="btn-group">
        <button class="btn" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item"><i class="fa-solid fa-pencil"></i> Editar</a></li>
            <li><a class="dropdown-item"><i class="fa-solid fa-users"></i> Personal</a></li>
            <li><a class="dropdown-item"><i class="fa-solid fa-trash-can"></i> Eliminar</a></li>
        </ul>
        </div>
       
        </td>
        </tr>';
    }
    ?>       
    </tbody>
</table>