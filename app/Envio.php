<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Envio extends Model
{
    public function registro(){
        return $this->belongsTo('App\Registro');
    }

    public function pais(){
        return $this->belongsTo('App\Pais');
    }

    public function tipo_envio(){
        return $this->belongsTo('App\TipoEnvio');
    }

    public function tipo_producto(){
        return $this->belongsTo('App\TipoProducto');
    }

    public function estado_envio(){
        return $this->belongsTo('App\EstadoEnvio');
    }
}
