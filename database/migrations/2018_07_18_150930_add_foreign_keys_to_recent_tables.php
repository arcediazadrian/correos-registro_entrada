<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToRecentTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->integer('user_id')->unsigned();
            $table->integer('area_id')->unsigned();

            $table->foreign('user_id')
            ->references('id')->on('users')
            ->onDelete('cascade');

            $table->foreign('area_id')
            ->references('id')->on('areas');
        });

        Schema::table('bitacora_de_registros', function (Blueprint $table) {
            $table->integer('empleado_id')->unsigned();
            $table->integer('registro_id')->unsigned();
            $table->integer('estado_bitacora_id')->unsigned();

            $table->foreign('empleado_id')
            ->references('id')->on('empleados');

            $table->foreign('registro_id')
            ->references('id')->on('registros')
            ->onDelete('cascade');

            $table->foreign('estado_bitacora_id')
            ->references('id')->on('estado_bitacoras');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->dropForeign(['user_id']);
            $table->dropForeign(['area_id']);
            $table->dropColumn('user_id');
            $table->dropColumn('area_id');
        });

        Schema::table('bitacora_de_registros', function (Blueprint $table) {
            $table->dropForeign(['empleado_id']);
            $table->dropForeign(['registro_id']);
            $table->dropForeign(['estado_bitacora_id']);

            $table->dropColumn('empleado_id');
            $table->dropColumn('registro_id');
            $table->dropColumn('estado_bitacora_id');
            
        });
    }
}
