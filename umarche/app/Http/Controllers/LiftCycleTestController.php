<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LiftCycleTestController extends Controller
{
    //
    public function showServiceContainerTest()
    {
        // dd(app());
        //結果 https://gyazo.com/1e0b92b10b33dfd0230e20db04c90d97
        //bindings: array:71  ->  71のサービスが設定されている

        //サービスコンテナに登録
        app()->bind('lifeCycleTest', function(){
            return 'ライフサイクルテストです';
        });
        // dd(app());
        //結果 https://gyazo.com/5114deced57f7e2276f83c5c346e5201
        //bindings: array:72

        //サービスコンテナから取り出す
        $test = app()->make('lifeCycleTest');
        // dd($test);//結果 ライフサイクルテストです

    }
}
