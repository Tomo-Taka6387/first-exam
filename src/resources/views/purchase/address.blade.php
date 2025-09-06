@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase/address.css')}}?v={{ time() }}">
@endsection

@section('content')
<div class="profile_show-form">
    <h2 class="profile_show-form_title">住所の変更</h2>

    <div class="profile-form__inner">
        <form class="profile-form__form" action="{{ route('purchase.address.update', ['item' => $item->id]) }}" method="post">
            @csrf
            <div class="profile-form__group">
                <label class="profile-form__label" for="sending_postcode">郵便番号</label>
                <input class="profile-form__input" type="text" name="sending_postcode" id="sending_postcode" value="{{ old('sending_postcode', $address['postcode'] ?? '' ) }}">
                <p class="profile-form__error-message">
                    @error('sending_postcode')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="profile-form__group">
                <label class="profile-form__label" for="sending_address">住所</label>
                <input class="profile-form__input" type="text" name="sending_address" id="sending_address" value="{{ old('sending_address', $address['address'] ?? '' ) }}">
                <p class="profile-form__error-message">
                    @error('sending_address')
                    {{ $message }}
                    @enderror
                </p>
            </div>
            <div class="profile-form__group">
                <label class="profile-form__label" for="sending_building">建物名</label>
                <input class="profile-form__input" type="text" name="sending_building" id="sending_building" value="{{ old('sending_building', $address['building'] ?? '' ) }}">
            </div>
            <button type="submit" class="profile-form__btn">更新する</button>
        </form>
    </div>
</div>
@endsection