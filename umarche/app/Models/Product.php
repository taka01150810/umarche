<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Shop;
use App\Models\SecondaryCategory;
use App\Models\Image;

class Product extends Model
{
    use HasFactory;

    public function shop()
    {
        return $this->belongsTo(Shop::class);
    }

    public function category(){
        return $this->belongsTo(SecondaryCategory::class, 'secondary_category_id');
    }

    /*
    メソッド名をモデル名から変える場合は第2引数が必要
    (カラム名と同じメソッドは指定できないので名称変更)
    第2引数で  _id   がつかない場合は第3引数で指定が必要
    */
    public function imageFirst(){
        return $this->belongsTo(Image::class, 'image1', 'id');
    }
}
