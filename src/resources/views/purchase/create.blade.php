@extends('layouts.app')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase/create.css')}}?v={{ time() }}">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@700&display=swap" rel="stylesheet">
@endsection

@section('content')
<div class="item-container">
    <div class="item-image">
        <img class="create-form_img" src="{{ $item->img_url }}" alt="{{ $item->name }}">
    </div>
    <div class="item-detail">
        <h2 class="item-name">{{ $item->name }}</h2>
        <p class="item-brand">{{ $item->brand }}</p>
        <p class="item-price">¥{{ number_format($item->price) }} <span class="item-price_tax">(税込)</span></p>


        <div class="item-interactions">
            <div class="interactions">
                <span class="likes">
                    @auth
                    @if($item->likeByUsers->contains(auth()->id()))
                    <form action="{{ route('items.unlike', $item->id) }}" method="post">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="like-btn liked">
                            <img class="icon" src="{{ asset('icons/star.png') }}" alt="like" />
                        </button>
                    </form>
                    @else
                    <form action="{{ route('items.like', $item->id) }}" method="post">
                        @csrf
                        <button type="submit" class="like-btn">
                            <img class="icon" src="{{ asset('icons/star.png') }}" alt="like" />
                        </button>
                    </form>
                    @endif
                    @else

                    <span class="like-btn disabled">
                        <img src="{{ asset('icons/star.png') }}" alt="like" class="icon">
                    </span>
                    @endauth
                    <span class="count">{{ $item->likeByUsers->count() }}</span>
                </span>

                <span class="comments">
                    <img class="icon" src="{{ asset('icons/comment.png') }}" />
                    <span class="count">{{ $item->comments->count() }}</span>
                </span>
            </div>
        </div>

        <form action="{{ route('purchase.show', $item->id) }}" method="get">
            <button class="item-detail_btn" type="submit">購入手続きへ</button>
        </form>

        <div class="item-information">
            <h3 class="item-title">商品説明</h3>
            <p class="item-description">{{ $item->description }}</p>
        </div>

        <div class="item-information">
            <h3 class="item-title">商品の情報</h3>

            <div class="item-category">
                <span class="sub-title">カテゴリー</span>
                <div class="category">
                    @foreach($item->categories as $category)
                    <span class="category-tag">{{ $category->category }}</span>
                    @if(!$loop->last)
                    @endif
                    @endforeach
                </div>
            </div>

            <div class="item-condition">
                <span class="sub-title">商品の状態</span>
                <span class="condition">{{ $item->condition->condition }}</span>
            </div>
        </div>

        <div class="comment-section">
            <h3 class="comment-count">コメント ({{ $item->comments->count()  }})</h3>

            <div class="comments-list">
                @foreach($item->comments as $comment)
                <div class="comment-user">
                    <div class="comment-information">
                        @if($comment->user->profile->img_url)
                        <img src="{{ asset('storage/' .  $comment->user->profile->img_url) }}">
                        @else
                        <div class="default-user-image"></div>
                        @endif
                        <p class="user-name">{{ $comment->user->name }}</p>
                    </div>
                    <p class="comment-content">{{ $comment->content }}</p>
                </div>
                @endforeach
            </div>


            <p class="content-title">商品へのコメント</p>
            @auth
            <form action="{{ route('items.comment', $item->id) }}" method="post">
                @csrf
                <textarea name="content" id="comment-content"></textarea>
                @error('content')
                <p class="error">{{ $message }}</p>
                @enderror
                <button class="comment_btn" type="submit">コメントを送信する</button>
            </form>
            @else
            <form action="{{ route('login') }}" method="get">
                <textarea disabled></textarea>
                <button class="comment_btn" type="submit">コメントを送信する</button>
            </form>
            @endauth
        </div>
    </div>
</div>
@endsection