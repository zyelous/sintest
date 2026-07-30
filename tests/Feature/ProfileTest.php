<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class ProfileTest extends TestCase
{
    use RefreshDatabase;

    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::create([
            'name'      => 'Budi Test',
            'username'  => 'buditest',
            'email'     => 'budi@sintara.test',
            'password'  => Hash::make('password123'),
            'role'      => 'operator',
            'is_active' => true,
        ]);
    }

    public function test_guest_cannot_access_profile_page()
    {
        $response = $this->get(route('profile.edit'));
        $response->assertRedirect(route('login'));
    }

    public function test_authenticated_user_can_view_profile_page()
    {
        $this->actingAs($this->user);

        $response = $this->get(route('profile.edit'));

        $response->assertStatus(200)
            ->assertSee('Budi Test')
            ->assertSee('buditest')
            ->assertSee('budi@sintara.test');
    }

    public function test_user_can_update_profile_information()
    {
        $this->actingAs($this->user);

        $response = $this->put(route('profile.update'), [
            'name'     => 'Budi Updated',
            'username' => 'budi_new',
            'email'    => 'budi_new@sintara.test',
        ]);

        $response->assertRedirect(route('profile.edit'))
            ->assertSessionHas('success_profile');

        $this->assertDatabaseHas('users', [
            'id'       => $this->user->id,
            'name'     => 'Budi Updated',
            'username' => 'budi_new',
            'email'    => 'budi_new@sintara.test',
        ]);
    }

    public function test_user_cannot_update_profile_with_existing_username()
    {
        User::create([
            'name'      => 'Other User',
            'username'  => 'existing_user',
            'email'     => 'other@sintara.test',
            'password'  => Hash::make('password123'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        $this->actingAs($this->user);

        $response = $this->put(route('profile.update'), [
            'name'     => 'Budi Updated',
            'username' => 'existing_user',
            'email'    => 'budi@sintara.test',
        ]);

        $response->assertSessionHasErrors(['username']);
    }

    public function test_user_can_update_password_with_correct_current_password()
    {
        $this->actingAs($this->user);

        $response = $this->put(route('profile.password'), [
            'current_password'      => 'password123',
            'password'              => 'newsecret123',
            'password_confirmation' => 'newsecret123',
        ]);

        $response->assertRedirect(route('profile.edit'))
            ->assertSessionHas('success_password');

        $this->assertTrue(Hash::check('newsecret123', $this->user->fresh()->password));
    }

    public function test_user_cannot_update_password_with_wrong_current_password()
    {
        $this->actingAs($this->user);

        $response = $this->put(route('profile.password'), [
            'current_password'      => 'wrongpassword',
            'password'              => 'newsecret123',
            'password_confirmation' => 'newsecret123',
        ]);

        $response->assertSessionHasErrors(['current_password']);
        $this->assertFalse(Hash::check('newsecret123', $this->user->fresh()->password));
    }

    public function test_user_cannot_update_password_when_confirmation_mismatches()
    {
        $this->actingAs($this->user);

        $response = $this->put(route('profile.password'), [
            'current_password'      => 'password123',
            'password'              => 'newsecret123',
            'password_confirmation' => 'mismatch123',
        ]);

        $response->assertSessionHasErrors(['password']);
    }
}
