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
@if ($items instanceof \Illuminate\Contracts\Pagination\Paginator && $items->isEmpty())
<p class="no-items">商品がありません。</p>
@elseif (is_array($items) || $items instanceof \Illuminate\Support\Collection)
@if ($items->isEmpty())
<p class="no-items">商品がありません。</p>
@endif
@endif
<div class="wrap">
    <div class="items-grid">
        @foreach ($items as $item)
        <a href="{{ route('items.show', $item) }}" class="item-card">
            <div class="thumb">
                @if(!empty ($item->image_url) || !empty($item->image_path))
                <img src="{{ $item->image_url ?? asset('storage/'.$item->image_path) }}" alt="{{ $item->title }}">
                @else
                {{-- ここで購入済み判定 --}}
                @if($item->purchase)
                <div class="sold-badge">SOLD</div>
                @endif
                <span class="thumb-dummy">商品画像</span>
                @endif
            </div>
            <div class="item-name">{{ $item->title }}</div>
        </a>
        @endforeach
    </div>
</div>

@if ($items instanceof \Illuminate\Contracts\Pagination\Paginator)
<div class="pager">
    {{ $items->withQueryString()->links() }}
</div>
@endif
@endsection