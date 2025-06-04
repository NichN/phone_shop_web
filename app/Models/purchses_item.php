<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class purchses_item extends Model
{
    use HasFactory;
    protected $table = 'purchase_item';
    protected $fillable = [
        'id',
        'purchase_id',
        'pr_item_id',
        'quantity',
        'subtotal',
        'supplier_id',
        'created_at',
        'updated_at',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
}
