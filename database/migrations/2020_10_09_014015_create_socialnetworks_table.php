<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSocialnetworksTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('socialnetworks', function (Blueprint $table) {
            $table->increments('cdsocialnetwork');
            $table->text('nmtoken1');
            $table->text('nmtoken2');
            $table->text('nmtoken3');
            $table->text('nmtoken4');
            $table->integer('setype');// 1 - twitter / 2 - facebook
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
        Schema::dropIfExists('socialnetworks');
    }
}
