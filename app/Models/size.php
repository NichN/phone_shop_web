<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class size extends Model
{
    use HasFactory;
    protected $table = 'size';
    protected $fillable = [
        'id',
        'size'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
