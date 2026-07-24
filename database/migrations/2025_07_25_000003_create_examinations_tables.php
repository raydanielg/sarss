<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('examinations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('academic_year_id')->constrained()->cascadeOnDelete();
            $table->foreignId('exam_type_id')->constrained()->cascadeOnDelete();
            $table->foreignId('region_id')->constrained()->cascadeOnDelete();
            $table->date('start_date');
            $table->date('end_date');
            $table->enum('status', ['draft', 'open', 'closed', 'archived'])->default('draft');
            $table->foreignId('created_by')->constrained('users')->cascadeOnDelete();
            $table->text('description')->nullable();
            $table->timestamps();
        });

        Schema::create('examination_district', function (Blueprint $table) {
            $table->foreignId('examination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('district_id')->constrained()->cascadeOnDelete();
            $table->primary(['examination_id', 'district_id']);
        });

        Schema::create('examination_school', function (Blueprint $table) {
            $table->foreignId('examination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('school_id')->constrained()->cascadeOnDelete();
            $table->primary(['examination_id', 'school_id']);
        });

        Schema::create('examination_subject', function (Blueprint $table) {
            $table->foreignId('examination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->primary(['examination_id', 'subject_id']);
        });

        Schema::create('examination_class', function (Blueprint $table) {
            $table->foreignId('examination_id')->constrained()->cascadeOnDelete();
            $table->foreignId('class_id')->constrained()->cascadeOnDelete();
            $table->primary(['examination_id', 'class_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('examination_class');
        Schema::dropIfExists('examination_subject');
        Schema::dropIfExists('examination_school');
        Schema::dropIfExists('examination_district');
        Schema::dropIfExists('examinations');
    }
};
