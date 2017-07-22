<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStadiumsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stadiums', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('district_id');
            $table->string('name');
            $table->string('phone_number')->nullable()->unique();
            $table->string('address')->nullable();
            $table->integer('number_of_ground')->nullable();
            $table->integer('price_per_hour')->nullable();

            $table->unsignedInteger('ground_type_id')->nullable();

            $table->string('map_image_link')->nullable();
            
            $table->string('introduce_image_link_1')->nullable();
            $table->string('introduce_image_link_2')->nullable();
            $table->string('introduce_image_link_3')->nullable();


            $table->foreign('district_id')
                ->references('id')
                ->on('districts')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('ground_type_id')
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
        
        Schema::dropIfExists('stadiums');
    }
}
