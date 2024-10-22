<?php
require '../help.php';
$con = $ClassConexionBD->conectarBD();
$idUsuario = $_POST['idUsuario'];
$token = bin2hex(random_bytes(3));

$sql = "SELECT id, estatus FROM op_token_telegram WHERE id_usuario = '" . $idUsuario . "' ORDER BY id DESC LIMIT 1";
$result = mysqli_query($con, $sql);
$numero_lista = mysqli_num_rows($result);

if($numero_lista > 0){
while ($row_lista = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
$idToken = $row_lista['id'];
$estatus = $row_lista['estatus'];
}


if($estatus == 0){
$sql_update = "UPDATE op_token_telegram SET token = '" . $token . "', fecha_creacion = NOW() WHERE id_usuario = '" . $idUsuario . "' AND estatus = 0";
if(mysqli_query($con, $sql_update)){
echo 1;

}else{
echo 0;  
}

}else{

$sql_insert = "INSERT INTO op_token_telegram (id_usuario,token,estatus) VALUES ('".$idUsuario."','".$token."',0)";
if(mysqli_query($con, $sql_insert)){
echo 1;

}else{
echo 0;  
}

}

    
}else{

$sql_insert = "INSERT INTO op_token_telegram (id_usuario,token,estatus) VALUES ('".$idUsuario."','".$token."',0)";
if(mysqli_query($con, $sql_insert)){
echo 1;

}else{
echo 0;  
}
  
}
