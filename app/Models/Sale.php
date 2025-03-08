<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    use HasFactory;

    protected $table = 'sales';

    protected $fillable = ['user_id', 'customer_id', 'discount', 'ppn', 'total_price', 'cash_paid', 'cash_return'];

    public function salesDetails()
    {
        return $this->hasMany(SalesDetail::class, 'sales_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
