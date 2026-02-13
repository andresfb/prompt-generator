<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('novel_starter_sections', static function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->boolean('active')
                ->default(true)
                ->index();
            $table->unsignedSmallInteger('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('novel_starter_sections');
    }
};
