<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LiftCycleTestController extends Controller
{
    //
    public function showServiceProviderTest()
    {
        $encrypt = app()->make('encrypter');
        $password = $encrypt->encrypt('passwordです');

        $sample = app()->make('serviceProviderTest');
        dd($sample);//結果 サービスプロバイダのテストです

        // dd($password, $encrypt->decrypt($password));
        //結果 https://gyazo.com/2ce27a23c38a2c6a08d7a46b1ed42845
    }

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

        //サービスコンテナなしのパターン
        $message = new Message();
        $sample = new Sample($message);
        $sample->run(); //結果 サービスコンテナメッセージ表示

        //サービスコンテナありのパターン -> インスタンス化する必要なし
        app()->bind('sample', Sample::class);
        $sample = app()->make('sample');
        $sample->run();//結果 サービスコンテナメッセージ表示
        
    }
}

class Sample
{
    public $message;

    public function __construct(Message $message){
        $this->message = $message;
    }

    public function run(){
        $this->message->send();
    }
}

class Message
{
    public function send(){
        echo('サービスコンテナメッセージ表示');
    }
}