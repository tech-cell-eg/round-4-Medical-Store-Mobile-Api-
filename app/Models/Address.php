<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['user_id', 'phone', 'address1', 'address2'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
