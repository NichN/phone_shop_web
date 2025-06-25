<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class refund extends Model
{
    use HasFactory;
    protected $table = 'returns';
    protected $fillable = ['id','order_id','return_status','return_reason','refund_amount'];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
