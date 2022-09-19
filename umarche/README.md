publicフォルダに直接置く...初期ファイル
storageフォルダ...フォルダ内画像はgitHubにアップしない(アップデートされた画像など)

storageフォルダはそのままでは使えない
public側で使えるように
->php artisan storage:link でpublic/storageリンクを生成
asset() ヘルパ関数でpublic内のファイルを指定

