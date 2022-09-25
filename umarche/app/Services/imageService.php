<?php
namespace App\Services;
Use InterventionImage;
Use Illuminate\Support\Facades\Storage;


Class ImageService
{
    public static function upload($imageFile, $folderName)
    {
        // dd($imageFile['image']);
        //配列かどうかの確認
        if(is_array($imageFile)){
            $file = $imageFile['image'];// 配列なので[key]で取得
        } else {
            $file = $imageFile;
        }

        $fileName = uniqid(rand().'_');//重複しないファイル名作成
        $extension = $file->extension();//拡張子を取得
        $fileNameToStore = $fileName. '.' . $extension;

        $resizedImage = InterventionImage::make($file)
        ->resize(1920, 1080)
        ->encode();

        Storage::put('public/' . $folderName . '/' . $fileNameToStore, $resizedImage);
        
        return $fileNameToStore;
    }
}