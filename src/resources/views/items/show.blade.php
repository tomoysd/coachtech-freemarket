@extends('layouts.app')

@section('title', $item->title ?? $item->name ?? '商品詳細')

@section('content')
<div class="item-detail">
    <div class="item-detail__container">

        <h1 class="item-detail__title">
            {{ $item->title ?? $item->name ?? '商品名がここに入る' }}
        </h1>

        <div class="item-detail__body">
            {{-- 左：画像 --}}
            <div class="item-detail__media">
                @php $imageUrl = $item->image_url ?? null; @endphp
                @if ($imageUrl)
                <img src="{{ asset($imageUrl) }}" alt="{{ $item->title ?? $item->name ?? '商品画像' }}">
                @else
                <div class="item-detail__image--placeholder">商品画像</div>
                @endif
            </div>

            {{-- 右：縦長カード --}}
            <aside class="item-detail__panel">
                <div class="item-detail__panel-card">

                    {{-- 価格 + いいね/コメント --}}
                    <div class="item-detail__price-row">
                        <div class="item-detail__price">
                            ￥{{ number_format($item->price ?? 47000) }} <span class="item-detail__tax">(税込)</span>
                        </div>

                        <div class="item-detail__stats">
                            @auth
                            <form action="{{ route('items.like.toggle', ['item_id' => $item->id]) }}" method="POST">
                                @csrf
                                <button type="submit"
                                    class="item-detail__icon-btn {{ $likedByMe ? 'is-active' : '' }}"
                                    aria-label="お気に入り">
                                    ★
                                </button>
                            </form>
                            @else
                            <button type="button" class="item-detail__icon-btn" aria-label="お気に入り" disabled>★</button>
                            @endauth
                            <span class="item-detail__stat-num">{{ $likesCount }}</span>

                            <span class="item-detail__icon-badge" aria-hidden="true">💬</span>
                            <span class="item-detail__stat-num">{{ $comments->count() }}</span>
                        </div>
                    </div>

                    {{-- 購入導線 --}}
                    @auth
                    <a class="item-detail__cta" href="{{ route('purchase.create', $item) }}">購入手続きへ</a>
                    @else
                    <a class="item-detail__cta" href="{{ route('login') }}">購入手続きへ</a>
                    @endauth>

                    {{-- 商品説明 --}}
                    <section class="item-detail__section">
                        <h2 class="item-detail__section-title">商品説明</h2>
                        <div class="item-detail__description">
                            {{-- ブランド名も表示（無ければ非表示） --}}
                            @php $brand = $item->brand ?? $item->brand_name ?? null; @endphp
                            @if($brand)
                            <div class="item-detail__brand">ブランド名：{{ $brand }}</div>
                            @endif
                            {!! nl2br(e($item->description ?? "カラー：グレー\n新品。\n商品の状態は良好です。傷はありません。\n購入後、即発送いたします。")) !!}
                        </div>
                    </section>

                    {{-- 商品の情報 --}}
                    <section class="item-detail__section">
                        <h2 class="item-detail__section-title">商品の情報</h2>
                        <dl class="item-detail__props">
                            <div class="item-detail__prop">
                                <dt>カテゴリー</dt>
                                <dd class="item-detail__chips">
                                    @forelse ($categories as $cat)
                                    <span class="item-detail__chip">{{ is_object($cat) ? ($cat->name ?? $cat->title ?? 'カテゴリ') : $cat }}</span>
                                    @empty
                                    <span class="item-detail__chip is-muted">未分類</span>
                                    @endforelse
                                </dd>
                            </div>
                            <div class="item-detail__prop">
                                <dt>商品の状態</dt>
                                <dd><span class="item-detail__chip">{{ $item->condition_label ?? '新品' }}</span></dd>
                            </div>
                        </dl>
                    </section>

                    {{-- コメント一覧 --}}
                    <section class="item-detail__section">
                        <h2 class="item-detail__section-title">コメント <span class="item-detail__count">({{ $comments->count() }})</span></h2>
                        <ul class="item-detail__comments">
                            @forelse ($comments as $comment)
                            <li class="item-detail__comment">
                                <div class="item-detail__avatar" aria-hidden="true"></div>
                                <div class="item-detail__comment-main">
                                    <div class="item-detail__comment-meta">
                                        <span class="item-detail__author">{{ $comment->user->name ?? 'user' }}</span>
                                        <time class="item-detail__time">{{ optional($comment->created_at)->format('Y/m/d H:i') }}</time>
                                    </div>
                                    <p class="item-detail__comment-body">{{ $comment->body }}</p>
                                </div>
                            </li>
                            @empty
                            <li class="item-detail__empty">まだコメントはありません。</li>
                            @endforelse
                        </ul>
                    </section>

                    @auth
                    <section class="item-detail__section">
                        <h3 class="item-detail__section-title--sub">商品のコメント</h3>
                        <form action="{{ route('items.comments.store', $item) }}" method="POST" class="item-detail__comment-form">
                            @csrf
                            <textarea name="body" class="item-detail__textarea" rows="4" placeholder="コメントを書いてください">{{ old('body') }}</textarea>
                            @error('body') <p class="item-detail__error">{{ $message }}</p> @enderror
                            <button type="submit" class="item-detail__submit">コメントを送信する</button>
                        </form>
                    </section>
                    @endauth

                </div>
            </aside>
        </div>

    </div>
</div>
@endsection