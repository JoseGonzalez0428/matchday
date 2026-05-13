<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tournament_matches', function (Blueprint $table) {
            $table->tinyInteger('home_penalties')->unsigned()->nullable()->after('away_score');
            $table->tinyInteger('away_penalties')->unsigned()->nullable()->after('home_penalties');
        });
    }

    public function down(): void
    {
        Schema::table('tournament_matches', function (Blueprint $table) {
            $table->dropColumn(['home_penalties', 'away_penalties']);
        });
    }
};