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
        Schema::create('surats', function (Blueprint $table) {
            $table->id();
            $table->string('nomor');
            $table->string('nomor_surat');
            $table->string('kepada');
            $table->string('perihal');
            $table->string('tembusan');
            $table->string('url_surat');
            $table->string('mengetahui');
            $table->boolean('verif1')->default(0);
            $table->boolean('verif2')->default(0);
            $table->boolean('verif3')->default(0);
            $table->boolean('ditandatangani')->default(0);
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
        Schema::dropIfExists('surats');
    }
};
