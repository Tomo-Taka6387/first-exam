<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category;
use App\Models\Condition;
use App\Models\SoldItem;
use App\Http\Requests\ExhibitionRequest;
use App\Http\Requests\CommentRequest;
use Illuminate\Support\Facades\Redirect;

class ItemController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $keyword = $request->query('keyword');
        $tab = $request->query('tab', '/');

        $query = Item::query();

        if ($keyword) {
            $query->where('name', 'like', "%{$keyword}%");
        } elseif ($user) {
            $purchasedItemIds = SoldItem::where('user_id', $user->id)->pluck('item_id');
            $query->whereNotIn('id', $purchasedItemIds);
        }

        $items = $query->latest()->get();

        $likedItems = collect();

        if ($user) {
            $likedItems = $user->likes()->with('item')->get()->pluck('item')->filter(function ($item) use ($keyword) {
                return $item && (!$keyword || str_contains($item->name, $keyword));
            });
        }


        return view('purchase.index', compact('items', 'likedItems', 'tab', 'keyword'))->with('page', $tab)
            ->with('keyword', $keyword);
    }

    public function search(Request $request)
    {
        $keyword = $request->input('keyword');

        $items = Item::query()->when($keyword, function ($query, $keyword) {
            $query->where('name', 'like', '%' . $keyword . '%');
        })
            ->get();

        $page = 'search';

        return view('purchase.index', compact('items', 'keyword', 'page'));
    }

    public function create()
    {
        $categories = Category::all();
        $conditions = Condition::all();

        $page = 'sell';

        return view('purchase.sell', compact('categories', 'conditions', 'page'));
    }


    public function store(ExhibitionRequest $request)
    {
        $validated = $request->validated();

        $path = $request->file('img_url')->store('item_images', 'public');
        $validated['img_url'] = $path;

        $item = new Item($validated);
        $item->user_id = Auth::id();
        $item->save();

        $item->categories()->sync($validated['category_id']);

        return Redirect()->to('/mypage?page=sell');
    }


    public function show(Item $item)
    {
        $item->load(['condition', 'categories', 'user', 'comments.user.profile']);
        $page = 'sell';

        return view('purchase.create', compact('item', 'page'));
    }

    public function like(Item $item)
    {
        if (!$item->likeByUsers->contains(auth()->id())) {
            $item->likeByUsers()->attach(auth()->id());
        }

        return back();
    }

    public function unlike(Item $item)
    {
        $item->likeByUsers()->detach(auth()->id());
        return back();
    }

    public function comment(CommentRequest $request, Item $item)
    {

        $item->comments()->create([
            'user_id' => auth()->id(),
            'content' => $request->content,
        ]);
        return back();
    }
}
