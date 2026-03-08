<?php /** @noinspection DuplicatedCode */

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('plot_machine_prompts', static function (Blueprint $table) {
            $table->id();
            $table->string('hash')->unique();
            $table->string('setting');
            $table->string('act_of_villan');
            $table->string('motive');
            $table->string('complicater');
            $table->string('twist');
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
        Schema::dropIfExists('plot_machine_prompts');
    }
};
