@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/profile/edit.css')}}?v={{ time() }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">
@endsection

@section('content')
<div class="profile_show-form">
    <h2 class="profile_show-form_title">プロフィール設定</h2>
    <div class="profile-form__inner">
        <form class="profile-form__form" action="{{ route('mypage.edit.update') }}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="mode" value="edit">

            <div class="profile-form__group image-upload-group">
                <div class="image-upload-wrapper">
                    <img class="profile-form__image-circle" src="{{ $profile->img_url ? asset('storage/' . $profile->img_url) : '' }}">
                    <label for="img_url" class="custom-file-label">画像を選択する</label>
                    <input class="profile-form__input-file" type="file" name="img_url" id="img_url">
                </div>

                    @error('img_url')
                    <p class=" profile-form__error-message">{{ $message }}</p>
                    @enderror
            </div>

            <div class="profile-form__group">
                <label class="profile-form__label" for="name">ユーザー名</label>
                <input class="profile-form__input" type="text" name="name" id="name" value="{{ old('name',$user->name) }}">
                <p class="profile-form__error-message">
                    @error('name')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="profile-form__group">
                <label class="profile-form__label" for="postcode">郵便番号</label>
                <input class="profile-form__input" type="text" name="postcode" id="postcode" value="{{ old('postcode',$profile->postcode) }}">
                <p class="profile-form__error-message">
                    @error('postcode')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="profile-form__group">
                <label class="profile-form__label" for="address">住所</label>
                <input class="profile-form__input" type="text" name="address" id="address" value="{{ old('address',$profile->address) }}">
                <p class=" profile-form__error-message">
                    @error('address')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <div class="profile-form__group">
                <label class="profile-form__label" for="building">建物名</label>
                <input class="profile-form__input" type="text" name="building" id="building" value="{{ old('building',$profile->building) }}">
                <p class=" profile-form__error-message">
                    @error('building')
                    {{ $message }}
                    @enderror
                </p>
            </div>

            <button class="profile-form__btn" type="submit">更新する</button>

        </form>
    </div>
</div>
@endsection