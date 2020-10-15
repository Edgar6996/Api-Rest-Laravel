<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetalleDiarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detalle_diarios', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('diario_id');
            $table->unsignedBigInteger('becado_id');
            $table->integer('raciones')->default(0);
            $table->integer('retirado')->default(0);
            $table->timestamps();

            $table->foreign('diario_id')->references('id')->on('diarios');
            $table->foreign('becado_id')->references('id')->on('becados');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('detalle_diario');
    }
}
