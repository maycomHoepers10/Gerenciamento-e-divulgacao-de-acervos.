<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMuseumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('museums', function (Blueprint $table) {
            $table->increments('cdmuseum');
            $table->string('nmmuseum', 255);
            $table->text('dsmuseum');
            $table->string('nmphone', 255);
            $table->string('nmemail', 50);
            $table->date('dtfundation');
            $table->string('nmstate', 255);
            $table->string('nmcity', 255);
            $table->string('nmneighborhood', 255);
            $table->string('nmaddress', 255);
            $table->integer('ninumberaddress');
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
        Schema::dropIfExists('museums');
    }
}
