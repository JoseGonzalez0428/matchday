<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tournaments', function (Blueprint $table) {
            $table->id();
            $table->string('name', 150);
            $table->year('edition');
            $table->enum('format', ['groups_knockout', 'league', 'knockout'])->default('groups_knockout');
            $table->enum('status', ['draft', 'active', 'finished'])->default('draft');
            $table->date('starts_at');
            $table->date('ends_at')->nullable();
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tournaments');
    }
};