<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('booking_plots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('plot_id')->references('id')->on('plots');
            $table->foreignId('member_id')->references('id')->on('members');
            $table->string('member_phone');
            $table->double('deposit_amount');
            $table->double('paystack_commission');
            $table->string('payment_reference')->unique();
            $table->integer('payment_status_code');
            $table->string('date_processed');
            $table->string('month');
            $table->timestamp('date_cancelled')->nullable();
            $table->softDeletes();
            $table->timestamps();

            $table->unique(['member_id', 'plot_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('booking_plot');
    }
};
