<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Demands extends Model
{
    use HasFactory;
        protected $fillable = [
        'type',
        'name',
        'qty',
    ];
}
