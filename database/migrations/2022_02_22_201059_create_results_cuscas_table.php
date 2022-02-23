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
            $table->string('result_description', 500);
            $table->string('responsible_name');
            $table->double('percentage', 8, 2);
            $table->date('create_date');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('axis_cusca_id')->references('id')->on('axis_cusca');
            $table->foreignId('indicator_id')->references('id')->on('indicators');
            $table->foreignId('organizational_units_id')->references('id')->on('organizational_units');
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
