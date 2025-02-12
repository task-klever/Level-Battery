<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quote extends Model
{
    use HasFactory;

    protected $fillable = [
        'full_name', 'email', 'phone', 'country', 'business_type', 'looking_for', 'message'
    ];

    protected $casts = [
        'looking_for' => 'array', // Store multi-select options as JSON
    ];
}
