@extends('layouts.app')

@section('title', '商品一覧')

{{-- タブ部分 --}}
@section('tabs')
<a href="{{ route('items.index', ['tab' => 'recommend']) }}" class="{{ request('tab','recommend')==='recommend' ? 'is-active' : '' }}">おすすめ</a>
<a href="{{ route('items.index', ['tab' => 'mylist']) }}" class="{{ request('tab')==='mylist' ? 'is-active' : '' }}">マイリスト</a>
@endsection

{{-- 商品一覧 --}}
@section('content')
<div class="item-grid">
    @foreach($items as $item)
    <div class="item-card">
        <a href="{{ route('items.show', $item) }}">
            <div class="item-image">
                @if(!empty($item->image_url))
                <img src="{{ $item->image_url }}" alt="{{ $item->title }}">
                @elseif(!empty($item->image_path))
                <img src="{{ asset('storage/'.$item->image_path) }}" alt="{{ $item->title }}">
                @else
                <div class="placeholder">商品画像</div>
                @endif
            </div>
            <div class="item-title">{{ $item->title }}</div>
        </a>
    </div>
    @endforeach
</div>
@endsection