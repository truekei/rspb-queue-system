<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Queue extends Model
{
    /** @use HasFactory<\Database\Factories\QueueFactory> */
    use HasFactory;
    protected $fillable = [
        'number',
        'type',
        'datetime',
        'status',
        'staff_id'
    ];
}
