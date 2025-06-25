<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class deliveries extends Model
{
    use HasFactory;
    protected $table = 'deliveries';
    protected $fillable = ['id','order_id','delivery_status','customer_name','customer_phone','address'];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
