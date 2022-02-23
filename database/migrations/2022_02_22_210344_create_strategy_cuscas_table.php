<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStrategyCuscasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('strategy_cusca', function (Blueprint $table){
            $table->id();
            $table->string('description_strategy',500);
            $table->date('create_date');
            $table->string('user_create_strategy');
            $table->double('percentage', 8, 2);
            $table->foreignId('organizational_units_id')->references('id')->on('organizational_units');
            $table->foreignId('programmatic_objectives_id')->references('id')->on('programmatic_objectives');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('strategy_cusca');
    }
}
