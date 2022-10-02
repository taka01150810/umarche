<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;

// php artisan make:controller User/ItemController で作成
class ItemController extends Controller
{
    //

    public function __construct()
    {
        $this->middleware('auth:users');

        //販売中でない商品もURLを入力すると表示されるのでその対策
        $this->middleware(function ($request, $next) {
            $id = $request->route()->parameter('item');
            if(!is_null($id)){
                $itemId = Product::availableItems()->where('products.id', $id)->exists();
                if(!$itemId){
                    abort(404); // 404画面表示
                }
            }
            return $next($request);
        });
    }

    public function index(){

        $products = Product::availableItems()->get();

        return view('user.index', compact('products'));
    }

    public function show($id)
    {
        $product = Product::findOrFail($id);

        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');

        if($quantity > 9){
            $quantity = 9;
        }

        return view('user.show',
        compact('product', 'quantity'));
    }

}
