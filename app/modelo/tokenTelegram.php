<?php 
class Telegram extends Exception{
    private $apiToken = "bot7325883923:AAFWJIR-Jklk0cfs0ZsYpHY6tA_2fpbcZ7U";
    private $con;

    public function __construct($con)
    {
        $this->con = $con;
    }
    public function enviarToken($idUsuario,$mensaje): bool{

        $result = true;
        $chatId = 5429996294;
        //$chatId = $this->getChatId($idUsuario);

        $data = [
            'chat_id' => $chatId,
            'text' => $mensaje,
            'parse_mode' => 'MarkdownV2'
        ];
        
        // URL de la API para enviar el mensaje
        $url = "https://api.telegram.org/$this->apiToken/sendMessage";
        
        // Inicia una solicitud cURL para enviar el mensaje
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        // Ejecuta la solicitud y obtiene la respuesta
        curl_exec($ch);
        curl_close($ch);
        return $result;
    }
    // Se obtiene el chat id que esta dado de alta en BD
    private function getChatID(int $idUsuario): int{
    $GET_idChat = 0;
    $estatus = 1;

    $sql = "SELECT chat_id FROM op_token_telegram WHERE id_usuario = '" . $idUsuario . "' AND estatus = '" . $estatus . "' ORDER BY fecha_creacion DESC LIMIT 1";
    $result = mysqli_query($this->con, $sql);
    $numero_lista = mysqli_num_rows($result);
    
    if($numero_lista > 0){
    while ($row_lista = mysqli_fetch_array($result, MYSQLI_ASSOC)) {
    $GET_idChat = $row_lista['chat_id'];
    }

    }

    return $GET_idChat;
    }
}