<?php
include_once "../../help.php";
?>
<table class="table table-bordered table-sm table-hover table-striped bg-white" id="tabla-personal">
    <thead class="bg-primary text-white">
    <tr>
        <th>#</th>
        <th>Nombre</th>
        <th>Puesto</th>
        <th>Usuario</th>
        <th>Contraseña</th>
        <th>Estación</th>
        <th class="text-center align-middle" width="30px"><i class="fa-solid fa-ellipsis-vertical"></i></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT
    tb_usuarios.id,
    tb_usuarios.nombre,
    tb_usuarios.usuario,
    tb_usuarios.password,
    tb_puestos.tipo_puesto,   
    tb_estaciones.id AS id_estacion,
    tb_estaciones.nombre AS estacion
    FROM tb_usuarios
    INNER JOIN tb_puestos
    ON tb_usuarios.id_puesto = tb_puestos.id
    INNER JOIN tb_estaciones 
    ON tb_usuarios.id_gas = tb_estaciones.id WHERE tb_usuarios.estatus = 0";
    $result = mysqli_query($con, $sql);
    $numero = mysqli_num_rows($result);
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    echo '<tr>
    <td class="text-center align-middle fw-bold">'.$row['id'].'</td>
    <td class="align-middle">'.$row['nombre'].'</td>
    <td class="align-middle">'.$row['tipo_puesto'].'</td>
    <td class="align-middle">'.$row['usuario'].'</td>
    <td class="align-middle">'.$row['password'].'</td>
    <td class="align-middle text-primary"><a href="personal-estacion/'.$row['id_estacion'].'">'.$row['estacion'].'</a></td>

    <td class="text-center align-middle">

        <div class="btn-group">
        <button class="btn" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item"><i class="fa-solid fa-pencil"></i> Editar</a></li>
            <li><a class="dropdown-item"><i class="fa-solid fa-sliders"></i> Permisos</a></li>
            <li><a class="dropdown-item"><i class="fa-solid fa-trash-can"></i> Eliminar</a></li>
        </ul>
        </div>
       
    </td>
    </tr>';
    }
    ?>
    </tbody>
</table>