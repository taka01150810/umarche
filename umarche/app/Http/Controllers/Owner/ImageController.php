<?php

namespace App\Http\Controllers\Owner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Image;
use App\Models\Product;
use App\Http\Requests\UploadImageRequest;
use App\Services\ImageService;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:owners');

        $this->middleware(function($request, $next){

            $id = $request->route()->parameter('image'); //imageのid取得
            if(!is_null($id)){ //$idがnullでなかったら
                $imagesOwnerId = Image::findOrFail($id)->owner->id;
                $imageId = (int)$imagesOwnerId; // キャスト 文字列→数値に型変換
                $ownerId = Auth::id();

                if($imageId !== $ownerId){ // 同じでなかったら
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
        //
        $ownerId = Auth::id(); //認証されているid
        $images = Image::where('owner_id', $ownerId)
        ->orderBy('updated_at', 'desc') //降順
        ->paginate(20);

        return view('owner.images.index',
        compact('images'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return view('owner.images.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(UploadImageRequest $request)
    {
        //
        $imageFiles = $request->file('files');//配列でファイルを取得

        if(!is_null($imageFiles)){
            foreach($imageFiles as $imageFile){
                $fileNameToStore = ImageService::upload($imageFile, 'products');
                Image::create([
                    'owner_id' => Auth::id(),
                    'filename' => $fileNameToStore
                ]);
            }
        }

        return redirect()
        ->route('owner.images.index')
        ->with([
            'message' => '画像登録を実施しました',
            'status' => 'info',
        ]);
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
        $image = Image::findOrFail($id);

        return view('owner.images.edit', compact('image'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $request->validate([
            'title' => 'string|max:255',
        ]);

        $image = Image::findOrFail($id);
        $image->title = $request->title;
        $image->save();

        return redirect()
        ->route('owner.images.index')
        ->with([
            'message' => '画像を更新しました',
            'status' => 'info',
        ]);


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
        $image = Image::findOrFail($id);

        /*
        Productで選択しているImageを削除しようとすると外部キーエラー発生
        画像を使っているか確認して
        対策1. Product側で画像の選択を外してとメッセージを出す
        対策2. Productのimage1をnullに変更
        
        今回は対策2で対応
        */
        // 削除したい画像をProductで使っているかの確認
        $imageInProducts = Product::where('image1', $image->id)
        ->get();

        // 使っていたらimage1をチェックして nullに変更
        if($imageInProducts){
            $imageInProducts->each(function($product) use($image){
                if($product->image1 === $image->id){
                    $product->image1 = null;
                    $product->save();
                }
            });
        }

        //テーブル情報を削除する前にStorageフォルダ内画像ファイルを削除
        $filePath = 'public/products/' . $image->filename;

        if(Storage::exists($filePath)){
            Storage::delete($filePath);
        }

        Image::findOrFail($id)->delete();

        return redirect()
        ->route('owner.images.index')
        ->with([
            'message' => '画像を削除しました',
            'status' => 'alert',
        ]);
    }
}
