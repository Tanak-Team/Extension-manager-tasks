<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTmActionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('tm_actions', function (Blueprint $table) {
            $table->increments('id');
            $table->longText('comment');
            $table->boolean('is_read')->default(0);
            $table->timestamps();
        });

        Schema::table('tm_actions', function($table) {
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('tm_task_id');
            $table->unsignedInteger('assigner_id');
            $table->unsignedInteger('tm_process_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('tm_actions');
    }
}
