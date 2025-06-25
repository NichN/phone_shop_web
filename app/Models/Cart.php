<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cart extends Model
{
    use HasFactory;
    protected $table = 'cart';
    protected $fillable = [
        'id',
        'user_id',
        'product_item_id',
        'quantity',
        'price'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
