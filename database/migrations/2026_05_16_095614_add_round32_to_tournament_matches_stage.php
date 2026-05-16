<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // MySQL no permite modificar enums directamente, hay que recrear la columna
        DB::statement("ALTER TABLE tournament_matches MODIFY COLUMN stage ENUM('group','round32','quarter','semi','final') NOT NULL");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE tournament_matches MODIFY COLUMN stage ENUM('group','quarter','semi','final') NOT NULL");
    }
};