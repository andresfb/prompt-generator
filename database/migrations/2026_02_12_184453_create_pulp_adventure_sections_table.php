<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pulp_adventure_sections', static function (Blueprint $table) {
            $table->id();
            $table->string('code', 4)->index();
            $table->string('name');
            $table->unsignedSmallInteger('order');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pulp_adventure_sections');
    }
};
