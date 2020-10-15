<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCalendarioTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('calendario', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger("id_becado");

            # Cada uno de los siguientes campos se usara para indicar la cantidad de raciones que consumira
            // un becado por dia y turno
            $table->integer("lunes_dia")->default(0);// Raciones que consumira los lunes al medio dia
            $table->integer("lunes_noche")->default(0);
            $table->integer("martes_dia")->default(0);
            $table->integer("martes_noche")->default(0);
            $table->integer("miercoles_dia")->default(0);
            $table->integer("miercoles_noche")->default(0);
            $table->integer("jueves_dia")->default(0);
            $table->integer("jueves_noche")->default(0);
            $table->integer("viernes_dia")->default(0);
            $table->integer("viernes_noche")->default(0);
            $table->integer("sabado_dia")->default(0);
            $table->integer("sabado_noche")->default(0);
            $table->integer("domingo_dia")->default(0);
            $table->integer("domingo_noche")->default(0);



            $table->timestamps();

            # Definir relacion con tabla becados
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('calendario');
    }
}
