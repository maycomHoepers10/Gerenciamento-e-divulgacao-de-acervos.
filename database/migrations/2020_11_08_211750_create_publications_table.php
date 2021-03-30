<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePublicationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('publications', function (Blueprint $table) {
            $table->increments('cdpublication');
            $table->integer('setwitter')->nullable();
            $table->integer('sefacebook')->nullable();
            $table->integer('cdcollection');
            $table->integer('cditem');
            $table->string('nmimages')->nullable();
            $table->string('idtwitterpost')->nullable();
            $table->string('idfacebookpost')->nullable();
            $table->text('dspublication');
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
        Schema::dropIfExists('publications');
    }
}
