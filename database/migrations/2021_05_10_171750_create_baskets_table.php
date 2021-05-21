<?php

use App\Models\Basket;
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
            $table->dateTime('closed')->nullable()->default(null);
            $table->string('status')->default(Basket::STATUS_UNCONFIRMED);

            $table->unsignedBigInteger('shop_id')->index();
            $table->foreign('shop_id')->references('id')->on('shops');

            $table->unsignedBigInteger('user_id')->default(1)->index()->nullable();
            $table->foreign('user_id')->references('id')->on('users');

            $table->unsignedBigInteger('distributor_id')->index()->nullable();
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
