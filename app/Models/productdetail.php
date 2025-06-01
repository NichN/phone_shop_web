<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class productdetail extends Model
{
    use HasFactory;
    protected $table = 'product_item';
    protected $fillable = [
        'id',
        'pro_id',
        'order_item_id',
        'color_id',
        'size_id',
        'price',
        'cost_price',
        'stock',
        'images',
        'product_name',
        'color',
        'size'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [
    'images' => 'array',
    ];
}
