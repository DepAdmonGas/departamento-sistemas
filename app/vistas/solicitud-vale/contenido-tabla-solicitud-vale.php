<?php
include_once "../../help.php";
?>
<table class="table table-bordered table-sm table-hover table-striped bg-white" id="tabla-solicitud-vale">
    <thead class="bg-primary text-white">
    <tr>
        <th class="align-middle">Folio</th>
        <th class="align-middle" width="100px">Fecha</th>
        <th class="align-middle" width="100px">Hora</th>
        <th class="align-middle">Monto</th>
        <th class="align-middle">Concepto</th>
        <th class="align-middle">Solicitante</th>
        <th class="align-middle">Autorizado</th>
        <th class="align-middle">Metodo Autorización</th>
        <th class="align-middle">Razon Social</th>
        <th class="align-middle">Cuenta</th>
        
        <th class="text-center align-middle" width="30px"><i class="fa-solid fa-ellipsis-vertical"></i></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT 
    op_solicitud_vale.id,
    op_solicitud_vale.folio,
    op_solicitud_vale.fecha,
    op_solicitud_vale.hora,
    op_solicitud_vale.monto,
    op_solicitud_vale.concepto,
    op_solicitud_vale.solicitante,
    op_solicitud_vale.autorizado_por,
    op_solicitud_vale.metodo_autorizacion,

    op_solicitud_vale.cuenta,
    tb_estaciones.nombre,
    tb_estaciones.razonsocial,
    tb_usuarios.nombre AS nombreUsuario
    FROM op_solicitud_vale 
    INNER JOIN tb_estaciones 
    ON op_solicitud_vale.id_estacion = tb_estaciones.id
    INNER JOIN tb_usuarios
    ON op_solicitud_vale.id_usuario = tb_usuarios.id";
    $result = mysqli_query($con, $sql);
    $numero = mysqli_num_rows($result);
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){
    
    echo '<tr>
    <td class="text-center align-middle fw-bold">00'.$row['folio'].'</td>
    <td class="align-middle">'.$row['fecha'].'</td>
    <td class="align-middle">'.$row['hora'].'</td>
    <td class="align-middle">$'.number_format($row['monto'],2).'</td>
    <td class="align-middle">'.$row['concepto'].'</td>
    <td class="align-middle">'.$row['solicitante'].'</td>
    <td class="align-middle">'.$row['autorizado_por'].'</td>
    <td class="align-middle">'.$row['metodo_autorizacion'].'</td>

    <td class="align-middle">'.$row['razonsocial'].'</td>
    <td class="align-middle">'.$row['cuenta'].'</td>
    
    <td class="text-center align-middle">

        <div class="btn-group">
        <button class="btn btn-sm" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical"></i>
        </button>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
            <li><a class="dropdown-item"><i class="fa-solid fa-pencil"></i> Editar</a></li>
        </ul>
        </div>
       
    </td>
    </tr>';
    }
    ?>
    </tbody>
 </table>