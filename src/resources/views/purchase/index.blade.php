@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase/index.css')}}?v={{ time() }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">
@endsection

@section('content')
<div class="home_list">

    <div class="item-toggle">
        <a href="{{ route('index', ['tab' => '/','keyword' => $keyword ?? '']) }}" class="tab {{ $tab === '/' ? 'active' : '' }}">おすすめ</a>
        <a href="{{ route('index', ['tab' => 'mylist','keyword' => $keyword ?? '']) }}" class="tab {{ $tab === 'mylist' ? 'active' : '' }}">マイリスト</a>
    </div>

    <div class="item-box">

        @if ($tab === '/')
        <div class="item-list">
            @foreach($items as $item)
            <div class="item-card">
                @if(!$item->soldItem)
                <a href="{{ route('items.show', $item->id) }}" class="item-link">
                    <div class="item-image" style="background-image: url('{{ asset('storage/' . $item->img_url) }}')"></div>
                    <span class="item-name">{{ $item->name }}</span>
                </a>
                @else
                <div class="item-image" style="background-image: url('{{ asset('storage/' . $item->img_url) }}')"></div>
                <div class="item-title">
                    <span class="item-name">{{ $item->name }}</span>
                    <span class="sold-label">SOLD</span>
                </div>
                @endif
            </div>
            @endforeach
        </div>

        @elseif ($tab === 'mylist')
        <div class="item-list">
            @foreach($likedItems as $item)
            <div class="item-card">
                @if($item->soldItem)
                <div class="item-image" style="background-image: url('{{ asset('storage/' . $item->img_url) }}')"></div>
                <div class="item-title">
                    <span class="item-name">{{ $item->name }}</span>
                    <span class="sold-label">SOLD</span>
                </div>
                @else
                <a href="{{ route('items.show', $item->id) }}" class="item-link">
                    <div class="item-image" style="background-image: url('{{ asset('storage/' . $item->img_url) }}')"></div>
                    <span class="item-name">{{ $item->name }}</span>
                </a>
                @endif
            </div>
            @endforeach
        </div>
        @endif

    </div>
</div>
@endsection