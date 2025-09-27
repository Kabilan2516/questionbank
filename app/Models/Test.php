<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Test extends Model
{
    use HasFactory;

    protected $fillable = ['name','test_date','duration_minutes'];

    // Relationships
    public function questions()
    {
        return $this->belongsToMany(Question::class, 'test_questions')->withPivot('order_no')->withTimestamps();
    }
}
