<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoEntrega extends Model
{
    public function entregas(){
        return $this->hasMany('App\Entrega');
    }
}
