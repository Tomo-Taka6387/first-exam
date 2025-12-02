@extends('layouts.auth')

@section('css')
<link rel="stylesheet" href="{{ asset('css/purchase/chat.css')}}?v={{ time() }}">
@endsection

@section('content')
<div class="mypage-container">

    <div class="left-container">
        <p class="left-container_name">その他の取引</p>

        @foreach($otherTrades as $t)
        <a href="{{ route('chat.show', $t->id) }}">
            <div class="trade-item">
                <p class="trade-title">{{ $t->item->name }}</p>
            </div>
        </a>
        @endforeach
    </div>

    <div class="right-container">
        <div class="user-information">

            @php
            $raw = optional(optional($partner)->profile)->img_url;
            $partnerImg = $raw ? asset('storage/' . ltrim($raw, '/')) : null;
            @endphp

            @if ($partnerImg)
            <img class="profile-image" src="{{ $partnerImg }}" alt="プロフィール画像">
            @else
            <div class="profile-image no-image"></div>
            @endif

            <h2 class="profile-name">「{{ $partner->name }}」さんとの取引画面</h2>

            <form action="{{ route('trade.complete', $trade->id) }}" method="post">
                @csrf
                <button type="submit" class="complete-button">取引を完了する</button>
            </form>
        </div>


        <hr class="section-divider">

        <div class="purchase-information">
            <img class="create-form_img" src="{{ $item->img_url }}" alt="{{ $item->name }}">
            <div class="item-information">
                <div class="item-name">{{ $item->name }}</div>
                <div class="item-price">¥{{ number_format($item->price) }}</div>
            </div>
        </div>

        <hr class="section-divider">

        <div class="chat-box">
            @foreach($trade->messages as $message)
            <div class="chat-message {{ $message->sender_id == auth()->id() ? 'my-message' : 'other-message' }}">

                <div class="chat-header {{ $message->sender_id == auth()->id() ? 'my-chat-header' : 'other-chat-header' }}">
                    @php
                    $raw = optional(optional($message->sender)->profile)->img_url;
                    $imgUrl = $raw ? asset('storage/' . ltrim($raw, '/')) : null;
                    @endphp



                    @if($imgUrl)
                    <img class="chat-user-image" src="{{ $imgUrl }}" alt="ユーザー画像">
                    @else
                    <div class="chat-user-placeholder"></div>
                    @endif

                    <span class="chat-username">
                        {{ $message->sender_id == auth()->id() ? auth()->user()->name : $message->sender->name }}
                    </span>
                </div>

                <div class="chat-content">

                    @if(request('edit') == $message->id && $message->sender_id == auth()->id())
                    <form action="{{ route('chat.update', $message->id) }}" method="post">
                        @csrf
                        @method('PUT')
                        <textarea name="message" class="edit-area">{{ $message->message }}</textarea>
                        <div class="edit-buttons">
                            <button type="submit" class="btn">更新</button>
                            <a href="{{ route('chat.show', $trade->id) }}" class="btn cancel-btn">キャンセル</a>
                        </div>
                    </form>
                    @else

                    @if($message->message)
                    <p class="message-text">{{ $message->message }}</p>
                    @endif


                    @if($message->img_path)
                    <img
                        src="{{ asset('storage/' . $message->img_path) }}"
                        class="chat-image"
                        alt="送信画像">
                    @endif

                    @if($message->sender_id == auth()->id())
                    <div class="chat-actions">
                        <a class="chat-edit"
                            href="{{ route('chat.show', $trade->id) }}?edit={{ $message->id }}">
                            編集
                        </a>


                        <form action="{{ route('chat.destroy', $message->id) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="chat-delete">削除</button>
                        </form>
                    </div>
                    @endif
                    @endif
                </div>

            </div>
            @endforeach

        </div>

        <form action="{{ route('chat.store', $trade->id) }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="message-box">
                <input class="chat-form_input" type="text" name="message" id="message"
                    placeholder="取引メッセージを記入してください"
                    value="{{ $draft ?? old('message') }}">
                <div class="image-upload-wrapper">
                    <label for="img_url" class="custom-file-label">画像を追加</label>
                    <input class="input-file" type="file" name="img_url" id="img_url" accept="image/*">
                </div>
                <button class="send-button" type="submit"><img class="send_img" src="{{ asset('icons/sendicon.jpg') }}" alt="画像"></button>
            </div>
            <p class="chat-form__error-message">
                @error('message'){{ $message }}@enderror
                @error('img_url'){{ $message }}@enderror
            </p>
        </form>

    </div>


    <div id="reviewModal" class="modal-overlay" style="display: none;">
        <div class="modal-content">
            <h2 class="modal-title">取引が完了しました<span class="modal-title_span">。</span></h2>
            <hr class="modal-line">

            <form action="{{ route('trade.review.store', $trade->id) }}" method="post" id="reviewForm">
                @csrf
                <input type="hidden" name="reviewee_id" value="{{ $partner->id }}">
                <p class="modal-message">今回の取引相手はどうでしたか？</p>

                <div class="star-rating">
                    @for($i=1; $i<=5; $i++)
                        <span data-star="{{ $i }}">★</span>
                        @endfor
                </div>

                <input type="hidden" name="rating" id="ratingInput">
                <div class="submit-wrapper">
                    <button type="submit" class="review-submit">送信する</button>
                </div>
            </form>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        const modal = document.getElementById("reviewModal");
        const shouldShowModal = @json($shouldShowModal);

        if (modal && shouldShowModal) {
            modal.style.display = "flex";
        }

        const stars = modal ? modal.querySelectorAll(".star-rating span") : [];
        const ratingInput = modal ? modal.querySelector("#ratingInput") : null;

        stars.forEach(star => {
            star.addEventListener("click", () => {
                const rating = parseInt(star.dataset.star);
                if (ratingInput) ratingInput.value = rating;

                stars.forEach(s => s.classList.remove("selected"));
                for (let i = 0; i < rating; i++) stars[i].classList.add("selected");
            });
        });
    });

    document.addEventListener("DOMContentLoaded", function() {

        const input = document.getElementById("message");

        let timer;
        input.addEventListener("input", function() {
            clearTimeout(timer);
            timer = setTimeout(() => {
                fetch("{{ route('chat.saveDraft', $trade->id) }}", {
                    method: "POST",
                    headers: {
                        "X-CSRF-TOKEN": "{{ csrf_token() }}",
                        "Accept": "application/json",
                        "Content-Type": "application/json",
                    },
                    body: JSON.stringify({
                        message: input.value
                    })
                });
            }, 800);
        });

    });
</script>

@endsection