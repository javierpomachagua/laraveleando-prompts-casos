<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $casts = [
        'date' => 'datetime',
    ];

    public function users()
    {
        return $this->belongsToMany(User::class);
    }
}
