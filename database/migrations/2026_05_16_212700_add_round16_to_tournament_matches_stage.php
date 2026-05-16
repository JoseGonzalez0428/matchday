<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE tournament_matches MODIFY COLUMN stage ENUM('group','round32','round16','quarter','semi','final') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE tournament_matches MODIFY COLUMN stage ENUM('group','round32','quarter','semi','final') NOT NULL");
    }
};