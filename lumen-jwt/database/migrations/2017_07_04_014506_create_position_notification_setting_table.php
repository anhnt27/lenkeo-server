<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePositionNotificationSettingTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('position_notification_setting', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('notification_setting_id');
            $table->unsignedInteger('position_id');

            $table->foreign('position_id')
                ->references('id')->on('properties')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');

            $table->foreign('notification_setting_id')
                ->references('id')->on('notification_settings')
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
        Schema::dropIfExists('position_notification_setting');
    }
}
