<?php

namespace App\Models;

use Eliseekn\LaravelMetrics\HasMetrics;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory, HasMetrics;

    protected $fillable = [
        'name',
        'price',
        'slug',
        'status'
    ];
}
