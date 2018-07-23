<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAlmacenajesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('almacenajes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('nro_paquete');
            $table->string('ubicacion');
            $table->integer('columna');
            $table->string('fila');
            $table->string('bandeja');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('almacenajes');
    }
}
