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

        case 'sistemas':
        $ruta_elegida = 'app/vistas/soporte/sistemas-index.php';
        break;
        case 'home':
        $ruta_elegida = 'app/vistas/home/home-index.php';
        break;
        case 'estaciones':
        $ruta_elegida = 'app/vistas/estaciones/index.php';
        break;
        case 'puestos':
        $ruta_elegida = 'app/vistas/area-departamento-puesto/puesto-index.php';
        break;
        case 'personal':
        $ruta_elegida = 'app/vistas/personal/index.php';
        break;
        case 'corte-diario':
        $ruta_elegida = 'app/vistas/corporativo/corte-diario-index.php';
        break;
        case 'cursos':
        $ruta_elegida = 'app/vistas/cursos/cursos-index.php';
        break;
        case 'solicitud-cheque':
        $ruta_elegida = 'app/vistas/solicitud-cheque/index.php';
        break;
        case 'solicitud-vale':
        $ruta_elegida = 'app/vistas/solicitud-vale/index.php';
        break;


        //----- SOPORTE -----//
	    case 'soporte':
        $ruta_elegida = 'app/vistas/soporte/sistemas-soporte.php';
        break;
        //-------------------//
        //----- ACTIVIDADES -----//
	    case 'actividades':
        $ruta_elegida = 'app/vistas/actividades/index.php';
        break;


        //-------------------//
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
         //----- NUEVA ACTIVIDAD -----//
         if ($partes_ruta[1] == 'nueva-actividad') {
            $GET_IdRegistro = $partes_ruta[2];
            $ruta_elegida = 'app/vistas/actividades/nueva-actividad.php';
            }
        //----- NUEVA ACTIVIDAD -----//
        else if ($partes_ruta[1] == 'detalle-registro') {
            $GET_IdRegistro = $partes_ruta[2];
            $ruta_elegida = 'app/vistas/soporte/sistemas-detalle-registro.php';
            }
        //----- EDITAR REGISTRO -----//
        else if ($partes_ruta[1] == 'editar-registro') {
            $GET_IdRegistro = $partes_ruta[2];
            $ruta_elegida = 'app/vistas/soporte/sistemas-editar-registro.php';
            }
        // DEATLLE ACTIVIDAD
        else if ($partes_ruta[1] == 'detalle-actividad') {
            $GET_IdRegistro = $partes_ruta[2];
            $ruta_elegida = 'app/vistas/actividades/detalle-actividad.php';
            }
        else if($partes_ruta[1] == 'cursos-cuestionario'){
            $GET_IdTema = $partes_ruta[2];
            $ruta_elegida = 'app/vistas/cursos/cuestionario.php';
        }
        
    }
}

include_once $ruta_elegida;
