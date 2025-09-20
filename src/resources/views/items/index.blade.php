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
<div class="item-grid">
    @foreach ($items as $item)
    <div class="item-card">
        <a href="{{ route('items.show', $item) }}" class="item-link">
            <div class="item-image">
                @if($item->image_url)
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

<div class="pager">
    {{ $items->withQueryString()->links() }}
</div>
@endif
@endsection