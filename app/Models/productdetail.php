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
        'color_code',
        'size',
        'type',
        'warranty',
        'is_featured'
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    protected $casts = [
    'images' => 'array',
    ];

    public function color()
    {
        return $this->belongsTo(Color::class, 'color_id', 'id');
    }
    public function product()
    {
        return $this->belongsTo(Product::class, 'pro_id', 'id');
    }
    public function size()
    {
        return $this->belongsTo(Size::class, 'size_id', 'id');
    }
}
