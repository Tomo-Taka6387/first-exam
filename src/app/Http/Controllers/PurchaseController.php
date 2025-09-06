<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Http\Requests\PurchaseRequest;
use App\Http\Requests\SendingAddressRequest;
use App\Models\Item;
use App\Models\SoldItem;


class PurchaseController extends Controller
{

    public function show($itemId)
    {
        $item = Item::findOrFail($itemId);
        $user = Auth::user();

        $defaultAddress = [
            'postcode' => $user->profile->postcode,
            'address'  => $user->profile->address,
            'building' => $user->profile->building ?? '',
        ];

        $address = session('shipping_address_' . $itemId, $defaultAddress);

        $paymentMethods = [
            'convenience_store' => 'コンビニ払い',
            'card' => 'カード払い',
        ];
        $selectedPaymethod = session('selected_paymethod_' . $itemId, null);

        return view('purchase.order', compact('item', 'paymentMethods', 'selectedPaymethod', 'address'));
    }

    public function address()
    {
        $profile = auth()->user()->profile;
        return view('purchase.address', compact('profile'));
    }

    public function editAddress($itemId)
    {
        $item = Item::findOrFail($itemId);
        $user = Auth::user();

        $defaultAddress = [
            'postcode' => $user->profile->postcode,
            'address'  => $user->profile->address,
            'building' => $user->profile->building ?? '',
        ];

        $address = session('shipping_address_' . $itemId, $defaultAddress);

        return view('purchase.address', compact('item', 'address'));
    }

    public function updateAddress(SendingAddressRequest $request, $itemId)
    {
        $validated = $request->validated();

        session(['shipping_address_' . $itemId => [
            'postcode' => $validated['sending_postcode'],
            'address'  => $validated['sending_address'],
            'building' => $validated['sending_building'] ?? '',
        ]]);

        return redirect()->route('purchase.show', ['item' => $itemId]);
    }

    public function store(PurchaseRequest $request, $itemId)
    {
        $item = Item::findOrFail($itemId);
        $user = Auth::user();

        $paymethod = $request->input('paymethod');

        $address = session('shipping_address_' . $item->id, [
            'postcode' => $user->profile->postcode,
            'address'  => $user->profile->address,
            'building' => $user->profile->building ?? '',
        ]);

        SoldItem::create([
            'user_id' => $user->id,
            'item_id' => $item->id,
            'sending_postcode' => $address['postcode'],
            'sending_address'  => $address['address'],
            'sending_building' => $address['building'] ?? '',
            'paymethod'        => $paymethod,
        ]);

        session()->forget('shipping_address_' . $item->id);
        session()->forget('selected_paymethod_' . $item->id);

        return redirect()->route('index');
    }
}
