<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Product;
use App\Models\Image;
use App\Models\PrimaryCategory;
use App\Models\Shop;
use App\Models\Owner;
use App\Models\Stock;
use Throwable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use App\Http\Requests\ProductRequest;

class ProductController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function($request, $next){

            $id = $request->route()->parameter('product'); //imageのid取得
            if(!is_null($id)){ //$idがnullでなかったら
                $productsOwnerId = Product::findOrFail($id)->shop->owner->id;
                $productId = (int)$productsOwnerId; // キャスト 文字列→数値に型変換
                $ownerId = Auth::id();

                if($productId !== $ownerId){ // 同じでなかったら
                    abort(404); //404画面表示
                }
            }
            return $next($request);
        });
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //SQLが商品画像の情報一つにつき一つ発行されてしまう(N+1問題)
        //$products = Owner::findOrFail(Auth::id())->shop->product;

        // Eager(積極的) Loading
        $ownerInfo = Owner::with('shop.product.imageFirst')
        ->where('id', Auth::id())
        ->get();
        // dd($ownerInfo);
        
        // foreach($ownerInfo as $owner){
        //     // dd($owner->shop->product);
        //     foreach($owner->shop->product as $product)
        //     {
        //         dd($product->imageFirst->filename);
        //     }
        // }

        return view('owner.products.index',
        compact('ownerInfo'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $shops = Shop::where('owner_id', Auth::id())
        ->select('id', 'name')
        ->get();

        $images = Image::where('owner_id', Auth::id())
        ->select('id', 'title', 'filename')
        ->orderBy('updated_at', 'desc')
        ->get();

        $categories = PrimaryCategory::with('secondary')
        ->get();

        return view('owner.products.create', compact('shops', 'images', 'categories'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ProductRequest $request)
    {
        //
        // dd($request);
        $request->validate([
            'name' => 'required|string|max:50',
            'information' => 'required|string|max:1000',
            'price' => 'required|integer',
            'sort_order' => 'nullable|integer',
            'quantity' => 'required|integer',
            'shop_id' => 'required|exists:shops,id',
            'category' => 'required|exists:secondary_categories,id',
            'image1' => 'nullable|exists:images,id',
            'is_selling' => 'required',
        ]);

        //トランザクションでエラー時は例外発生
        try{
            DB::transaction(function() use ($request){
                $product = Product::create([
                    'name' => $request->name,
                    'information' => $request->information,
                    'price' => $request->price,
                    'sort_order' => $request->sort_order,
                    'quantity' => $request->quantity,
                    'shop_id' => $request->shop_id,
                    'secondary_category_id' => $request->category,
                    'image1' => $request->image1,
                    'is_selling' => $request->is_selling,
                ]);

                Stock::create([
                    'product_id' => $product->id,
                    'type' => 1,
                    'quantity' => $request->quantity,
                ]);

            }, 2);//NGの時に2回試す
        }catch(Throwable $e){// PHP7からThrowableで例外取得
            Log::error($e);//ログはstorage/logs/laravel.logファイル内に保存
            throw $e;
        }

        return redirect()
        ->route('owner.products.index')
        ->with('message', '商品登録を実施しました');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $product = Product::findOrFail($id);
        
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');

        $shops = Shop::where('owner_id', Auth::id())
        ->select('id', 'name')
        ->get();

        $images = Image::where('owner_id', Auth::id())
        ->select('id', 'title', 'filename')
        ->orderBy('updated_at', 'desc')
        ->get();

        $categories = PrimaryCategory::with('secondary')
        ->get();

        return view('owner.products.edit', compact('product', 'quantity', 'shops', 'images', 'categories'));

    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(ProductRequest $request, $id)
    {
        //
        $request->validate([
            'current_quantity' => 'required|integer',
        ]);

        $product = Product::findOrFail($id);
        $quantity = Stock::where('product_id', $product->id)
        ->sum('quantity');

        /*
        画面表示後に在庫数が変わっている可能性がある
        (Editとupdateの間でユーザーが購入した場合など)
        在庫が同じか確認し違っていたらeditに戻す (楽観的ロック) 
        */
        if($request->current_quantity !== $quantity){
            $id = $request->route()->parameter('product');
            return redirect()
            ->route('owner.products.edit', [ 'product' => $id ])
            ->with([
                'message' => '在庫数が変更されています。再度確認してください。',
                'status' => 'alert',
            ]);
        } else {

        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
