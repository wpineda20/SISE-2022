<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActionsCuscasTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('actions_cusca', function (Blueprint $table) {
            $table->id();
            $table->string('description_action',500);
            $table->date('create_date');
            $table->integer('actionNumberYear');
            $table->double('percentage', 8, 2);
            $table->string('responsable_name');
            $table->foreignId('user_id')->references('id')->on('users');
            $table->foreignId('results_cusca_id')->references('id')->on('results_cusca');
            $table->foreignId('year_id')->references('id')->on('years');
            $table->foreignId('financings_id')->references('id')->on('financings');
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
        Schema::dropIfExists('actions_cusca');
    }
}
