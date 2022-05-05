<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateContactQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contact_questions', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('contact_id');
            $table->text('input_1')->nullable();
            $table->text('input_2')->nullable();
            $table->text('input_3')->nullable();
            $table->text('input_4')->nullable();
            $table->text('input_5')->nullable();
            $table->text('input_6')->nullable();
            $table->text('input_7')->nullable();
            $table->text('input_8')->nullable();
            $table->text('input_9')->nullable();
            $table->text('input_10')->nullable();
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
        Schema::dropIfExists('contact_questions');
    }
}
