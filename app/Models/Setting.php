<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;
       protected $fillable = [
        'is_show_qty',
        'is_show_purchase',
        'is_show_sale',
        'is_show_status',
        'is_show_action',
    ];
}
