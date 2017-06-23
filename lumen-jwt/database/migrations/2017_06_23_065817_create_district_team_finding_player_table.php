<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistrictTeamFindingPlayerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('district_team_finding_player', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('team_id');
            $table->unsignedInteger('district_id');

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('district_team_finding_player', function ($table) {
            $table->foreign('district_id')
                ->references('id')->on('districts')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');

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
        Schema::create('district_team_finding_player');
    }
}
