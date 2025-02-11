<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->string('width')->nullable();
            $table->string('height')->nullable();
            $table->string('rim')->nullable();
            $table->string('runflat')->nullable();
            $table->string('load_speed')->nullable();
            $table->integer('year')->nullable();

            $table->unsignedBigInteger('brand_id')->nullable();
            $table->unsignedBigInteger('pattern_id')->nullable();
            $table->unsignedBigInteger('oem_id')->nullable();
            $table->unsignedBigInteger('origin_id')->nullable();

            // Optionally, you can add foreign key constraints if you have separate tables for these entities
            // $table->foreign('brand_id')->references('id')->on('brands')->onDelete('set null');
            // $table->foreign('pattern_id')->references('id')->on('patterns')->onDelete('set null');
            // $table->foreign('oem_id')->references('id')->on('oems')->onDelete('set null');
            // $table->foreign('origin_id')->references('id')->on('origins')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn(['width', 'height', 'rim', 'runflat', 'load_speed', 'year', 'brand_id', 'pattern_id', 'oem_id', 'origin_id']);
        });
    }
};
