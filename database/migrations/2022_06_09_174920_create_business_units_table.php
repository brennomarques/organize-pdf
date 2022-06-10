<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBusinessUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('business_units', function (Blueprint $table) {
            $table->id()->unsigned();
            $table->uuid('uuid')->index()->nullable(false);
            $table->string('name')->nullable(false);
            $table->string('document')->nullable(false);
            $table->string('zipCode')->nullable(false);
            $table->string('address');
            $table->string('city');
            $table->string('state');
            $table->string('country');
            $table->boolean('status')->nullable(false);
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
        Schema::dropIfExists('business_units');
    }
}
