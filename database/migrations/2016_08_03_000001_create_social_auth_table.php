<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateSocialAuthTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('social_connections', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned()->nullable();
            $table->string('social_id', 127)->index();
            $table->string('provider')->default('');
            $table->char('oauth_version', 1)->default(2);
            $table->json('data')->nullable();
            $table->timestamps();
            $table->unique(['provider', 'user_id']);
        });

    }


    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('social_connections');
    }
}
