<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResultsCuscasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('results_cusca', function (Blueprint $table) {
            $table->id();
            $table->text('result_description');
            $table->string('mesure_unit', 500);
            $table->integer('year_goal');
            $table->string('executed');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('indicator_id')->references('id')->on('indicators');
            $table->foreignId('organizational_units_id')->references('id')->on('organizational_units');
            $table->foreignId('year_id')->references('id')->on('years');
            $table->foreignId('period_id')->references('id')->on('periods');
            $table->foreignId('strategy_cusca_id')->references('id')->on('strategy_cusca');
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
        Schema::dropIfExists('results_cusca');
    }
}
