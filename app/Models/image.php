<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class image extends Model
{
    use HasFactory;
    protected $table = 'image';
    protected $fillable = [
        'id',
        'pr_item_id',
        'file_name',
        'file_path',
        'name',
        'img_type',
        'is_default',
        'caption',
        'description',
    ];
    protected $dates = [
        'created_at',
        'updated_at',
    ];

}
