<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory;

    protected $table = 'order_item';

    protected $fillable = [
        'order_id',
        'quantity',
        'price',
        'product_item_id'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
