<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class RoleAccessTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $operator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name'      => 'Admin User',
            'username'  => 'admin_test',
            'email'     => 'admin_test@sintara.test',
            'password'  => Hash::make('password123'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        $this->operator = User::create([
            'name'      => 'Operator User',
            'username'  => 'operator_test',
            'email'     => 'operator_test@sintara.test',
            'password'  => Hash::make('password123'),
            'role'      => 'operator',
            'is_active' => true,
        ]);
    }

    public function test_operator_accessing_admin_dashboard_is_redirected_to_operator_dashboard()
    {
        $this->actingAs($this->operator);

        $response = $this->get(route('admin.dashboard'));

        $response->assertRedirect(route('operator.dashboard'))
            ->assertSessionHas('error', 'Anda tidak memiliki akses ke halaman administrator.');
    }

    public function test_admin_accessing_operator_dashboard_is_redirected_to_admin_dashboard()
    {
        $this->actingAs($this->admin);

        $response = $this->get(route('operator.dashboard'));

        $response->assertRedirect(route('admin.dashboard'))
            ->assertSessionHas('error', 'Anda tidak memiliki akses ke halaman operator.');
    }
}
