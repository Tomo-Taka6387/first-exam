<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\Profile;
use App\Http\Requests\RegisterRequest;
use App\Http\Requests\LoginRequest;
use App\Http\Requests\ProfileRequest;


class UserController extends Controller
{
    public function index()
    {
        return view('profile.show');
    }

    public function loginUser(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');

        if (Auth::attempt($credentials)) {
            $user = Auth::user();

            if (!$user->profile || $user->profile->postcode) {
                return redirect()->route('register.profile.form');
            }
            return redirect('/');
        }
    }

    public function create()

    {
        return view('auth.register');
    }

    public function createProfile()
    {
        $user = auth()->user();
        $mode = $user->profile ? 'edit' : 'register';
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

        $mode = session('mode', $user->profile ? 'edit' : 'register');

        return view('profile.show', compact('user', 'profile', 'mode'));
    }

    public function store(RegisterRequest $request)
    {
        $date = $request->validated();

        $user = User::create([
            'name' => $date['name'],
            'email' => $date['email'],
            'password' => bcrypt($date['password']),
        ]);

        Auth::login($user);

        return redirect()->route('mypage.edit.update')
            ->with(['mode', 'register']);
    }

    public function storeProfile(ProfileRequest $request)
    {
        $user = auth()->user();

        $profile = new Profile();
        $profile->user_id = $user->id;
        $profile->postcode = $request->input('postcode');
        $profile->address = $request->input('address');
        $profile->building = $request->input('building');

        if ($request->hasFile('img_url')) {
            $path = $request->file('img_url')->store('profile_images', 'public');
            $profile->img_url = $path;
        }

        $profile->save();

        return redirect()->route('mypage');
    }

    public function update(ProfileRequest $request)
    {

        $user = auth()->user();
        $date = $request->validated();

        $user->name = $date('name');
        $user->save();

        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);

        $profile->postcode = $request->input('postcode');
        $profile->address = $request->input('address');
        $profile->building = $request->input('building');

        if ($request->hasFile('img_url')) {
            $path = $request->file('img_url')->store('profile_images', 'public');
            $profile->img_url = basename($path);
        }

        $profile->save();

        $mode = $request->input('mode', 'edit');

        return $mode === 'register'
            ? redirect('/')
            : redirect()->route('mypage');
    }


    public function mypage()
    {
        $user = auth()->user();
        $profile = $user->profile ?? new Profile();

        return view('purchase.show', compact('user', 'profile'));
    }

    public function editProfile()
    {
        $user = auth()->user();
        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
        $page = 'profile';

        return view('profile.edit', compact('user', 'profile', 'page'));
    }


    public function updateProfile(ProfileRequest $request)
    {


        $user = Auth()->user();
        $date = $request->validated();


        if ($request->input('mode') === 'register') {
            $profile = new Profile(['user_id' => $user->id]);
        } else {
            $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
        }

        $user->name = $date['name'];
        $user->save();

        $profile->postcode = $date['postcode'];
        $profile->address = $date['address'];
        $profile->building = $date['building'] ?? null;

        if ($request->hasFile('img_url')) {
            $path = $request->file('img_url')->store('profile_images', 'public');
            $profile->img_url = $path;
        }
        $profile->save();

        $mode = $request->input('mode', 'register');

        return $request->input('mode') === 'register'
            ? redirect('/')
            : redirect()->route('mypage');

    }

    public function logout()
    {
        Auth::logout();
        return view('/login');
    }
}
