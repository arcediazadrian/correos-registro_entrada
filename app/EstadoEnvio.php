<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoEnvio extends Model
{
    public function envios(){
        return $this->hasMany('App\Envio');
    }
}
