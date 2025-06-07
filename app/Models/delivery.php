<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class delivery extends Model
{
    use HasFactory;
    protected $table = 'delivery';
    protected $fillable = ['id','fee'];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
