<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification_settings', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('player_id');
            $table->tinyInteger('type'); // 
                // 1: team finding match  / filter: district,  level
                // 2: team finding player / filter: district, position, level
                // 3: player finding match / filter: district, position, level
                // 4: team finding member / filter: district, position, level
                // 5: player finding team / filter: district, position, level
            $table->unsignedInteger('city_id')->nullable();
            
            

            $table->foreign('player_id')
                ->references('id')->on('players')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
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
        Schema::dropIfExists('notification_settings');
    }
}
