<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class payment extends Model
{
    use HasFactory;
    protected $table = 'payment';
    protected $fillable = [
        'id',
        'order_id',
        'payment_type_id',
        'amount',
        'payment_type',
        'remark',
        'payment_status',
        'img_verify'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
