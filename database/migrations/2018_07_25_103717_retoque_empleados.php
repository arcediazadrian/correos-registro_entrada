<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RetoqueEmpleados extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('empleados', function (Blueprint $table) {
            $table->integer('ciudad_id')->unsigned()->default(1);
            $table->integer('rango_id')->unsigned()->default(1);
    
            $table->foreign('ciudad_id')
            ->references('id')->on('ciudads');

            $table->foreign('rango_id')
            ->references('id')->on('rangos');
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
