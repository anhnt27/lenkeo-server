<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGroundsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grounds', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('stadium_id');
            $table->unsignedInteger('ground_type_id');
            $table->string('name');
            $table->integer('price_per_hour')->nullable();

            $table->foreign('stadium_id')
                ->references('id')
                ->on('stadiums')
                ->onUpdate('NO ACTION')
                ->onDelete('NO ACTION');
            $table->foreign('ground_type_id')
                ->references('id')
                ->on('properties')
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
        Schema::dropIfExists('grounds');
    }
}
