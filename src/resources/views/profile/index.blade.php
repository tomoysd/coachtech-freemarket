@extends('layouts.app')

@push('head')
<link rel="stylesheet" href="{{ asset('css/mypage.css') }}">
@endpush

@section('content')
<div class="mypage">

    {{-- ヘッダー：アイコン・名前・編集ボタン --}}
    <section class="mp-header">
        <div class="mp-avatar">
            @php $avatar = optional($user->profile)->avatar_url; @endphp
            @if($avatar)
            <img src="{{ $avatar }}" alt="プロフィール画像">
            @else
            <div class="avatar-ph"></div>
            @endif
        </div>

        <div class="mp-meta">
            <h1 class="mp-name">{{ $user->name }}</h1>
            <a href="{{ route('profile.edit') }}" class="mp-edit">プロフィールを編集</a>
        </div>
    </section>

    {{-- ---- JSなしラジオタブ ---- --}}
    <section class="mp-tabs">
        {{-- ラジオ（非表示） --}}
        <input type="radio" name="tab" id="tab-listed" class="tab-radio" checked>
        <input type="radio" name="tab" id="tab-purchased" class="tab-radio">

        {{-- タブラベル --}}
        <div class="tab-labels" role="tablist" aria-label="プロフィールのタブ">
            <label for="tab-listed" class="tab-label" role="tab" aria-controls="panel-listed">出品した商品</label>
            <label for="tab-purchased" class="tab-label" role="tab" aria-controls="panel-purchased">購入した商品</label>
        </div>

        {{-- パネル：出品 --}}
        <div id="panel-listed" class="tab-panel">
            @if($listedItems->count())
            <ul class="card-grid">
                @foreach($listedItems as $item)
                <li class="card">
                    <a href="{{ route('items.show', $item->id) }}" class="card-link">
                        <div class="thumb">
                            @if(!empty($item->image_url))
                            <img src="{{ $item->image_url }}" alt="{{ $item->title }}" />
                            @else
                            <div class="thumb-ph"></div>
                            @endif
                        </div>
                        <p class="title">{{ $item->title ?? $item->name }}</p>
                    </a>
                </li>
                @endforeach
            </ul>
            <div class="pager">{{ $listedItems->onEachSide(1)->links() }}</div>
            @else
            <p class="empty">出品した商品はまだありません。</p>
            @endif
        </div>

        {{-- パネル：購入 --}}
        <div id="panel-purchased" class="tab-panel">
            @if($purchased->count())
            <ul class="card-grid">
                @foreach($purchased as $pv)
                @if($pv->item)
                <li class="card">
                    <a href="{{ route('items.show', $pv->item->id) }}" class="card-link">
                        <div class="thumb">
                            @php $item = $pv->item; @endphp
                            @if($item && !empty($item->image_url))
                            <img src="{{ $item->image_url }}" alt="{{ $item->title }}" />
                            @else
                            <div class="thumb-ph"></div>
                            @endif
                        </div>
                        <p class="title">{{ $pv->item->title ?? $pv->item->name }}</p>
                    </a>
                </li>
                @endif
                @endforeach
            </ul>
            <div class="pager">{{ $purchased->onEachSide(1)->links() }}</div>
            @else
            <p class="empty">購入した商品はまだありません。</p>
            @endif
        </div>
    </section>
</div>
@endsection