<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';

    protected $fillable = [
        'site_name',
        'site_logo',
        'favicon',
        'site_title',
        'receipt_header',
        'receipt_footer',
    ];
}