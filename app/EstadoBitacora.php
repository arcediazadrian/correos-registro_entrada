<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class EstadoBitacora extends Model
{
    public function bitacoras(){
        return $this->hasMany('App\Bitacora');
    }
}
