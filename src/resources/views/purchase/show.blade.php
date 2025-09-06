@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase/show.css')}}?v={{ time() }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">
@endsection

@section('content')
<div class="mypage-container">

    <div class="profile-header">
        <img class="profile-image" src="{{ asset('storage/' . $profile->img_url) }}">
        <div class="profile-info">
            <h2 class="profile-name">{{ $user->name }}</h2>
            <a href="{{ route('mypage.edit') }}" class="edit-button">プロフィール編集</a>
        </div>
    </div>

    <div class="item-toggle">
        <a href="{{ url('/mypage?page=sell') }}" class="tab {{ $page === 'sell' ? 'active' : '' }}">出品した商品</a>
        <a href="{{ url('/mypage?page=buy') }}" class="tab {{ $page === 'buy' ? 'active' : '' }}">購入した商品</a>
    </div>

    <div class="item-box">
        @if ($page === 'sell')
        <div class="item-list">
            @foreach ($sellingItems as $item)
            @php
            $sold = $purchasedItems->firstWhere('item_id', $item->id);
            @endphp
            <a href="{{ $sold ? '#' : route('items.show', ['item' => $item->id]) }}">
                <div class="item-card">
                    <div class="item-image" style="background-image: url('{{ asset('storage/' . $item->img_url) }}')"></div>
                    <div class="item-title">
                        <p class="item-name">{{ $item->name }}</p>
                        @if($sold)
                        <span class="sold-label">SOLD</span>
                        @endif
                    </div>
                </div>
            </a>
            @endforeach
        </div>

        @elseif ($page === 'buy')
        <div class="item-list">
            @foreach ($purchasedItems as $sold)
            @php $item = $sold->item; @endphp
            @if($item)
            <div class="item-card">
                <div class="item-image" style="background-image: url('{{ asset('storage/' . $item->img_url) }}')"></div>
                <div class="item-title">
                    <span class="item-name">{{ $item->name }}</span>
                    <span class="sold-label">SOLD</span>
                </div>
            </div>
            @endif
            @endforeach
        </div>
        @endif
    </div>
</div>
@endsection