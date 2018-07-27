<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RetoqueBitacoraDeRegistros extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('bitacora_de_registros', function (Blueprint $table) {
            $table->integer('ciudad_id')->unsigned()->default(1);;
    
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
        //
    }
}
