<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Destinatario extends Model
{
    public function registro(){
        return $this->belongsTo('App\Registro');
    }

    public function pais(){
        return $this->belongsTo('App\Pais');
    }

    public function ciudad(){
        return $this->belongsTo('App\Ciudad');
    }

    public function zona(){
        return $this->belongsTo('App\Zona');
    }
}
