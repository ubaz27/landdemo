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
        //
        Schema::create('land_distributions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('member_id');
            $table->foreignId('plot_id')->constrained();
            $table->foreignId('agent_id');
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
        //
        Schema::dropIfExists('land_distributions');
    }
};
