@extends('layouts.app')

@section('css')
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="{{ asset('css/purchase/sell.css')}}?v={{ time() }}">
@endsection

@section('content')
<div class="sell-page">
    <h2 class="sell-page_title">商品の出品</h2>

    <form class="sell-page_form" action="{{ route('items.store') }}" method="post" enctype="multipart/form-data">
        @csrf
        <p class="sell-img_title">商品画像</p>
        <div class="sell-img_outer">
            <label for="img_url" class="sell-img_box">画像を選択する</label>
            <input class="sell-form__input-file" type="file" name="img_url" id="img_url" accept="image/*" hidden>
        </div>
        <p class=" sell-form__error-message">
            @error('img_url')
            {{ $message }}
            @enderror
        </p>

        <div class="sell-form_option">
            <h3 class="sell-form_subtitle">商品の詳細</h3>
            <div class="sell-form__category">
                <h4 class="category_title">カテゴリー</h4>
                <div class="category-button-wrap">

                    @foreach ($categories as $category)
                    <input class="category_radio" type="checkbox" name="category_id[]" id="category_{{ $category->id }}" value="{{ $category->id }}">
                    <label for="category_{{ $category->id }}" class="category_btn">{{ $category->category }}</label>
                    @endforeach
                </div>
                <p class="sell-form__error-message">
                    @error('category_id')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <h4 class="category_title">商品の状態</h4>
            <select class="sell-form_condition" name="condition_id" id="condition_id">
                <option value="">選択してください</option>
                @foreach ($conditions as $condition)
                <option value="{{ $condition->id }}">{{ $condition->condition }}</option>
                @endforeach
            </select>
            <p class="sell-form__error-message">
                @error('condition_id')
                {{ $message }}
                @enderror
            </p>
        </div>


        <div class="sell-form__box">
            <h3 class="sell-form_subtitle">商品名と説明</h3>
            <div class="sell-form__group">
                <label class="sell-form__label" for="name">商品名</label>
                <input class="sell-form__input" type="text" name="name" id="name">
                <p class="sell-form__error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label" for="brand">ブランド名</label>
                <input class="sell-form__input" type="text" name="brand" id="brand">
                <p class="sell-form__error-message">
                    @error('brand')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell-form__group">
                <label class="sell-form__label" for="description">商品の説明</label>
                <textarea class="sell-form__textarea" name="description" id="description"></textarea>
                <p class="sell-form__error-message">
                    @error('description')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="sell-form__group price-group">
                <label class="sell-form__label" for="price">販売価格</label>
                <div class="price-input_wrapper">
                    <span class="yen">¥</span>
                    <input class="sell-form__input price-input" type="text" name="price" id="price">
                </div>
                <p class="sell-form__error-message">
                    @error('price')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <button class="sell-form_btn">出品する</button>
        </div>
    </form>
</div>
@endsection