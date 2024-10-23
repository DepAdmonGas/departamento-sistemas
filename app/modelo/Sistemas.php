<?php
include_once "tokenTelegram.php";
class Sistemas
{

  private $ClassConexionBD;
  private $con;
  private $telegram;

  public function __construct()
  {
    $this->ClassConexionBD = new ConexionBD();
    $this->con = $this->ClassConexionBD->conectarBD();
    $this->telegram = new Telegram($this->con);
  }

  public function NuevoTicket($idUsuario, $categoria)
  {
    $IdTicket = $this->IdTicket();

    $sql_insert = "INSERT INTO ds_soporte (
        id_ticket, 
        id_personal,
        descripcion,
        prioridad,
        fecha_creacion,
        fecha_inicio,        
        fecha_termino,
        tiempo_solucion,
        fecha_termino_real,
        porcentaje,
        categoria,
        id_personal_soporte,
        estado
            )
            VALUES 
            (
            '" . $IdTicket . "',
            '" . $idUsuario . "',
            '',
            '',
            '',
            '',
            '',
            '',
            '',
            0,
            '" . $categoria . "',
            0,
            0
            )";

    if (mysqli_query($this->con, $sql_insert)) {
      $Resultado = $IdTicket;
    } else {
      $Resultado = 0;
    }

    return $Resultado;
  }

  public function IdTicket()
  {

    $sql = "SELECT id_ticket FROM ds_soporte ORDER BY id_ticket DESC LIMIT 1";
    $result = mysqli_query($this->con, $sql);
    $numero = mysqli_num_rows($result);
    if ($numero > 0) {
      while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
        $Resultado = $row['id_ticket'] + 1;
      }
    } else {
      $Resultado = 1;
    }

    return $Resultado;
  }

  //---------- EDITAR DESCRIPCION ---------//
  public function EditarDescripcion($idRegistro, $Dato)
  {

    $sql = "UPDATE ds_soporte SET 
        descripcion = '" . $Dato . "'
        WHERE id_ticket='" . $idRegistro . "' ";

    if (mysqli_query($this->con, $sql)) {
      $Resultado = 1;
    } else {
      $Resultado = 0;
    }

    return $Resultado;
  }
  //--------- EDITAR PRIORIDAD ---------//

  public function EditarPrioridad($idRegistro, $Dato)
  {

    $sql = "UPDATE ds_soporte SET 
        prioridad = '" . $Dato . "'
        WHERE id_ticket='" . $idRegistro . "' ";

    if (mysqli_query($this->con, $sql)) {
      $Resultado = 1;
    } else {
      $Resultado = 0;
    }

    return $Resultado;
  }
  //------------ AGREGAR ACTIVIDAD ------------//

  public function AgregarActividad($idRegistro, $Descripcion, $Name, $Temporal)
  {
    $aleatorio = uniqid();
    $extension = pathinfo($Name, PATHINFO_EXTENSION);
    $UpDoc = "../../assets/archivos/" . "Actividad" . date("Y") . date("m") . date("d") . "-" . $aleatorio . "." . $extension;
    if (move_uploaded_file($Temporal, $UpDoc)) {
      $Archivo = "Actividad" . date("Y") . date("m") . date("d") . "-" . $aleatorio . "." . $extension;
    } else {
      $Archivo = "";
    }

    $sqlInsert1 = "INSERT INTO ds_soporte_actividades (
            id_ticket,
            descripcion,
            archivo,
            fecha_inicio,
            fecha_termino,
            estado
                )
                VALUES 
                (
                '" . $idRegistro . "',
                '" . $Descripcion . "',
                '" . $Archivo . "',
                '',
                '',
                0
                )";

    if (mysqli_query($this->con, $sqlInsert1)) {
      $Resultado = 1;
    } else {
      $Resultado = 0;
    }

    return $Resultado;
  }

  //-------------- AGREGAR EVIDENCIA -----------//

  public function AgregarEvidencia($idRegistro, $Descripcion, $Name, $Temporal)
  {
    $aleatorio = uniqid();
    $extension = pathinfo($Name, PATHINFO_EXTENSION);
    $Archivo = "Evidencia" . date("Y") . date("m") . date("d") . "-" . $aleatorio . "." . $extension;
    $UpDoc = "../../assets/archivos/" . "Evidencia" . date("Y") . date("m") . date("d") . "-" . $aleatorio . "." . $extension;
    if (move_uploaded_file($Temporal, $UpDoc)) {
    }

    $sqlInsert1 = "INSERT INTO ds_soporte_evidencia (
            id_ticket,
            descripcion,
            evidencia
                )
                VALUES 
                (
                '" . $idRegistro . "',
                '" . $Descripcion . "',
                '" . $Archivo . "'
                )";

    if (mysqli_query($this->con, $sqlInsert1)) {
      $Resultado = 1;
    } else {
      $Resultado = 0;
    }

    return $Resultado;
  }
  //---------------- ELIMINAR ACTIVIDAD ------------------//

  public function EliminarActividad($idActividad)
  {

    $sql = "DELETE FROM ds_soporte_actividades WHERE id = '" . $idActividad . "' ";

    if (mysqli_query($this->con, $sql)) {
      $Resultado = 1;
    } else {
      $Resultado = 0;
    }

    return $Resultado;
  }

  //---------------- ELIMINAR EVIDENCIA ------------------//

  public function EliminarEvidencia($idActividad)
  {

    $sql = "DELETE FROM ds_soporte_evidencia WHERE id = '" . $idActividad . "' ";

    if (mysqli_query($this->con, $sql)) {
      $Resultado = 1;
    } else {
      $Resultado = 0;
    }

    return $Resultado;
  }

  //------------ FINALIZAR REGISTRO --------------//

  public function FinalizarRegistro($idRegistro, $idticket, $usuario)
  {

    date_default_timezone_set('America/Mexico_City');
    $hoy = date("Y-m-d H:i:s");
    $mensaje = "Se envia la solitud de sistemas con numero de ticket: $idticket";
    $sql = "UPDATE ds_soporte SET 
        fecha_creacion = '" . $hoy . "',
        estado = 1
        WHERE id_ticket='" . $idRegistro . "' ";

    if (mysqli_query($this->con, $sql)) {
      $this->PersonalSistemas($idRegistro, 1);
      $this->telegram->enviarToken($usuario, $mensaje);
      $Resultado = 1;
    } else {
      $Resultado = 0;
    }

    return $Resultado;
  }

  public function PersonalSistemas($idRegistro, $opcion)
  {

    if ($opcion == 1) {
      $detalle = 'Tienes un nuevo ticket pendiente por atender #0' . $idRegistro;
    } else if ($opcion == 2) {
      $detalle = 'Tienes un nuevo comentario en Soporte de Sistemas en el Ticket #0' . $idRegistro;
    }

    $sql = "SELECT 
        tb_usuarios.id,
        tb_usuarios_token.id AS idToken,
        tb_usuarios_token.token
        FROM tb_usuarios
        INNER JOIN tb_usuarios_token 
        ON tb_usuarios.id = tb_usuarios_token.id_usuario WHERE tb_usuarios.id_puesto = 2 AND tb_usuarios_token.herramienta = 'token-web' ";
    $result = mysqli_query($this->con, $sql);
    $numero = mysqli_num_rows($result);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      $token = $row['token'];
      $this->sendNotification($token, $detalle);
    }

    return true;
  }

  public function sendNotification($token, $detalle)
  {
    $url = "https://fcm.googleapis.com/fcm/send";

    $fields = array(

      "to" => $token,

      "notification" => array(

        "body" => $detalle,
        "title" => "Portal AdmonGas",
        "icon" => "",
        "click_action" => ""
      )
    );

    $headers = array(
      'Authorization: key=AAAAccs8Ry4:APA91bFc3rlPHpHHyABA01dZPc4J9ZChulB2nmBZp0VW5ODR-uDq2Lnz0YvlpROjZrFgIl2UBFHqOPhPM8c5ho-8IR6XuFpwv8_WT_Y-av9vXav4_6eGsZrUdtrMl9GwDWDNZee0Ppli',
      'Content-Type:application/json'
    );

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
    $result = curl_exec($ch);

    curl_close($ch);
  }

  //---------- CANCELAR TICKET ----------//

  public function CancelarTicket($idticket)
  {

    $sql = "UPDATE ds_soporte SET 
        estado = 4
        WHERE id_ticket='" . $idticket . "' ";

    if (mysqli_query($this->con, $sql)) {
      $Resultado = 1;
    } else {
      $Resultado = 0;
    }

    return $Resultado;
  }

  //--------- CREAR COMENTARIO --------//

  public function GuardarComentario($idticket, $comentario, $idPersonal, $opcion)
  {
    $detalle = 'Tienes un nuevo comentario en Soporte de Sistemas en el Ticket #0' . $idticket;
    $sql = "INSERT INTO ds_soporte_comentarios (
            id_ticket,
            id_personal,
            comentario
                )
                VALUES 
                (
                '" . $idticket . "',
                '" . $idPersonal . "',
                '" . $comentario . "'
                )";

    if (mysqli_query($this->con, $sql)) {

      if ($opcion == 1) {

        $this->PersonalSistemas($idticket, 2);
      } else if ($opcion == 2) {

        $ClassContenido = new SistemasContenido();
        $SoporteContenido = $ClassContenido->soporteContenido($idticket);
        $idSolicitante = $SoporteContenido['idSolicitante'];
        $token = $this->TokenPersonal($idSolicitante);
        $this->sendNotification($token, $detalle);
      }

      $Resultado = 1;
    } else {
      $Resultado = 0;
    }

    return $Resultado;
  }

  //------------ TOKEN PERSONAL ----------------//

  public function TokenPersonal($idSolicitante)
  {

    $sql = "SELECT 
        tb_usuarios.id,
        tb_usuarios_token.id AS idToken,
        tb_usuarios_token.token
        FROM tb_usuarios
        INNER JOIN tb_usuarios_token 
        ON tb_usuarios.id = tb_usuarios_token.id_usuario 
        WHERE tb_usuarios.id = '" . $idSolicitante . "' AND tb_usuarios_token.herramienta = 'token-web' 
        ORDER BY tb_usuarios_token.id DESC LIMIT 1 ";
    $result = mysqli_query($this->con, $sql);
    $numero = mysqli_num_rows($result);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      $token = $row['token'];
    }

    return $token;
  }

  //----------- EDITAR REGISTRO  --------//

  public function EditarRegistro($idticket, $Detalle, $opcion)
  {

    date_default_timezone_set('America/Mexico_City');
    $hoy = date("Y-m-d H:i:s");
    $hora_del_dia = date("H:i:s");

    if ($opcion == 1) {

      if ($Detalle == 1) {
        $Query = 'estado = ' . $Detalle;
      } else if ($Detalle == 2) {
        $Query = "fecha_inicio = '" . $hoy . "', estado = '" . $Detalle . "' ";
      }
    } else if ($opcion == 2) {
      $Query = 'fecha_inicio = "' . $Detalle . ' ' . $hora_del_dia . '" ';
    } else if ($opcion == 3) {
      $Query = 'fecha_termino = "' . $Detalle . ' ' . $hora_del_dia . '" ';
    } else if ($opcion == 4) {
      $Query = 'id_personal_soporte = ' . $Detalle;
    } else if ($opcion == 5) {
      $Query = 'porcentaje = ' . $Detalle;
    }

    $sql = "UPDATE ds_soporte SET 
        $Query 
        WHERE id_ticket = '" . $idticket . "' ";

    if (mysqli_query($this->con, $sql)) {
      $Resultado = 1;
    } else {
      $Resultado = 0;
    }

    return $Resultado;
  }

  //------------------- EDITAR ACTIVIDAD --------------//

  public function EditarActividad($idActividad, $Detalle, $opcion)
  {

    if ($opcion == 1) {
      $Query = 'fecha_inicio = "' . $Detalle . '"';
    } else if ($opcion == 2) {
      $Query = 'fecha_termino = "' . $Detalle . '"';
    } else if ($opcion == 3) {
      $Query = 'estado = "' . $Detalle . '"';
    }

    $sql = "UPDATE ds_soporte_actividades SET 
        $Query
        WHERE id = '" . $idActividad . "' ";

    if (mysqli_query($this->con, $sql)) {
      $Resultado = 1;
    } else {
      $Resultado = 0;
    }

    return $Resultado;
  }

  //------------ FINALIZAR SOPORTE ------------------//

  public function FinalizarSoporte($idticket, $idPersonal)
  {
    $ClassContenido = new SistemasContenido();
    $SoporteContenido = $ClassContenido->soporteContenido($idticket);
    $estado = $SoporteContenido['estado'];
    $comentario = 'Se finalizo el soporte por el área de sistemas, recuerda que tienes tres días para darle seguimiento antes de que se cierre el ticket.';

    date_default_timezone_set('America/Mexico_City');
    $hoy = date("Y-m-d H:i:s");

    if ($estado == 1) {

      $sql = "UPDATE ds_soporte SET 
                fecha_inicio = '" . $hoy . "',
                fecha_termino = '" . $hoy . "',
                fecha_termino_real = '" . $hoy . "',
                porcentaje = 100,
                id_personal_soporte = '" . $idPersonal . "',
                estado = 3              
                WHERE id_ticket = '" . $idticket . "' ";

      $sql2 = "UPDATE ds_soporte_actividades SET 
                estado = 2              
                WHERE id_ticket = '" . $idticket . "' ";

      if (mysqli_query($this->con, $sql)) {
        mysqli_query($this->con, $sql2);
        $ResultComentario = $this->GuardarComentario($idticket, $comentario, $idPersonal, 2);
        $Resultado = 1;
      } else {
        $Resultado = 0;
      }
    } else if ($estado == 2) {

      $sql = "UPDATE ds_soporte SET 
                fecha_termino_real = '" . $hoy . "',
                porcentaje = 100,
                id_personal_soporte = '" . $idPersonal . "',
                estado = 3              
                WHERE id_ticket = '" . $idticket . "' ";

      $sql2 = "UPDATE ds_soporte_actividades SET 
                estado = 2              
                WHERE id_ticket = '" . $idticket . "' ";

      if (mysqli_query($this->con, $sql)) {
        mysqli_query($this->con, $sql2);
        $ResultComentario = $this->GuardarComentario($idticket, $comentario, $idPersonal, 2);
        $Resultado = 1;
      } else {
        $Resultado = 0;
      }
    }

    return $Resultado;
  }
  public function asignarPersonal($idticket): int
  {
    $mensaje = "Se envia la solitud de sistemas con numero de ticket: $idticket";
    $sql = "SELECT id_personal_soporte FROM ds_soporte WHERE id_ticket='" . $idticket . "' ";

    $result = mysqli_query($this->con, $sql);
    while ($row = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
      $this->telegram->enviarToken($row['id_personal_soporte'], $mensaje);
    }
    $Resultado = 1;
    return $Resultado;
  }
}
