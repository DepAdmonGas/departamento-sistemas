<?php
include_once "../../help.php";
?>
<table class="table table-bordered table-sm table-hover table-striped bg-white" id="tabla-solicitud-cheque">
    <thead class="bg-primary text-white">
    <tr>
        <th>#</th>
        <th width="100px">Fecha</th>
        <th width="100px">Hora</th>
        <th>Beneficiario</th>
        <th>Monto</th>
        <th>No. Factura</th>
        <th>Concepto</th>
        <th>Solicitante</th>
        <th>Razon Social</th>
        <th class="text-center align-middle" width="30px"><i class="fa-solid fa-ellipsis-vertical"></i></th>
    </tr>
    </thead>
    <tbody>
    <?php
    $sql = "SELECT
    op_solicitud_cheque.id,
    op_solicitud_cheque.id_estacion,
    op_solicitud_cheque.fecha,
    op_solicitud_cheque.hora,
    op_solicitud_cheque.beneficiario,
    op_solicitud_cheque.monto,
    op_solicitud_cheque.no_factura,
    op_solicitud_cheque.razonsocial AS razonSocialSolicitud,
    op_solicitud_cheque.concepto,
    op_solicitud_cheque.solicitante,

    tb_estaciones.razonsocial AS razonSocialEstacion
    FROM op_solicitud_cheque 
    INNER JOIN tb_estaciones 
    ON op_solicitud_cheque.id_estacion = tb_estaciones.id";
    $result = mysqli_query($con, $sql);
    $numero = mysqli_num_rows($result);
    while($row = mysqli_fetch_array($result, MYSQLI_ASSOC)){

    $razonSocial = ($row['id_estacion'] == 8)? $row['razonSocialSolicitud']: $row['razonSocialEstacion'];
    
    echo '<tr>
    <td class="text-center align-middle fw-bold">'.$row['id'].'</td>
    <td class="align-middle">'.$row['fecha'].'</td>
    <td class="align-middle">'.$row['hora'].'</td>
    <td class="align-middle">'.$row['beneficiario'].'</td>
    <td class="align-middle">$'.number_format($row['monto'],2).'</td>
    <td class="align-middle">'.$row['no_factura'].'</td>
    <td class="align-middle">'.$row['concepto'].'</td>
    <td class="align-middle">'.$row['solicitante'].'</td>
    <td class="align-middle">'.$razonSocial.'</td>

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