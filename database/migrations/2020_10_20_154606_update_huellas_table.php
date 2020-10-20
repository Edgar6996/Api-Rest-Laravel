<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateHuellasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('huellas', function (Blueprint $table) {

            $table->binary('template_huella')->nullable()
                ->default(null)
                ->change();
            $table->binary('img_huella')->nullable()
                ->default(null)
                ->change();
        });
        DB::statement("ALTER TABLE huellas CHANGE COLUMN template_huella template_huella MEDIUMBLOB DEFAULT NULL;");
        DB::statement("ALTER TABLE huellas CHANGE COLUMN img_huella img_huella LONGBLOB DEFAULT NULL;");
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
