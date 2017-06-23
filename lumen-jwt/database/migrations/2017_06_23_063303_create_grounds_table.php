<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grounds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stadium_id');
            $table->unsignedInteger('ground_type_id');
            $table->string('name');
            $table->integer('price_per_hour')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::create('grounds', function (Blueprint $table) {
            $table->foreign('stadium_id')
                ->references('id')
                ->on('stadiums')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('ground_type_id')
                ->references('id')
                ->on('properties')
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
        Schema::create('grounds');
    }
}
