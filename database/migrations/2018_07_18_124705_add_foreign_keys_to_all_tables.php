<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddForeignKeysToAllTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('almacenajes', function (Blueprint $table) {
            $table->integer('registro_id')->unsigned();

            $table->foreign('registro_id')
            ->references('id')->on('registros')
            ->onDelete('cascade');
        });

        Schema::table('destinatarios', function (Blueprint $table) {
            $table->integer('registro_id')->unsigned();

            $table->foreign('registro_id')
            ->references('id')->on('registros')
            ->onDelete('cascade');

            $table->integer('pais_id')->unsigned();
            $table->integer('ciudad_id')->unsigned();
            $table->integer('zona_id')->unsigned();
            
            $table->foreign('pais_id')
            ->references('id')->on('pais');

            $table->foreign('ciudad_id')
            ->references('id')->on('ciudads');

            $table->foreign('zona_id')
            ->references('id')->on('zonas');
        });

        Schema::table('entregas', function (Blueprint $table) {
            $table->integer('registro_id')->unsigned();

            $table->foreign('registro_id')
            ->references('id')->on('registros')
            ->onDelete('cascade');

            $table->integer('estado_entrega_id')->unsigned();

            $table->foreign('estado_entrega_id')
            ->references('id')->on('estado_entregas');
        });

        Schema::table('envios', function (Blueprint $table) {
            $table->integer('registro_id')->unsigned();

            $table->foreign('registro_id')
            ->references('id')->on('registros')
            ->onDelete('cascade');

            $table->integer('pais_id')->unsigned();
            $table->integer('tipo_envio_id')->unsigned();
            $table->integer('tipo_producto_id')->unsigned();
            $table->integer('estado_envio_id')->unsigned();

            $table->foreign('pais_id')
            ->references('id')->on('pais');

            $table->foreign('tipo_envio_id')
            ->references('id')->on('tipo_envios');

            $table->foreign('tipo_producto_id')
            ->references('id')->on('tipo_productos');

            $table->foreign('estado_envio_id')
            ->references('id')->on('estado_envios');
        });

        Schema::table('facturas', function (Blueprint $table) {
            $table->integer('registro_id')->unsigned();

            $table->foreign('registro_id')
            ->references('id')->on('registros')
            ->onDelete('cascade');
        });

        Schema::table('ciudads', function (Blueprint $table) {
            $table->integer('pais_id')->unsigned();

            $table->foreign('pais_id')
            ->references('id')->on('pais');
        });

        Schema::table('zonas', function (Blueprint $table) {
            $table->integer('ciudad_id')->unsigned();

            $table->foreign('ciudad_id')
            ->references('id')->on('ciudads');
        });



    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('almacenajes', function (Blueprint $table) {
            $table->dropForeign(['registro_id']);
        });
        
        Schema::table('destinatarios', function (Blueprint $table) {
            $table->dropForeign(['registro_id']);
            $table->dropForeign(['pais_id']);
            $table->dropForeign(['ciudad_id']);
            $table->dropForeign(['zona_id']);
        });

        Schema::table('entregas', function (Blueprint $table) {
            $table->dropForeign(['registro_id']);
            $table->dropForeign(['estado_entrega_id']);
        });

        Schema::table('envios', function (Blueprint $table) {
            $table->dropForeign(['registro_id']);
        });

        Schema::table('facturas', function (Blueprint $table) {
            $table->dropForeign(['registro_id']);
            $table->dropForeign(['pais_id']);
            $table->dropForeign(['tipo_envio_id']);
            $table->dropForeign(['tipo_producto_id']);
            $table->dropForeign(['estado_envio_id']);
        });
    }
}
