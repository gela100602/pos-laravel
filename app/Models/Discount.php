<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Discount extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'discounts';
    protected $primaryKey = 'discount_id';
    protected $fillable = [
        'discount_type',
        'percentage'
    ];
}