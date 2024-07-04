<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sales extends Model
{
    use HasFactory;

    protected $table = 'sales';
    protected $primaryKey = 'sales_id';
    protected $fillable = [
        'role_id',
        'gender_id',
        'name',
        'email',
        'username',
        'password',
        'contact_number',
        'user_image'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'sales_id', 'id_user');
    }
}

