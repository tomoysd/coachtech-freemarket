@extends('layouts.app')

@section('title', '商品一覧')

@section('tabs')
<a href="{{ route('items.index', ['tab' => 'recommend']) }}"
    class="tab-link {{ request('tab','recommend')==='recommend' ? 'is-active' : '' }}">
    おすすめ
</a>
<a href="{{ route('items.index', ['tab' => 'mylist']) }}"
    class="tab-link {{ request('tab')==='mylist' ? 'is-active' : '' }}">
    マイリスト
</a>
@endsection

@section('content')
@if ($items->isEmpty())
<p class="no-items">商品がありません。</p>
@else
<div class="wrap">
    <div class="items-grid">
        @foreach ($items as $item)
        <a href="{{ route('items.show', $item) }}" class="item-card">
            <div class="thumb">
                @if($item->image_url)
                <img src="{{ $item->image_url }}" alt="{{ $item->title }}">
                @else
                <span class="thumb-dummy">商品画像</span>
                @endif
            </div>
            <div class="item-name">{{ $item->title }}</div>
        </a>
        @endforeach
    </div>
</div>

<div class="pager">
    {{ $items->withQueryString()->links() }}
</div>
@endif
@endsection