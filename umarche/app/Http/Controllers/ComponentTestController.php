<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

//php artisan make:controller ComponentTestControllerで作成
class ComponentTestController extends Controller
{
    //
    public function showComponent1(){
        $message = 'メッセージ1です';

        return view('tests.component-test1', 
        compact('message'));
    }

    public function showComponent2(){
        return view('tests.component-test2');
    }
}
