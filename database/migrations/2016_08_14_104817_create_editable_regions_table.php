<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEditableRegionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('editable_regions', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->string('layout')->index();
            $table->string('region')->index();
            $table->text('items')->nullable();
            $table->text('settings')->nullable();
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
        Schema::drop('editable_regions');
    }
}
