<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Entrega extends Model
{
    public function registro(){
        return $this->belongsTo('App\Registro');
    }

    public function estado_entrega(){
        return $this->belongsTo('App\EstadoEntrega');
    }
}
