<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;
    protected $table = 'brand';
    protected $fillable = [
        'id',
        'name',
        'description',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
