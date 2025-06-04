<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'product';
    protected $fillable = ['id','name','description','brand_id','cat_id'];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
