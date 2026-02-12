<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plot_machine_sections', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('order')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('plot_machine_sections');
    }
};
