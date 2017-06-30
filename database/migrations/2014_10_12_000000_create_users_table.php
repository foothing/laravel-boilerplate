<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->default("");
            $table->string('email')->unique();
            $table->string('password');
            $table->enum('status', ['active'])->nullable()->default(null);
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('activations', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('code')->unique();
            $table->boolean('completed')->default(false);
            $table->dateTime('completed_at')->nullable()->default(null);
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activations');
        Schema::dropIfExists('users');
    }
}
