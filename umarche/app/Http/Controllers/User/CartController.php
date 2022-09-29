<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Cart;

// php artisan make:controller User/CartControllerで生成
class CartController extends Controller
{
    //
    public function add(Request $request)
    {
        dd($request);
    }
}
