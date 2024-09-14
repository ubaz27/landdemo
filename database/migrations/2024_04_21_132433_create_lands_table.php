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
        Schema::create('lands', function (Blueprint $table) {
            $table->id();
            $table->string('land_name')->unique();
            $table->string('lga');
            $table->double('cost', 12, 2, true)->unsigned();
            $table->string('dimension');
            $table->float('commission');
            $table->softDeletes();
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
        //
        Schema::dropIfExists('lands');
    }
};
