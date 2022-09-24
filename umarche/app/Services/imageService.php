<?php
namespace App\Services;
Use InterventionImage;
Use Illuminate\Support\Facades\Storage;


Class ImageService
{
    public static function upload($imageFile, $folderName)
    {
        $fileName = uniqid(rand().'_');//重複しないファイル名作成
        $extension = $imageFile->extension();//拡張子を取得
        $fileNameToStore = $fileName. '.' . $extension;

        $resizedImage = InterventionImage::make($imageFile)
        ->resize(1920, 1080)
        ->encode();

        Storage::put('public/' . $folderName . '/' . $fileNameToStore, $resizedImage);
        
        return $fileNameToStore;
    }
}