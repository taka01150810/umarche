<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

// php artisan make:controller User/ItemController で作成
class ItemController extends Controller
{
    //
    public function index(){
        return view('user.index');
    }
}
