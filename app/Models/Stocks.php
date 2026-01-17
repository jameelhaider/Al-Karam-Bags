<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stocks extends Model
{
    use HasFactory;
    protected $fillable = [
        'qty',
        'alert_qty',
        'name',
        'sale_price',
        'purchase_price'
    ];
}
