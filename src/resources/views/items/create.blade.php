@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('content')
<div class="sell-container">
    <h2 class="sell-title">商品の出品</h2>

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="sell-form">
        @csrf

        {{-- ✅ バリデーション全体のエラーリスト（errorBag指定） --}}
        @if ($errors->exhibition->any())
        <ul class="error-list">
            @foreach ($errors->exhibition->all() as $msg)
            <li>{{ $msg }}</li>
            @endforeach
        </ul>
        @endif

        {{-- 商品画像 --}}
        <div class="form-group">
            <label for="image">商品画像<span class="required">必須</span></label>
            <input type="file" name="image" id="image">
            @error('image', 'exhibition')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- カテゴリー --}}
        <div class="form-group">
            <label for="category_id">カテゴリー<span class="required">必須</span></label>
            <div class="category-list">
                @foreach ($categories as $category)
                <label>
                    <input type="checkbox" name="category_ids[]"
                        value="{{ $category->id }}"
                        {{ in_array($category->id, old('category_ids', [])) ? 'checked' : '' }}>
                    {{ $category->name }}
                </label>
                @endforeach
            </div>
            @error('category_ids', 'exhibition') <p class="error">{{ $message }}</p> @enderror
            @error('category_ids.*', 'exhibition') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- 商品の状態（セレクトボックス；固定配列） --}}
        <div class="form-group">
            <label for="condition">商品の状態<span class="required">必須</span></label>
            <select id="condition" name="condition" required>
                <option value="">選択してください</option>
                @foreach ($conditions as $cond)
                <option value="{{ $cond }}" {{ old('condition')===$cond ? 'selected' : '' }}>{{ $cond }}</option>
                @endforeach
            </select>
            @error('condition', 'exhibition') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- 商品名 --}}
        <div class="form-group">
            <label for="title">商品名<span class="required">必須</span></label>
            <input type="text" name="title" id="title" value="{{ old('title') }}">
            @error('title', 'exhibition')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- ブランド名 --}}
        <div class="form-group">
            <label for="brand">ブランド名</label>
            <input type="text" name="brand" id="brand" value="{{ old('brand') }}">
            @error('brand', 'exhibition')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 商品の説明 --}}
        <div class="form-group">
            <label for="description">商品の説明<span class="required">必須</span></label>
            <textarea name="description" id="description" rows="5">{{ old('description') }}</textarea>
            @error('description', 'exhibition')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 販売価格 --}}
        <div class="form-group">
            <label for="price">販売価格<span class="required">必須</span></label>
            <input type="number" name="price" id="price" value="{{ old('price') }}" min="1">
            @error('price', 'exhibition')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-submit">出品する</button>
    </form>
</div>
@endsection