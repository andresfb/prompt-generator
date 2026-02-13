<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('image_based_prompts', static function (Blueprint $table) {
            $table->id();
            $table->string('hash')->unique();
            $table->text('content');
            $table->boolean('active')->default(true);
            $table->unsignedSmallInteger('usages')->default(0);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('image_based_prompts');
    }
};
