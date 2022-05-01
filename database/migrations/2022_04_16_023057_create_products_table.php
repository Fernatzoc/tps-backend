<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('proveedores', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->string('telefono');
            $table->string('direccion');
            $table->string('correo');
            $table->timestamps();
        });

        Schema::create('categorias', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->timestamps();
        });

        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->integer('stock');
            $table->foreignId('id_proveedor')->references('id')->on('proveedores');
            $table->foreignId('id_categoria')->references('id')->on('categorias');
            $table->timestamps();
        });

        Schema::create('transactions', function (Blueprint $table) {
          $table->id();
          $table->foreignId('id_producto')->references('id')->on('products');
          $table->date('fecha');
          $table->string('movimiento');
          $table->integer('cantidad');
          $table->float('costo_unitario');
          $table->float('total');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('proveedores');
        Schema::enableForeignKeyConstraints();

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('categorias');
        Schema::enableForeignKeyConstraints();

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('products');
        Schema::enableForeignKeyConstraints();

        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('entries');
        Schema::enableForeignKeyConstraints();

    }
};
