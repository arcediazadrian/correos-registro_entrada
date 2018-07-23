<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Pais;
use App\Ciudad;
use App\Zona;
use App\TipoEnvio;
use App\TipoProducto;
use App\EstadoEnvio;

class ParametricasController extends Controller
{
    public function paises(){
        $paises = Pais::where('esActivo', 1);
        return $paises;
    }
    
    public function ciudades(){
        $ciudades = Ciudad::where('esActivo', 1);
        return $ciudades;
    }

    public function zonas(){
        $zonas = Zona::where('esActivo', 1);
        return $zonas;
    }

    public function tipos_envio(){
        $tipos_envio = TipoEnvio::where('esActivo', 1);
        return $tipos_envio;
    }

    public function tipos_producto(){
        $tipos_producto = TipoProducto::where('esActivo', 1);
        return $tipos_producto;
    }

    public function estados_envio(){
        $estados_envio = EstadoEnvio::where('esActivo', 1);
        return $estados_envio;
    }
}
