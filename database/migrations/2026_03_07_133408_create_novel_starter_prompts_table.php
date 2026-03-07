<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('novel_starter_prompts', static function (Blueprint $table) {
            $table->id();
            $table->string('hash')->unique();
            $table->string('hero');
            $table->string('flaw');
            $table->string('genre');
            $table->text('content')->nullable();
            $table->text('provider')->nullable();
            $table->text('prompt')->nullable();
            $table->boolean('generated')
                ->default(false)
                ->index();
            $table->boolean('active')->default(true);
            $table->unsignedSmallInteger('usages')->default(0);
            $table->softDeletes();
            $table->timestamps();

            $table->index(['active', 'usages']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('novel_starter_prompts');
    }
};
