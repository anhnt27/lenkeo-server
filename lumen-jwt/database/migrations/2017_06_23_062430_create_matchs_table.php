<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_team', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('team_id');
            $table->string('stadium_name');
            $table->string('note')->nullable();
            $table->time('from');
            $table->time('to');
            $table->date('date');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('player_team', function ($table) {
            $table->foreign('team_id')
                ->references('id')->on('teams')
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
        Schema::drop('player_team')
    }
}
