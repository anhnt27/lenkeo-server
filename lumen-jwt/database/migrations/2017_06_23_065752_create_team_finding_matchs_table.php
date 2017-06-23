<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFindingMatchsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_finding_matchs', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('team_id');
            $table->boolean('is_booked');
            $table->string('stadium_name');
            $table->string('note')->nullable();
            $table->unsignedInteger('level_id'); 
            $table->unsignedInteger('type_id'); 

            $table->timestamps();
            $table->softDeletes();
        });

        Schema::table('team_finding_matchs', function ($table) {
            $table->foreign('team_id')
                ->references('id')->on('teams')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('level_id')
                ->references('id')->on('properties')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('type_id')
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
        Schema::create('team_finding_matchs');
    }
}
