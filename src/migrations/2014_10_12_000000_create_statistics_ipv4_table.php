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
        Schema::create('statistics_ipv4', function (Blueprint $table) {
            $table->id();
            $table->char('country_iso',2);
            $table->char('ip_start',15);
            $table->char('ip_end',15);
            $table->unsignedBigInteger('ip_start_int')->index();
            $table->unsignedBigInteger('ip_end_int')->index();
            //$table->engine = 'InnoDB';
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statistics_ipv4');
    }
};
