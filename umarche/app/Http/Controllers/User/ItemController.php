<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;

// php artisan make:controller User/ItemController で作成
class ItemController extends Controller
{
    //
    public function index(){
        $products = Product::all();
        return view('user.index', compact('products'));
    }
}
