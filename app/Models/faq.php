<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class faq extends Model
{
    use HasFactory;
    protected $table = 'faqs';
    protected $fillable = [
        'id',
        'question',
        'answer',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
