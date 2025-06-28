<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Address extends Model
{
    protected $fillable = ['user_id', 'title', 'address_line1', 'address_line2', 'is_default'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
