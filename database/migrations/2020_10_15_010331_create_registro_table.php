<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registros', function (Blueprint $table) {
			$table->id();
            $table->unsignedBigInteger('becado_id');
            $table->unsignedBigInteger('diario_id');
            $table->dateTime('fecha_hora');
            $table->timestamps();

            // Relaciones
            $table->foreign('becado_id')->references('id')->on('becados');
            $table->foreign('diario_id')->references('id')->on('diarios');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registro');
    }
}
