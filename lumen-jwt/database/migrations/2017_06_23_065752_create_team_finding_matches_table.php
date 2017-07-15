<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeamFindingMatchesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('team_finding_matches', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('player_id');
            $table->unsignedInteger('team_id')->nullable();
            $table->boolean('is_booked')->default(false);
            $table->string('stadium_name')->nullable();
            $table->unsignedInteger('level_id')->nullable(); 

            $table->string('address')->nullable();
            $table->unsignedInteger('ground_type_id')->nullable(); 
            $table->unsignedInteger('ground_id')->nullable(); 

            $table->string('time')->nullable();
            $table->string('phone_number')->nullable();
            $table->date('date')->nullable();
            $table->date('expired_date')->nullable();

            $table->string('message')->nullable();
            $table->string('fb_name')->nullable();
            $table->string('fb_page_to_find')->nullable();

            $table->foreign('player_id')
                ->references('id')
                ->on('players')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('team_id')
                ->references('id')->on('teams')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('level_id')
                ->references('id')->on('properties')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('ground_type_id')
                ->references('id')->on('properties')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('ground_id')
                ->references('id')->on('grounds')
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
        Schema::dropIfExists('team_finding_matches');
    }
}
