<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateAppConfigTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('app_config', function (Blueprint $table) {

            $table->float('limite_horas_cancelar_reserva')->default(2);
            $table->integer("max_faltas")->default(3);
            $table->integer("castigo_duracion_dias")->default(10);

            $table->time("hora_cena")->default("20:00:00");
            $table->time("hora_almuerzo")->default("12:00:00");

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
