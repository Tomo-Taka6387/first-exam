<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\Profile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;


class UserUpdateTest extends TestCase
{
    use RefreshDatabase;

    public function test_profile_form_displays_user_information()
    {
        $user = User::factory()->create([
            'name' => '山本太郎',
        ]);

        $profile = Profile::factory()->create([
            'user_id' => $user->id,
            'postcode' => '3948406',
            'address' => '島根県山口市西区松本町5-6-10',
            'building' => 'コーポ杉山105号',
            'img_url' => 'profile.png',
        ]);


        $response = $this->actingAs($user)
            ->get(route('mypage.edit'));

        $response->assertStatus(200);
        $response->assertSee('プロフィール設定');
        $response->assertSee($user->name);
        $response->assertSee($profile->postcode);
        $response->assertSee($profile->address);
        $response->assertSee('profile.png');
    }
}
