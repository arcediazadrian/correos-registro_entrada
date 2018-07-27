<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RetoqueEnvios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('envios', function (Blueprint $table) {
            $table->integer('tipo_servicio_id')->unsigned()->default(1);
            
            $table->foreign('tipo_servicio_id')
            ->references('id')->on('tipo_servicios');

            $table->dateTime('fecha_hora_envio')->nullable()->change();
            $table->integer('pais_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('envios', function (Blueprint $table) {
            $table->dropForeign(['tipo_servicio_id']);
            $table->dropColumn('tipo_servicio_id');
        });
    }
}
