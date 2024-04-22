<?php  
include_once "../../app/help.php";
$con = $ClassConexionBD->conectarBD();
$idticket = $_GET['idticket'];

$soporteContenido = $ClassContenido->soporteContenido($idticket);

if($soporteContenido['estado'] == 3 || $soporteContenido['estado'] == 4){

    $explode = explode(' ',$soporteContenido['fechaterminoreal']);
    $FechaCierreTicket = date("Y-m-d",strtotime($explode[0]."+ 3 days"));

    if($FechaCierreTicket >= $fecha_del_dia){
        $BotonComentarios = '<button type="button" class="btn btn-success rounded-0" onclick="GuardarComentario('.$idticket.')" >Guardar</button>';  
    }else{
        $BotonComentarios = '<button type="button" class="btn btn-success rounded-0" disabled >Guardar</button>';    
    } 

}else{
    $BotonComentarios = '<button type="button" class="btn btn-success rounded-0" onclick="GuardarComentario('.$idticket.')" >Guardar</button>';
    
}
    
    $sql_comen = "SELECT * FROM ds_soporte_comentarios WHERE id_ticket = '".$idticket."' ORDER BY id DESC ";
    $result_comen = mysqli_query($con, $sql_comen);
    $numero_comen = mysqli_num_rows($result_comen);
?>

<div class="modal-header">
<h5 class="modal-title">Comentarios</h5>
<button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body"> 

<div class="border-bottom" style="height: 300px;overflow: auto;">
<?php
if ($numero_comen > 0) {
while($row_comen = mysqli_fetch_array($result_comen, MYSQLI_ASSOC)){
$idUsuario = $row_comen['id_personal'];
$comentario = $row_comen['comentario'];

$NomUsuario = $ClassContenido->Responsable($idUsuario);

if ($Session_IDUsuarioBD == $idUsuario) {
    $margin = "margin-left: 40px;margin-right: 5px;";
    $bgcolor = 'bg-sistemas';
    }else{
    $margin = "margin-right: 40px;margin-left: 5px;";
    $bgcolor = 'bg-personal';
    }

$fechaExplode = explode(" ", $row_comen['fecha_hora']);
$FechaFormato = FormatoFecha($fechaExplode[0]);
$HoraFormato = date("g:i a",strtotime($fechaExplode[1]));
?>
<div class="mt-1" style="<?=$margin;?>">

<div style="font-size: .7em;" class="mb-1"><?=$NomUsuario;?></div>
<div class="<?=$bgcolor;?>" style="border-radius: 20px;">
<div class="p-1 pb-1"><?=$comentario;?></div>
</div>
<div class="text-end text-secondary" style="font-size: .7em;margin-top: 1px"><?=$FechaFormato;?>, <?=$HoraFormato;?></div>

</div>
<?php
}
}else{
	echo "<div class='text-center' style='margin-top: 150px;'><small>No se encontraron comentarios</small></div>";
}
?>
</div>


<div class="mb-2 text-secondary mt-2">COMENTARIO:</div>
<textarea class="form-control rounded-0" id="Comentario"></textarea>
</div>


<div class="modal-footer">
<?=$BotonComentarios;?>
</div>



