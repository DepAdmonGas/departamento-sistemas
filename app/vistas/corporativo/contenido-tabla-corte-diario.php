<?php
include_once "../../help.php";
?>
<div class="table-responsive">
  <table id="tabla-corte-diario" class="custom-table" style="font-size: .8em;" width="100%">
    <thead class="bg-primary text-white">
      <tr>
        <th class="text-center align-middle">#</th>
        <th class="align-middle">Fecha</th>
        <th class="align-middle">Estación</th>
        <th class="text-center align-middle" width="30px"><i class="fa-solid fa-ellipsis-vertical"></i></th>
      </tr>
    </thead>
    <tbody class="bg-white">
      <?php
      $sql = "SELECT
     op_corte_year.id AS idCorteYear,
     op_corte_year.year,
     tb_estaciones.id AS idEstacion,
     tb_estaciones.nombre AS nomEstacion,
     op_corte_mes.mes,
     op_corte_dia.id AS idCorteDia,
     op_corte_dia.fecha,
     op_corte_dia.ventas,
     op_corte_dia.tpv,
     op_corte_dia.monedero     
     FROM op_corte_year 
     INNER JOIN tb_estaciones 
     ON tb_estaciones.id = op_corte_year.id_estacion
     INNER JOIN op_corte_mes 
     ON op_corte_mes.id_year = op_corte_year.id
     INNER JOIN op_corte_dia 
     ON op_corte_dia.id_mes = op_corte_mes.id WHERE op_corte_dia.fecha <= CURRENT_DATE ORDER BY op_corte_dia.fecha DESC limit 300";
      $result = mysqli_query($con, $sql);
      $numero = mysqli_num_rows($result);
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {

        $trColor = ($row['ventas'] == 1 && $row['tpv'] == 1 && $row['monedero'] == 1) ?  'background: #dffbe6' : 'background: #fbdfdf';
        ?>
        <tr style="<?=$trColor?>">
        <th class="text-center align-middle p-0 fw-bold"><?=$row['idCorteDia']?></th>
        <td class="align-middle p-0"><?=FormatoFecha($row['fecha'])?></td>
        <td class="align-middle p-0 fw-bold"><?=$row['nomEstacion']?></td>

        <td class="text-center align-middle p-0">

        <div class="btn-group">
        <a class="p-1" id="dropdownMenuButton" data-bs-toggle="dropdown" aria-expanded="false">
        <i class="fa-solid fa-ellipsis-vertical"></i>
        </a>
        <ul class="dropdown-menu" aria-labelledby="dropdownMenuButton">
          <?php 
          if($row['ventas'] == 1 && $row['tpv'] == 1 && $row['monedero'] == 1){         
          ?>
            <li onclick="activarCorte(<?=$row['idCorteDia']?>,<?=$Session_IDUsuarioBD?>)"><a class="dropdown-item"><i class="fa-solid fa-pencil"></i> Activar</a></li>
          <?php }else{ ?>
            <li onclick="finalizarCorte(<?=$row['idCorteDia']?>)"><a class="dropdown-item"><i class="fa-solid fa-sliders"></i> Finalizar</a></li>
          <?php }?>
          </ul>
        </div>
       
        </td>

        </tr>
        <?php
      }

      ?>
    </tbody>
  </table>
</div>