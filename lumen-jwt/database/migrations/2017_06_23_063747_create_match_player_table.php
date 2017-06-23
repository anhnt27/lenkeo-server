<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMatchPlayerTable extends Migration
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
            $table->unsignedInteger('match_id');
            $table->unsignedInteger('player_id');
            $table->tinyInteger('confirm_status')->nullable();

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('player_team', function ($table) {
            $table->foreign('player_id')
                ->references('id')->on('players')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');

            $table->foreign('match_id')
                ->references('id')->on('matchs')
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
        Schema::create('match_player');
    }
}
