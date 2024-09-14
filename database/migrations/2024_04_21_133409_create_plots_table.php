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
        Schema::create('plots', function (Blueprint $table) {
            $table->id();
            $table->foreignId('land_id')->constrained();
            $table->string('plot_no');
            $table->string('dimension');
            $table->double('cost', 12, 2, true)->unsigned();
            $table->softDeletes();
            $table->timestamps();
            $table->unique(['land_id', 'plot_no']);
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
        Schema::dropIfExists('plots');
    }
};
