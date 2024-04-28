<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'province',
        'province_id',
        'city',
        'city_id',
        'postal_code',
        'detail',
        'phone'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
