<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Jobs extends Model
{
    use HasFactory;

    protected $table = 'jobs';

    protected $fillable = [
        'customer_name',
        'customer_phone',
        'company_id',
        'company_name',
        'model_id',
        'model_name',
        'issues',
        'parts',
        'inn_date',
        'advance',
        'status',
        'reason',
        'dead_approval',
    ];
    protected $casts = [
        'issues' => 'array',
        'parts' => 'array',
    ];

    public function getIssuesAttribute($value)
    {
        return json_decode($value, true);
    }
}
