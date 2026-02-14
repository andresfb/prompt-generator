<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('media_studios', static function (Blueprint $table) {
            $table->id();
            $table->uuid()->unique();
            $table->string('name');
            $table->string('short_name');
            $table->text('description')->nullable();
            $table->string('endpoint');
            $table->unsignedSmallInteger('total_scenes')->default(0);
            $table->unsignedSmallInteger('per_page')->default(0);
            $table->unsignedSmallInteger('last_page')->default(0);
            $table->boolean('active')
                ->default(true)
                ->index();
            $table->timestamp('published_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('media_studios');
    }
};
