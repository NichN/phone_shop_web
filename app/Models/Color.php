<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Color extends Model
{
    use HasFactory;
    protected $table = 'color';
    protected $fillable = ['id','name','code'];
    protected $dates = [
        'created_at',
        'updated_at',
    ];
    // Optional reverse relation
public function productDetails()
{
    return $this->hasMany(ProductDetail::class, 'color_code', 'color_code');
}

}
