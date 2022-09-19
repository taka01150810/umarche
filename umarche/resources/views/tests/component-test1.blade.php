<x-tests.app>
    <x-slot name="header">ヘッダー1</x-slot>
    コンポーネントテスト1

    <x-tests.card title="タイトル1" content="本文1" :message="$message"/>
    {{-- 初期値を設定せずにcontentやmessageなど使わない場合 --}}
    {{-- <x-tests.card title="タイトル1" /> --}}
    {{-- 結果 エラー -> 初期値を設定する必要がある--}}

    <x-tests.card title="タイトルです" />
</x-tests.app>