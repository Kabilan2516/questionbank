<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_text',
        'question_type',
        'question_format',
        'answer_text',
        'subject_id',
        'chapter_id',
        'difficulty',
        'marks'
    ];

    // Relationships
    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function chapter()
    {
        return $this->belongsTo(Chapter::class);
    }

    public function options()
    {
        return $this->hasMany(QuestionOption::class);
    }

    public function tests()
    {
        return $this->belongsToMany(Test::class, 'test_questions')->withPivot('order_no')->withTimestamps();
    }

    public function media()
    {
        return $this->hasMany(QuestionMedia::class);
    }
}
