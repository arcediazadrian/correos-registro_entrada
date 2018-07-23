<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Pais extends Model
{
    public function ciudades(){
        return $this->hasMany('App\Ciudad');
    }

    public function destinatarios(){
        return $this->hasMany('App\Destinatario');
    }

    public function envio(){
        return $this->hasMany('App\Envio');
    }
}
