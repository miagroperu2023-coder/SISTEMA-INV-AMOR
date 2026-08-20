<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSizeSetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('size_sets', function (Blueprint $table) {
            $table->id();
            $table->enum('tipo', ['letra', 'numero']);
            $table->string('valor'); // XS, S, M, L, XL / 26, 28, 30...
            $table->integer('orden')->default(0); // para que no se desordenen al mostrarlas
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
        Schema::dropIfExists('size_sets');
    }
}
