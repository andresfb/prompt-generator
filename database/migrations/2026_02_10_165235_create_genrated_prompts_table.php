<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('generated_prompts', static function (Blueprint $table) {
            $table->id();
            $table->string('genre');
            $table->string('setting');
            $table->string('character');
            $table->string('conflict');
            $table->string('tone');
            $table->string('narrative');
            $table->string('period');
            $table->text('content');
            $table->boolean('active')->default(true);
            $table->unsignedInteger('usages')->default(0);
            $table->text('provider');
            $table->text('prompt');
            $table->softDeletes();
            $table->timestamps();

            $table->index(['active', 'usages'], 'active_usages_index');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('generated_prompts');
    }
};
