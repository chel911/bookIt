<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TimeRead extends Model
{
    use HasFactory;

    protected $table = 'my_flights';

    protected $fillable = [
        'user_id', 'book_id', 'created_at', 'reading_time'
    ];
}
