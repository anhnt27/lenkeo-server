<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayerFindingMatchsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('player_finding_matchs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('player_id');
            $table->string('note')->nullable();
            $table->unsignedInteger('type_id'); 
            $table->unsignedInteger('position_id'); 

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('player_finding_matchs', function ($table) {
            $table->foreign('player_id')
                ->references('id')->on('players')
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
        Schema::create('player_finding_matchs');
    }
}
