<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DropTipoEnvios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('envios', function (Blueprint $table) {
            $table->dropForeign(['tipo_envio_id']);
            $table->dropColumn('tipo_envio_id');
        });
        
        Schema::dropIfExists('tipo_envios');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('tipo_envios', function (Blueprint $table) {
            $table->increments('id');
            $table->string('tipo_envio');
            $table->boolean('esActivo');
            $table->timestamps();
        });
    }
}
