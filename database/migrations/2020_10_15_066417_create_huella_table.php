<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateHuellaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('huellas', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('becado_id');
            $table->binary('template_huella');
            $table->integer('size_template');
            $table->binary('img_huella');
            $table->integer('img_width');
            $table->integer('img_height');

            $table->timestamps();

            $table->foreign('becado_id')->references('id')->on('becados');
        });

        DB::statement("ALTER TABLE huellas CHANGE COLUMN template_huella template_huella MEDIUMBLOB NOT NULL;");
        DB::statement("ALTER TABLE huellas CHANGE COLUMN img_huella img_huella LONGBLOB NOT NULL;");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('huella');
    }
}
