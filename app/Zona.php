<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Zona extends Model
{
    public function ciudad(){
        return $this->belongsTo('App\Ciudad');
    }

    public function destinatarios(){
        return $this->hasMany('App\Destinatario');
    }
}
