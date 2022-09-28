<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

// php artisan make:controller User/ItemController で作成
class ItemController extends Controller
{
    //
    public function index(){
        $stocks = DB::table('t_stocks')
        ->select('product_id',
        DB::raw('sum(quantity) as quantity'))
        ->groupBy('product_id')
        ->having('quantity', '>', 1);

        /*
        前ページの $stocksをサブクエリとして設定
        products、shops、stocksをjoin句で紐付けて
        where句で is_sellingがtrue かの条件指定
        $products = DB::table('products')
        */
        $products = DB::table('products')
        ->joinSub($stocks, 'stock', function($join){
            $join->on('products.id', '=', 'stock.product_id');
        })
        ->join('shops', 'products.shop_id', '=', 'shops.id')
        //ここから Eloquent->クエリビルダに変更したためselectで指定
        ->join('secondary_categories', 'products.secondary_category_id', '=', 'secondary_categories.id')
        ->join('images as image1', 'products.image1', '=', 'image1.id')
        //ここまで
        ->where('shops.is_selling', true)
        ->where('products.is_selling', true)
        //ここから Eloquent->クエリビルダに変更したためselectで指定
        ->select('products.id as id', 'products.name as name', 'products.price',
        'products.sort_order as sort_order','products.information',
        'secondary_categories.name as category','image1.filename as filename')
        //ここまで
        ->get();

        // dd($stocks, $products);
        //結果 https://gyazo.com/86967a974ae38fa2bc1fd6bfdd310cf9

        return view('user.index', compact('products'));
    }
}
