@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('title', '商品一覧')

@section('tabs')
<a href="{{ route('items.index', ['tab' => 'recommend']) }}"
    class="tab-link {{ request('tab') === 'recommend' ? 'is-active' : '' }}">
    おすすめ
</a>

<a href="{{ route('items.index', ['tab' => 'mylist']) }}"
    class="tab-link {{ request('tab') === 'mylist' ? 'is-active' : '' }}">
    マイリスト
</a>
@endsection

@section('content')
@if ($items instanceof \Illuminate\Contracts\Pagination\Paginator && $items->isEmpty())
<p class="no-items">商品がありません</p>
@elseif (isset($items) && $items->isNotEmpty())
<div class="wrap">
    <div class="items-grid {{ request('tab', auth()->check() ? 'mylist' : 'recommend') === 'mylist' ? 'grid-3' : '' }}">
        @foreach ($items as $item)
        {{-- 売れた商品は is-sold クラスを付与 --}}
        <a href="{{ route('items.show', $item) }}" class="item-card {{ $item->purchases_count > 0 ? 'is-sold' : '' }}">
            <div class="thumb">
                @if(!empty($item->image_url))
                <img src="{{ Str::startsWith($item->image_url, ['http://','https://','/storage'])
                                ? $item->image_url
                                : asset('storage/'.$item->image_url) }}"
                                alt="{{ $item->title ?? $item->name }}">
                @else
                <span class="thumb-dummy">商品画像</span>
                @endif
                {{-- SOLDバッジ --}}
                @if ($item->purchases_count > 0)
                <span class="sold-badge">SOLD</span>
                @endif
            </div>

            <div class="item-name">{{ $item->title ?? $item->name }}</div>
        </a>
        @endforeach
    </div>
</div>
@endif

{{-- ページネーション --}}
@if ($items instanceof \Illuminate\Contracts\Pagination\Paginator)
<div class="pager">
    {{ $items->withQueryString()->links() }}
</div>
@endif
@endsection