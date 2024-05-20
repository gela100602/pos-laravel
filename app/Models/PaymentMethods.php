<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PaymentMethods extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'payment_methods';
    protected $primaryKey = 'method_id';
    protected $fillable = ['method'];
}