<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;

class ShopController extends Controller
{
    //
    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function($request, $next){
            // dd($request->route()->parameter('shop')); //結果 shopのid(文字列)
            // dd(Auth::id()); //結果 shopのid(数字)

            $id = $request->route()->parameter('shop'); //shopのid取得
            if(!is_null($id)){ //$idがnullでなかったら
                $shopsOwnerId = Shop::findOrFail($id)->owner->id;
                $shopId = (int)$shopsOwnerId; // キャスト 文字列→数値に型変換
                $ownerId = Auth::id();

                if($shopId !== $ownerId){ // 同じでなかったら
                    abort(404); //404画面表示
                }
            }
            return $next($request);
        });
    }

    public function index()
    {
        $ownerId = Auth::id(); //認証されているid
        $shops = Shop::where('owner_id', $ownerId)->get();//whereは検索条件

        return view('owner.shops.index',
        compact('shops'));
    }

    public function edit($id)
    {
        // dd(Shop::findOrFail($id));
        //結果 他のオーナーのShopが見れてしまう

        $shop = Shop::findOrFail($id);

        return view('owner.shops.edit', compact('shop'));

    }

    public function update(UploadImageRequest $request, $id)
    {
        //
        $request->validate([
            'name' => 'required|string|max:255',
            'information' => 'required|string|max:1000',
            'is_selling' => 'required',
        ]);

        $imageFile = $request->image; //一時保存

        if(!is_null($imageFile) && $imageFile->isValid() ){
            $fileNameToStore = ImageService::upload($imageFile, 'shops');
        }

        $shop = Shop::findOrFail($id);
        $shop->name = $request->name;
        $shop->information = $request->information;
        $shop->is_selling = $request->is_selling;

        if(!is_null($imageFile) && $imageFile->isValid() ){
            $shop->filename = $fileNameToStore;
        }

        $shop->save();

        return redirect()
        ->route('owner.shops.index')
        ->with([
            'message' => '店舗情報を更新しました',
            'status' => 'info',
        ]);
    }
}
