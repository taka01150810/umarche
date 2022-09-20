<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Owner;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class OwnersController extends Controller
{
    /*
    ログイン済みユーザーのみ表示させるため
    コンストラクタで下記を設定
    */
    public function __construct(){
        $this->middleware('auth:admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data_now = Carbon::now();
        $data_parse = Carbon::parse(now());
        echo $data_now;//結果 2022-09-20 20:01:24
        echo $data_parse;//結果 2022-09-20 20:01:24
        //エロクアント
        $e_all = Owner::all();//返り値はEloquentCollection

        //クエリビルダ
        $q_get = DB::table('owners')->select('name', 'created_at')->get();//返り値は Collection
        // $q_first = DB::table('owners')->select('name')->first();//返り値は stdClass

        //コレクション
        // $c_test = collect([
        //     'name' => 'テスト',
        // ]);//返り値は Collection

        // dd($e_all, $q_get, $q_first, $c_test);
        //結果 https://gyazo.com/376f40d702d5e22307a7790765b97a91
        return view('admin.owners.index',
        compact('e_all', 'q_get'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
    }
}
