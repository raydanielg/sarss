<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('panels', function (Blueprint $table) {
            $table->id();
            $table->foreignId('examination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('moderator_user_id')->constrained('users')->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['examination_id', 'subject_id']);
        });

        Schema::create('panel_markers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id')->constrained()->cascadeOnDelete();
            $table->string('name');
            $table->string('phone')->nullable();
            $table->foreignId('school_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('panel_data_entry', function (Blueprint $table) {
            $table->id();
            $table->foreignId('panel_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->timestamps();

            $table->unique(['panel_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('panel_data_entry');
        Schema::dropIfExists('panel_markers');
        Schema::dropIfExists('panels');
    }
};
