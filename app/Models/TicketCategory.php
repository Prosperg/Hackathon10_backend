<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class TicketCategory extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description', 
        'price',
        'duration_hours',
        'signature',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'duration_hours' => 'integer',
        'is_active' => 'boolean'
    ];

}
