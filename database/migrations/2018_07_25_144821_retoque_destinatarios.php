<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RetoqueDestinatarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('destinatarios', function (Blueprint $table) {
            $table->string('apellido_paterno')->nullable()->change();
            $table->string('apellido_materno')->nullable()->change();
            $table->string('nombre')->nullable()->change();
            $table->string('telefono')->nullable()->change();
            $table->string('calle_avenida')->nullable()->change();
            $table->string('edificio_nro')->nullable()->change();
            $table->dropForeign(['zona_id']);
            $table->integer('zona_id')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
