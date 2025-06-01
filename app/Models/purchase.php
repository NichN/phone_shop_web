<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purchase extends Model
{
    use HasFactory;
    protected $table = 'purchase';
    protected $fillable = [
        'id',
        'Grand_total',
        'paid',
        'balance',
        'payment_statuse',
        'reference_no',
        'supplier_id'
    ];
     protected $dates = [
        'created_at',
        'updated_at',
    ];
}
