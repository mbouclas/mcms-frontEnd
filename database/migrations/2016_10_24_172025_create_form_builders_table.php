<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFormBuildersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_builders', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title');
            $table->string('slug');
            $table->text('provider');
            $table->text('label')->nullable()->description('What the user can see as title');
            $table->text('description')->nullable()->description('Can be shown to the user');
            $table->text('fields');
            $table->text('settings')->nullable();
            $table->text('meta')->nullable()->description('Store extra form info here');
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
        Schema::dropIfExists('form_builders');
    }
}
