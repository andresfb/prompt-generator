<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('publishing_sections', static function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description');
            $table->text('hint')->nullable();
            $table->boolean('active')
                ->default(true)
                ->index();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('publishing_sections');
    }
};
