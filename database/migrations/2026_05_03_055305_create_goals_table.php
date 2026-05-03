<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('match_id')->constrained('tournament_matches')->cascadeOnDelete();
            $table->foreignId('player_id')->nullable()->constrained('players')->nullOnDelete();
            $table->tinyInteger('minute')->unsigned();
            $table->enum('type', ['regular', 'penalty', 'own_goal']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('goals');
    }
};