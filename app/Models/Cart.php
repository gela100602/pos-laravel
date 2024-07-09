<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class Cart extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $table = 'carts';
    protected $primaryKey = 'cart_id';
    protected $fillable = [
        'transaction_id',
        'product_id',
        'selling_price',
        'quantity',
        'subtotal'
    ];

    public function transaction()
    {
        return $this->belongsTo(PaymentTransaction::class, 'transaction_id');
    }

    public function product()
    {
        return $this->belongsTo('App\Models\Product', 'product_id');
    }
    public function discount()
    {
        return $this->belongsTo(Discount::class)->withDefault();   
    }
}