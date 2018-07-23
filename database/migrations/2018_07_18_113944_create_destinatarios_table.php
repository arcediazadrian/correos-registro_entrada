<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDestinatariosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('destinatarios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('apellido_paterno');
            $table->string('apellido_materno');
            $table->string('nombre');
            $table->string('telefono');
            $table->string('calle_avenida');
            $table->string('edificio_nro');
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
        Schema::dropIfExists('destinatarios');
    }
}
