<?php
$componentes_url = parse_url($_SERVER["REQUEST_URI"]);
$ruta = $componentes_url['path'];
$partes_ruta = explode("/", $ruta);
$partes_ruta = array_filter($partes_ruta);
$partes_ruta = array_slice($partes_ruta, 0);
$ruta_elegida = '';

if ($partes_ruta[0] == 'departamento-sistemas') 
{
    if (count($partes_ruta) == 1)
    {
    $ruta_elegida = 'app/vistas/soporte/sistemas-index.php';
    }
    else if(count($partes_ruta) == 2)
    {

    switch ($partes_ruta[1]) {

        //----- SOPORTE -----//
	    case 'soporte':
        $ruta_elegida = 'app/vistas/soporte/sistemas-soporte.php';
        break;
        //-------------------//
        case 'home':
        $ruta_elegida = 'app/vistas/home/home-index.php';
        break;


        //----- CERRAR SESION DEL USUARIO -----//
	    case 'salir':
        $ruta_elegida = 'app/modelo/logout-usuarios.php';
        break;
    }

    }
    else if(count($partes_ruta) == 3)
    {
        //----- NUEVO REGISTRO -----//
        if ($partes_ruta[1] == 'nuevo-registro') {
        $GET_IdRegistro = $partes_ruta[2];
        $ruta_elegida = 'app/vistas/soporte/sistemas-nuevo-registro.php';
        }
        //----- DETALLE REGISTRO -----//
        else if ($partes_ruta[1] == 'detalle-registro') {
            $GET_IdRegistro = $partes_ruta[2];
            $ruta_elegida = 'app/vistas/soporte/sistemas-detalle-registro.php';
            }
        //----- EDITAR REGISTRO -----//
        else if ($partes_ruta[1] == 'editar-registro') {
            $GET_IdRegistro = $partes_ruta[2];
            $ruta_elegida = 'app/vistas/soporte/sistemas-editar-registro.php';
            }
    }
}

include_once $ruta_elegida;
