<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use APp\Models\Owner;

class Shop extends Model
{
    use HasFactory;

    public function owner()
    {
        return $this->belongsTo(Owner::class);
    }
}
