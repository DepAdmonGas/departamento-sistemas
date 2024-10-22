<?php 
include_once "../../help.php";
$con = $ClassConexionBD->conectarBD();

$sql = "SELECT
ds_soporte.id_ticket, 
ds_soporte.id_personal,
ds_soporte.descripcion,
ds_soporte.prioridad,
ds_soporte.fecha_creacion,
ds_soporte.fecha_inicio,        
ds_soporte.fecha_termino,
ds_soporte.tiempo_solucion,
ds_soporte.fecha_termino_real,
ds_soporte.porcentaje,
ds_soporte.id_personal_soporte,
ds_soporte.estado,
tb_usuarios.nombre,
tb_estaciones.nombre AS nomestacion,
tb_puestos.tipo_puesto
FROM ds_soporte 
INNER JOIN tb_usuarios 
ON ds_soporte.id_personal = tb_usuarios.id
INNER JOIN tb_estaciones
ON tb_usuarios.id_gas = tb_estaciones.id
INNER JOIN tb_puestos
ON tb_usuarios.id_puesto = tb_puestos.id
WHERE ds_soporte.id_personal = '".$Session_IDUsuarioBD."' ORDER BY ds_soporte.fecha_termino_real ASC";
$result = mysqli_query($con, $sql);
$numero = mysqli_num_rows($result);

?>
<table class="table table-bordered" id="tabla-actividades">
            <thead class="bg-primary text-white">
                <tr>
                    <th>FECHA CREACIÓN</th>
                    <th>DESCRIPCIÓN</th>
                    <th>PRIORIDAD</th>
                    <th>FECHA INICIO</th>
                    <th>FECHA DE TERMINO</th>
                    <th>TOTAL DIAS</th>
                    <th>PORCENTAJE</th>
                    <th>RESPONSABLE</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td>8</td>
                </tr>
                <tr>
                    <td>1</td>
                    <td>2</td>
                    <td>3</td>
                    <td>4</td>
                    <td>5</td>
                    <td>6</td>
                    <td>7</td>
                    <td>8</td>
                </tr>
            </tbody>
        </table>