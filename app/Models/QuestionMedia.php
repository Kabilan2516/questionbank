<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class QuestionMedia extends Model
{
    use HasFactory;

    protected $fillable = [
        'question_id',
        'media_type',
        'media_url'
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }
}
