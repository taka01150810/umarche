<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Product;

class Stock extends Model
{
    use HasFactory;

    //テーブル名が変更する時は宣言
    protected $table = 't_stocks';

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
