@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/items.css') }}">
@endsection

@section('content')
<div class="sell-container">
    <h2 class="sell-title">商品の出品</h2>

    <form action="{{ route('items.store') }}" method="POST" enctype="multipart/form-data" class="sell-form">
        @csrf

        {{-- 商品画像 --}}
        <div class="form-group">
            <label for="image">商品画像<span class="required">必須</span></label>
            <input type="file" name="image" id="image">
            @error('image')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- カテゴリー --}}
        <div class="form-group">
            <label for="category_id">カテゴリー<span class="required">必須</span></label>
            <div class="category-list">
                @foreach ($categories as $category)
                <label>
                    <input type="radio" name="category_id" value="{{ $category->id }}">
                    {{ $category->name }}
                </label>
                @endforeach
            </div>
            @error('category_id')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 商品の状態 --}}
        <div class="form-group">
            <label for="condition_id">商品の状態<span class="required">必須</span></label>
            <select name="condition_id" id="condition_id">
                <option value="">選択してください</option>
                @foreach ($conditions as $condition)
                <option value="{{ $condition->id }}">{{ $condition->name }}</option>
                @endforeach
            </select>
            @error('condition_id')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 商品名 --}}
        <div class="form-group">
            <label for="title">商品名<span class="required">必須</span></label>
            <input type="text" name="title" id="title" value="{{ old('title') }}">
            @error('title')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- ブランド名 --}}
        <div class="form-group">
            <label for="brand">ブランド名</label>
            <input type="text" name="brand" id="brand" value="{{ old('brand') }}">
        </div>

        {{-- 商品の説明 --}}
        <div class="form-group">
            <label for="description">商品の説明<span class="required">必須</span></label>
            <textarea name="description" id="description" rows="5">{{ old('description') }}</textarea>
            @error('description')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 販売価格 --}}
        <div class="form-group">
            <label for="price">販売価格<span class="required">必須</span></label>
            <input type="number" name="price" id="price" value="{{ old('price') }}" min="1">
            @error('price')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-submit">出品する</button>
    </form>
</div>
@endsection