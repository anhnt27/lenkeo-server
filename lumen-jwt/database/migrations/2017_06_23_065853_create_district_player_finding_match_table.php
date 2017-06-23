<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictPlayerFindingMatchTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('district_player_finding_player', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('player_id');
            $table->unsignedInteger('district_id');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('district_player_finding_player', function ($table) {
            $table->foreign('district_id')
                ->references('id')->on('districts')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');

            $table->foreign('player_id')
                ->references('id')->on('players')
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
        Schema::create('district_player_finding_player');
    }
}
