<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;//ミスで前回のコミットで追加
use Illuminate\Support\Facades\Auth;

// php artisan make:controller User/CartControllerで生成
class CartController extends Controller
{
    //
    public function add(Request $request)
    {
        $itemInCart = Cart::where('user_id', Auth::id())
        ->where('product_id', $request->product_id)
        ->first();//カートに商品があるか確認
        
        if($itemInCart){
            $itemInCart->quantity += $request->quantity; //あれば数量を追加
            $itemInCart->save();
        } else {
            Cart::create([ // なければ新規作成
                'user_id' => Auth::id(),
                'product_id' => $request->product_id,
                'quantity' => $request->quantity
            ]);
        }

        dd('テスト');
    }
}
