publicフォルダに直接置く...初期ファイル
storageフォルダ...フォルダ内画像はgitHubにアップしない(アップデートされた画像など)

storageフォルダはそのままでは使えない
public側で使えるように
->php artisan storage:link でpublic/storageリンクを生成
asset() ヘルパ関数でpublic内のファイルを指定

php artisan tinkerで

App\Models\Owner::find(1)->shop;
App\Models\Owner::find(1)->shop->name;
->Ownerに紐づくShop情報を取得

App\Models\Shop::find(1)->owner;
App\Models\Shop::find(1)->owner->email;
->Shopに紐づくOwner情報を取得
