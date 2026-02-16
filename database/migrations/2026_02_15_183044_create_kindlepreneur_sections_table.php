<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('kindlepreneur_sections', static function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->boolean('active')
                ->default(true)
                ->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('kindlepreneur_sections');
    }
};
