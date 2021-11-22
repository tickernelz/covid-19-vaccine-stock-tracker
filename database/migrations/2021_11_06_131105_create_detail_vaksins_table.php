<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDetailVaksinsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('detail_vaksins', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\Barang::class)->constrained()->onDelete('cascade');
            $table->string('kemasan');
            $table->integer('batch');
            $table->string('tanggal');
            $table->date('ed');
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
        Schema::dropIfExists('detail_vaksins');
    }
}
