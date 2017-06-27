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
            $table->unsignedInteger('ground_type_id')->nullable(); 
            $table->unsignedInteger('position_id')->nullable(); 

            $table->string('time')->nullable();
            $table->string('phone_number')->nullable();
            $table->date('date')->nullable();

            $table->boolean('is_finding_team')->default(false);
            $table->string('message')->nullable();
            $table->string('fb_name')->nullable();
            $table->string('fb_page_to_find')->nullable();

            $table->foreign('player_id')
                ->references('id')->on('players')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('ground_type_id')
                ->references('id')->on('properties')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('position_id')
                ->references('id')->on('properties')
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
        Schema::create('player_finding_matchs');
    }
}
