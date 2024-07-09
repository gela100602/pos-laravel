<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class PaymentTransaction extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'payment_transactions';
    protected $primaryKey = 'transaction_id';
    protected $fillable = [
        'customer_id',
        'total_items',
        'total_price',
        'discount_id',
        'payment',
        'received',
        'user_id'
    ];

    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }

    public function discount()
    {
        return $this->belongsTo(Discount::class, 'discount_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function carts()
    {
        return $this->hasMany(Cart::class, 'transaction_id', 'transaction_id');
    }
}
