<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('players', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_id')->constrained('teams')->cascadeOnDelete();
            $table->string('name', 100);
            $table->tinyInteger('dorsal')->unsigned();
            $table->enum('position', ['GK', 'DEF', 'MID', 'FWD']);
            $table->string('nationality', 60)->nullable();
            $table->timestamps();

            $table->unique(['team_id', 'dorsal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('players');
    }
};