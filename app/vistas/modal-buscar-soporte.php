<?php 
include_once "../../app/help.php";
?>

<div class="modal-header">
<h5 class="modal-title">Buscar</h5>
<button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
</div>

<div class="modal-body"> 

<h6>Estado:</h6>
<select name="" id="EstadoSoporte" class="form-control rounded-0">
    <option value="5">Todos</option>
    <option value="0">Creando</option>
    <option value="1">Pendiente</option>
    <option value="2">En proceso</option>
    <option value="3">Finalizados</option>
    <option value="4">Cancelados</option>
</select>

</div>
<div class="modal-footer">
<div class="text-end mt-2">
<button type="button" class="btn btn-primary rounded-0" onclick="BuscarSoporte()">Buscar</button>
</div>
</div>

