<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFindingPlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_finding_players', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('team_id');
            $table->string('stadium_name');
            $table->string('note')->nullable();
            $table->unsignedInteger('type_id'); 
            $table->unsignedInteger('position_id'); 

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('team_finding_players', function ($table) {
            $table->foreign('team_id')
                ->references('id')->on('teams')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('type_id')
                ->references('id')->on('properties')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('position_id')
                ->references('id')->on('properties')
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
        Schema::create('team_finding_players');
    }
}
