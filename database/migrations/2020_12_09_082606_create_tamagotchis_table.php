<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTamagotchisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tamagotchis', function (Blueprint $table) {
            $table->id();
            $table->string("name");
            $table->integer("age");
            $table->integer("coins");
            $table->integer("health");
            $table->integer("boredom");
            $table->integer("owner_id");
            $table->integer("hotel_room_id")->nullable();
            $table->boolean("dead");
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
        Schema::dropIfExists('tamagotchis');
    }
}
