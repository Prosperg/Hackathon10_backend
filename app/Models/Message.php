<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Message extends Model
{
    use HasFactory;
    protected $fillable = ["content","from_id","to_id","read_at"];

    public $timestamps = true;

    protected $date = ["read_at"];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
