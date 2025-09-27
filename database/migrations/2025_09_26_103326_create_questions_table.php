<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('questions', function (Blueprint $table) {
            $table->id();
            $table->text('question_text');
            $table->enum('question_type', ['MCQ', 'Descriptive'])->default('MCQ');
            $table->enum('question_format', [
                'Single_MC',
                'Multiple_MC',
                'Fill_Blank',
                'True_False',
                'Descriptive',
                'Match',
                'Assertion_Reason'
            ])->default('Single_MC');
            $table->text('answer_text')->nullable();
            $table->foreignId('subject_id')->constrained()->cascadeOnDelete();
            $table->foreignId('chapter_id')->nullable()->constrained()->nullOnDelete();
            $table->enum('difficulty', ['Easy', 'Medium', 'Hard'])->default('Medium');
            $table->integer('marks')->default(1);
            $table->timestamps();
            $table->unique('question_text');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('questions');
    }
};
