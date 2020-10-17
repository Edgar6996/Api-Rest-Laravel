<?php

use App\Enums\CategoriasBecados;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBecadosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('becados', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->bigInteger('dni')->unique();
            $table->string('nombres');
            $table->string('apellidos');
            $table->string('foto')->nullable();
            $table->string('email')->unique();
            $table->tinyInteger('estado')->default(\App\Enums\EstadoBecados::ACTIVO);
            $table->integer('categoria')->default(CategoriasBecados::BECADO);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('becados');
    }
}
