<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('movie_infos', static function (Blueprint $table) {
            $table->id();
            $table->string('movie_id')
                ->unique();
            $table->json('content');
            $table->unsignedSmallInteger('usages')
                ->default(0)
                ->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('movie_infos');
    }
};
