@props([
    'title',
    'message' => 'メッセージの初期値',
    'content' => '本文の初期値',
])

<div clsss="border-2 shadow-md w-1/4 p-2">
    <div>{{ $title }}</div>
    <div>画像</div>
    <div>{{ $content }}</div>
    <div>{{ $message }}</div>
</div>