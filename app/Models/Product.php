<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    /**
     * Tabel yang terkait dengan model ini.
     *
     * @var string
     */
    protected $table = 'products';

    /**
     * Kolom yang dapat diisi secara mass-assignment.
     *
     * @var array
     */
    protected $fillable = ['id_category', 'product_name', 'product_units', 'purchase_price', 'selling_price', 'stock', 'barcode', 'description'];

    /**
     * Tipe data kolom yang harus di-cast.
     *
     * @var array
     */
    protected $casts = [
        'purchase_price' => 'decimal:2',
        'selling_price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'id_category');
    }
}
