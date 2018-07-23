<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class BitacoraDeRegistro extends Model
{
    public function registro(){
        return $this->belongsTo('App\Registro');
    }

    public function empleado(){
        return $this->belongsTo('App\Empleado');
    }

    public function estado_bitacora(){
        return $this->belongsTo('App\EstadoBitacora');
    }
}
