<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Registro extends Model
{
    public function destiantario(){
        return $this->hasOne('App\Destinatario');
    }

    public function envio(){
        return $this->hasOne('App\Envio');
    }

    public function almacenaje(){
        return $this->hasOne('App\Almacenaje');
    }
    
    public function entrega(){
        return $this->hasOne('App\Entrega');
    }

    public function factura(){
        return $this->hasOne('App\Factura');
    }

    public function bitacoras(){
        return $this->hasMany('App\Bitacora');
    }

}
