<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateJoinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('joins', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('player_id');
            $table->unsignedInteger('team_id');

            $table->tinyInteger('status');
            $table->tinyInteger('type');

            $table->foreign('player_id')
                ->references('id')->on('players')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');

            $table->foreign('team_id')
                ->references('id')->on('teams')
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
        Schema::drop('joins');
    }
}
