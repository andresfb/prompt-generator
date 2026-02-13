<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('story_machine_sections', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->unsignedSmallInteger('to_pick')->default(0);
            $table->unsignedSmallInteger('order')->default(1);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('story_machine_sections');
    }
};
