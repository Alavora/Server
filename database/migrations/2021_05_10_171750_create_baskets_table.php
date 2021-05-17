<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateBasketsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baskets', function (Blueprint $table) {
            $table->id();
            $table->timestamps();

            $table->text('comments')->default('');
            $table->dateTime('closed');
            $table->string('status');

            $table->unsignedBigInteger('shop_id')->index();
            $table->foreign('shop_id')->references('id')->on('shops');

            $table->unsignedBigInteger('user_id')->index();
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('distributor_id')->index();
            $table->foreign('distributor_id')->references('id')->on('users');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('baskets');
    }
}
