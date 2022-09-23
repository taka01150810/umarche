<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Shop;
use Illuminate\Support\Facades\Storage;
use InterventionImage;

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

    public function update(Request $request, $id)
    {
        //
        $imageFile = $request->image; //一時保存

        if(!is_null($imageFile) && $imageFile->isValid() ){
            //リサイズしないパターン(putFileでファイル名生成)
            // Storage::putFile('public/shops', $imageFile);

            //リサイズありのパターン
            $fileName = uniqid(rand().'_');//重複しないファイル名作成
            $extension = $imageFile->extension();//拡張子を取得
            $fileNameToStore = $fileName. '.' . $extension;

            $resizedImage = InterventionImage::make($imageFile)
            ->resize(1920, 1080)
            ->encode();

            // dd($imageFile, $resizedImage);
            //結果 https://gyazo.com/4dcde9f1940289ff813577b039dbeae8
            
            //Storage:put フォルダを作成、ファイル名を指定して保存できる
            Storage::put('public/shops/' . $fileNameToStore, $resizedImage );
        }

        return redirect()->route('owner.shops.index');
    }
}
