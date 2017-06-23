<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStadiumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stadiums', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('district_id');
            $table->string('name');
            $table->string('phone_number')->nullable()->unique();
            $table->string('address')->nullable();
            $table->integer('number_of_ground')->nullable();
            $table->integer('price_per_hour')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('stadiums', function (Blueprint $table) {
            $table->foreign('district_id')
                ->references('id')
                ->on('districts')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
        Schema::drop('stadiums');
    }
}
