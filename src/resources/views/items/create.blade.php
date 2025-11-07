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
            <label for="image" class="required">商品画像</label>
            <div class="image-drop">
                <input type="file" name="image" id="image" class="file-input">
                <label for="image" class="file-btn">画像を選択する</label>
            </div>
            @error('image','exhibition')<p class="error">{{ $message }}</p>@enderror
        </div>

        {{-- カテゴリー --}}
        <div class="form-group">
            <label for="category_id" class="required">カテゴリー</label>
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
            <label for="condition" class="required">商品の状態</label>
            <select id="condition" name="condition" class="select">
                <option value="" selected disabled>選択してください</option>
                @foreach ($conditions as $key => $label) {{-- $key=1..6, $label='新品・未使用' 等 --}}
                <option value="{{ $key }}" {{ old('condition') == $key ? 'selected' : '' }}>
                    {{ $label }}
                </option>
                @endforeach
            </select>
            @error('condition', 'exhibition') <p class="error">{{ $message }}</p> @enderror
        </div>

        {{-- 商品名 --}}
        <div class="form-group">
            <label for="title" class="required">商品名</label>
            <input type="text" name="title" id="title" value="{{ old('title') }}" class="input">
            @error('title', 'exhibition')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- ブランド名 --}}
        <div class="form-group">
            <label for="brand">ブランド名</label>
            <input type="text" name="brand" id="brand" value="{{ old('brand') }}" class="input">
            @error('brand', 'exhibition')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 商品の説明 --}}
        <div class="form-group">
            <label for="description" class="required">商品の説明</label>
            <textarea name="description" id="description" rows="5" class="textarea">{{ old('description') }}</textarea>
            @error('description', 'exhibition')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        {{-- 販売価格 --}}
        <div class="form-group">
            <label for="price" class="required">販売価格</label>
            <div class="price-wrap">
                <span class="price-prefix">¥</span>
                <input type="number" name="price" id="price" value="{{ old('price') }}" class="input price-input" min="1">
            </div>
            @error('price', 'exhibition')
            <p class="error">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-submit">出品する</button>
    </form>

    @push('scripts')
    <script>
        const f = document.getElementById('image');
        if (f) {
            f.addEventListener('change', () => {
                document.getElementById('fileName').textContent = f.files[0]?.name ?? '選択されていません';
            });
        }
    </script>
    @endpush
</div>
@endsection