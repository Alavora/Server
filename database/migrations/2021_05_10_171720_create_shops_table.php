<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShopsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shops', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->string("name");
            $table->string("cif");
            $table->string("phone");
            $table->string("address");
            $table->double("longitude")->nullable();
            $table->double("latitude")->nullable();

            // $table->string('image_path');

            $table->unsignedBigInteger('market_id')->index();
            $table->foreign('market_id')->references('id')->on('markets');

            // $table->unsignedBigInteger('owner_id')->index();
            // $table->foreign('owner_id')->references('owner_id')->on('shop_owner');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shops');
    }
}
