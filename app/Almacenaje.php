<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Almacenaje extends Model
{
    public function registro(){
        return $this->belongsTo('App\Registro');
    }
}
