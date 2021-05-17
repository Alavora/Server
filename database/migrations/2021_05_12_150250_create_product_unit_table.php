<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductUnitTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_unit', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('market_id')->index();
            $table->unsignedBigInteger('unit_id')->index();
            $table->unsignedBigInteger('product_id')->index();

            // $table->foreign('market_id')->references('id')->on('markets');
            $table->foreign('unit_id')->references('id')->on('units');
            $table->foreign('product_id')->references('id')->on('products');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('product_unit');
    }
}
