<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeyToTarifarioEntregas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tarifario_entregas', function (Blueprint $table) {
            $table->integer('tipo_servicio_id')->unsigned();

            $table->foreign('tipo_servicio_id')
            ->references('id')->on('tipo_servicios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('tarifario_entregas', function (Blueprint $table) {
            $table->dropForeign(['tipo_servicio_id']);
            $table->dropColumn('tipo_servicio_id');
        });
    }
}
