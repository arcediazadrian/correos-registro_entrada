<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Empleado extends Model
{
    public function user(){
        return $this->belongsTo('App\User');
    }

    public function area(){
        return $this->belongsTo('App\Area');
    }

    public function bitacora(){
        return $this->hasMany('App\BitacoraDeRegistro');
    }
}
