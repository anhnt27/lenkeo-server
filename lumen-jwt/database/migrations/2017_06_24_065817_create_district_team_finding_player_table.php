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
            $table->unsignedInteger('team_finding_player_id');
            $table->unsignedInteger('district_id');

            $table->foreign('district_id')
                ->references('id')->on('districts')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');

            $table->foreign('team_finding_player_id')
                ->references('id')->on('team_finding_players')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
                
            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('district_team_finding_player');
    }
}
