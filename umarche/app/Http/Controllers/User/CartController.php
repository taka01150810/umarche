<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;//ミスで前回のコミットで追加
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Stock;

// php artisan make:controller User/CartControllerで生成
class CartController extends Controller
{
    //

    public function index()
    {
        $user = User::findOrFail(Auth::id());
        $products = $user->products; //多対多のリレーション
        $totalPrice = 0;
        
        foreach($products as $product){
            $totalPrice += $product->price * $product->pivot->quantity;
        }

        // dd($product, $totalPrice);

        return view('user.cart',
        compact('products', 'totalPrice'));
    }

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
        return redirect()->route('user.cart.index');//redirect先のミス
    }

    public function delete($id)
    {
        Cart::where('product_id', $id)
        ->where('user_id', Auth::id())
        ->delete();

        return redirect()->route('user.cart.index');
    }

    public function checkout()
    {
        $user = User::findOrFail(Auth::id());
        $products = $user->products;

        $lineItems = [];
        foreach($products as $product){

            //在庫確認
            $quantity = '';
            $quantity = Stock::where('product_id', $product->id)->sum('quantity');

            if($product->pivot->quantity > $quantity ){
                return redirect()->route('user.cart.index');
            } else {
                $lineItem = [
                    'name' => $product->name,
                    'description' => $product->information,
                    'amount' => $product->price,
                    'currency' => 'jpy',
                    'quantity' => $product->pivot->quantity,
                ];
                array_push($lineItems, $lineItem);
            }
        }

        //決済前に在庫を減らしておく
        foreach($products as $product){
            Stock::create([
                'product_id' => $product->id,
                'type' => \Constant::PRODUCT_LIST['reduce'],
                'quantity' => $product->pivot->quantity * -1
            ]);
        }

        // dd('test');

        \Stripe\Stripe::setApiKey(env('STRIPE_SECRET_KEY'));
        
        header('Content-Type: application/json');
        
        $session = \Stripe\Checkout\Session::create([
            'payment_method_types' => ['card'],//カード払い
            'line_items' => [$lineItems],//スペルミス
            'mode' => 'payment',//1回払い
            'success_url' => route('user.items.index'),
            'cancel_url' => route('user.cart.index'),
        ]);

        $publicKey = env('STRIPE_PUBLIC_KEY');

        return view('user.checkout', //viewスペルミス
        compact('session', 'publicKey'));

    }

}
