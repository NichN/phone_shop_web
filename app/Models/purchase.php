<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\purchses_item;
use App\Models\suppiler;

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
        'supplier_id',
        'note'
    ];
     protected $dates = [
        'created_at',
        'updated_at',
    ];
    public function supplier()
    {
        return $this->belongsTo(suppiler::class, 'supplier_id', 'id');
    }

    public function purchaseItems()
    {
        return $this->hasMany(purchses_item::class, 'purchase_id', 'id');
    }
}
