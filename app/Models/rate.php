<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class rate extends Model
{
    use HasFactory;
    protected $table = 'exchange_rates';
    protected $fillable = ['id','rate'];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

}
