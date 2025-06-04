<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class suppiler extends Model
{
    use HasFactory;
    protected $table = 'supplier';
    protected $fillable = ['id','name','address','phone','email'];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    
}
