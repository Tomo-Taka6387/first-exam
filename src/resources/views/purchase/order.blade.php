@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase/order.css')}}?v={{ time() }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">
@endsection

@section('content')
<div class="order-box">
    <form action="{{ route('purchase.store', ['item' => $item->id]) }}" class="order-page_form" method="POST">
        @csrf

        <div class="order-left">
            <div class="product-row">
                <img class="order-item_img" src="{{ asset('storage/' . $item->img_url) }}" alt="{{ $item->name }}">
                <div class="product-info">
                    <div class="order-price">{{ $item->name }}</div>
                    <div class="order-price">¥{{ $item->price }}</div>
                </div>
            </div>

            <div class="order-form">
                <p class="order-title">支払い方法</p>
                <select name="paymethod">
                    <option value="" disabled {{ old('paymethod') ? '' : 'selected' }}>選択してください</option>
                    <option value="card" {{ old('paymethod') == 'card' ? 'selected' : '' }}>カード払い</option>
                    <option value="convenience_store" {{ old('paymethod') == 'convenience_store' ? 'selected' : '' }}>コンビニ払い</option>
                </select>
                <p class="order-error">
                    @error('paymethod') {{ $message }} @enderror
                </p>
            </div>

            <div class="address-row">
                <div class="order-address">
                    <p class="order-title">配送先</p>
                    <p class="shipping-address_postcode">〒{{ $address['postcode'] }}</p>
                    <p class="shipping-address_address">{{ $address['address'] }}</p>
                    <p class="shipping-address_building">{{ $address['building'] }}</p>
                </div>
                <a href="{{ url('/purchase/address/' . $item->id) }}" class="address-change_btn">変更する</a>
            </div>
        </div>

        <div class="order-right">
            <div class="order-information">
                <div class="information-box">
                    <span class="information-title">商品代金</span>
                    <span class="information">¥{{ $item->price }}</span>
                </div>
                <div class="information-box">
                    <span class="information-title">支払い方法</span>
                    <span class="information">
                        {{ $selectedPaymethod !== '' ? ($paymentMethods[$selectedPaymethod] ?? '') : '選択してください' }}
                    </span>
                </div>
            </div>

            <button type="submit" class="order-btn">購入する</button>
        </div>
    </form>
</div>

@endsection