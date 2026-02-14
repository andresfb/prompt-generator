<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('hugging_face_prompts', static function (Blueprint $table) {
            $table->id();
            $table->text('text');
            $table->boolean('active')->default(true);
            $table->unsignedSmallInteger('usages')->default(0);

            $table->index(['active', 'usages']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('hugging_face_prompts');
    }
};
