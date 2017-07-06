<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePlayersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('players', function (Blueprint $table) {
            $table->increments('id');

            $table->string('name');
            $table->string('email')->unique();
            $table->string('phone_number')->nullable()->unique();
            $table->string('registration_id')->nullable()->unique();

            $table->boolean('is_admin')->default(false);
            $table->boolean('is_team_lead')->default(false);
            $table->boolean('is_finding_team')->default(false);

            //default value;
            $table->unsignedInteger('level_id')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->unsignedInteger('district_id')->nullable();

            $table->timestamp('created_at')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->timestamp('updated_at')->default(DB::raw('CURRENT_TIMESTAMP on update CURRENT_TIMESTAMP'));
            
            $table->softDeletes();

            $table->foreign('district_id')
                ->references('id')
                ->on('districts')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('city_id')
                ->references('id')
                ->on('cities')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('level_id')
                ->references('id')
                ->on('properties')
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
        Schema::dropIfExists('players');
    }
}
