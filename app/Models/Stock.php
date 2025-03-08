<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stock extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'stock_in',
        'stock_out',
        'current_stock',
        'source',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
