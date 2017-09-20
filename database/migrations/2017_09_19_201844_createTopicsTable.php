<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTopicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // update users
        Schema::table('users', function (Blueprint $table) {
            $table->char('slug', 255)->nullable();
            $table->boolean('active')->default(true);
        });

        // phone numbers
        Schema::create('phones', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable();
            $table->string('phone')->nullable();
            $table->unsignedInteger('user_id')->nullable();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        // topics
        Schema::create('topics', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->unsignedInteger('user_id')->nullable();

            $table->timestamps();

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });

        // repetitions
        Schema::create('repetitions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('topic_id')->nullable();
            $table->dateTime('when')->nullable(); // date

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
            $table->foreign('topic_id')
                ->references('id')
                ->on('topics')
                ->onDelete('cascade');
        });

        // preferences
        Schema::create('preferences', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->string('language')->nullable();
            $table->string('pattern')->nullable();
            $table->boolean('use_calendar')->default(false);

            $table->foreign('user_id')
                ->references('id')
                ->on('users')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->removeColumn('slug');
            $table->removeColumn('active');
        });
        Schema::dropIfExists('phones');
        Schema::dropIfExists('repetitions');
        Schema::dropIfExists('topics');
        Schema::dropIfExists('preferences');
    }
}
