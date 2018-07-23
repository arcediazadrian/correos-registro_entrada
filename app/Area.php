<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Area extends Model
{
    public function empleados(){
        return $this->hasMany('App\Empleado');
    }
}
