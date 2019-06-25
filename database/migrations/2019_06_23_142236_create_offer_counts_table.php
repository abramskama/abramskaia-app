<?php

use App\OfferCount;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOfferCountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('offer_counts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->bigInteger('city_id', false, true);
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->bigInteger('count', false, true);
            $table->timestamps();
        });

        $offerCount = new OfferCount();
        $offerCount->updateOfferCount();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('offer_counts');
    }
}
