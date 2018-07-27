<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RetoqueFacturas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->boolean('esManual');
            $table->integer('nro_factura')->unsigned()->nullable();
            $table->integer('nit_factura')->unsigned()->nullable()->change();
            $table->string('nombre_factura')->nullable()->change();
            $table->integer('monto_factura')->unsigned()->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('facturas', function (Blueprint $table) {
            $table->dropColumn('esManual');
            $table->dropColumn('nro_factura');
        });
    }
}
