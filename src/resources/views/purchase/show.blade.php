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

            <div class="rating-stars">
                @for ($i = 1; $i <= 5; $i++)
                    <span class="star {{ $i <= $rating ? 'filled' : '' }}">★</span>
                    @endfor
            </div>
        </div>

        <a href="{{ route('mypage.edit') }}" class="edit-button">プロフィール編集</a>

    </div>

</div>

<div class="item-toggle">
    <a href="{{ url('/mypage?page=sell') }}" class="tab {{ $page === 'sell' ? 'active' : '' }}">出品した商品</a>
    <a href="{{ url('/mypage?page=buy') }}" class="tab {{ $page === 'buy' ? 'active' : '' }}">購入した商品</a>
    <a href="{{ url('/mypage?page=transaction') }}" class="tab {{ $page === 'transaction' ? 'active' : '' }}">
        取引中の商品
        @if($unreadTotal > 0)
        <span class="unread-total-badge">（{{ $unreadTotal }}）</span>
        @endif
    </a>

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
                <div class="item-image" style="background-image: url('{{ $item->img_url }}')"></div>
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
            <div class="item-image" style="background-image: url('{{ $item->img_url }}')"></div>
            <div class="item-title">
                <span class="item-name">{{ $item->name }}</span>
                <span class="sold-label">SOLD</span>
            </div>
        </div>
        @endif
        @endforeach
    </div>
    @endif

    @if($page === 'transaction')
    <div class="item-list">
        @foreach ($trades as $trade)
        <a href="{{ route('chat.show', ['trade' => $trade->id]) }}">
            <div class="item-card">
                <div class="item-image" style="background-image: url('{{ $trade->item->img_url }}')"></div>
                @if($trade->unreadCount > 0)
                <span class="unread-badge">{{ $trade->unreadCount }}</span>
                @endif
            </div>
            <div class="item-title">{{ $trade->item->name }}</div>
        </a>
        @endforeach
    </div>
    @endif

</div>
</div>
@endsection