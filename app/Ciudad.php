<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Ciudad extends Model
{
    public function pais(){
        return $this->belongsTo('App\Pais');
    }

    public function zonas(){
        return $this->hasMany('App\Zona');
    }

    public function destinatarios(){
        return $this->hasMany('App\Destinatario');
    }
}
