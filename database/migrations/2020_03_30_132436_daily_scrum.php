<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DailyScrum extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('daily_scrum', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('id_users');
            $table->enum('team', ['dds', 'beon', 'dot', 'node1', 'node2', 'react1', 'react2', 'laravel', 'laravel_vue', 'android'])->default('dds');
            $table->text('activity_yesterday');
            $table->text('activity_today');
            $table->text('problem_yesterday');
            $table->text('solution');
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
        Schema::dropIfExists('daily_scrum');
    }
}
