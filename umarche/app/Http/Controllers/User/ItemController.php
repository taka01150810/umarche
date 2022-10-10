<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Stock;
use Illuminate\Support\Facades\DB;
use App\Models\PrimaryCategory;
use Illuminate\Support\Facades\Mail;
use App\Mail\TestMail;
use App\Jobs\SendThanksMail;

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

    public function index(Request $request)
    {
        // dd($request);

        //同期的に送信
        // Mail::to('test@example.com') //受信者の指定
        // ->send(new TestMail()); //Mailableクラス

        //非同期的に送信
        // キューにジョブを入れて処理(非同期)
        SendThanksMail::dispatch();

        $categories = PrimaryCategory::with('secondary')
        ->get();

        $products = Product::availableItems()
        ->selectCategory($request->category ?? '0')
        ->searchKeyword($request->keyword)
        ->sortOrder($request->sort)
        ->paginate($request->pagination ?? '20');//パラメータがないときは20

        return view('user.index', 
        compact('products', 'categories'));
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
