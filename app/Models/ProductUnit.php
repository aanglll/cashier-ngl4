<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductUnit extends Model
{
    use HasFactory;

    protected $table = 'product_units';

    protected $fillable = ['name', 'status'];

    // Menentukan kolom yang tidak dapat diubah (guarded)
    // protected $guarded = ['id'];

    protected $attributes = [
        'status' => 'inactive',
    ];
}
