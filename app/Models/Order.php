<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Order extends Model
{
    use HasFactory;

    protected $table = 'orders';

    protected $fillable = [
        'user_id',
        'delivery_id',
        'subtotal',
        'phone_number',
        'address',
        'delivery_fee',
        'total_amount',
    ];
}
