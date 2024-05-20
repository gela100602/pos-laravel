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
        return $this->belongsTo('App\Models\Customer', 'customer_id');
    }

    public function discount()
    {
        return $this->belongsTo('App\Models\Discount', 'discount_id');
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User', 'user_id');
    }
}