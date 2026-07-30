<?php

namespace Tests\Feature;

use App\Models\PasswordResetRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PasswordResetRequestTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $operator;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::create([
            'name'      => 'Admin System',
            'username'  => 'admin_test',
            'email'     => 'admin@sintara.test',
            'password'  => Hash::make('password123'),
            'role'      => 'admin',
            'is_active' => true,
        ]);

        $this->operator = User::create([
            'name'      => 'Operator Perencana',
            'username'  => 'op_perencana',
            'email'     => 'operator@sintara.test',
            'password'  => Hash::make('oldpassword'),
            'role'      => 'operator',
            'is_active' => true,
        ]);
    }

    public function test_operator_can_submit_password_reset_request()
    {
        $response = $this->post(route('password.request.store'), [
            'username' => 'op_perencana',
            'alasan'   => 'Lupa password akun operator',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('reset_success');

        $this->assertDatabaseHas('password_reset_requests', [
            'user_id'  => $this->operator->id,
            'username' => 'op_perencana',
            'status'   => 'pending',
            'alasan'   => 'Lupa password akun operator',
        ]);
    }

    public function test_admin_cannot_submit_operator_reset_request()
    {
        $response = $this->post(route('password.request.store'), [
            'username' => 'admin_test',
        ]);

        $response->assertRedirect();
        $response->assertSessionHas('reset_error');
    }

    public function test_admin_can_approve_password_reset_request()
    {
        $resetReq = PasswordResetRequest::create([
            'user_id'  => $this->operator->id,
            'username' => $this->operator->username,
            'alasan'   => 'Lupa password',
            'status'   => 'pending',
        ]);

        $this->actingAs($this->admin);

        $response = $this->put(route('admin.users.reset-requests.approve', $resetReq->id));

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('password_reset_requests', [
            'id'     => $resetReq->id,
            'status' => 'approved',
        ]);

        $this->operator->refresh();
        $this->assertTrue(Hash::check('password123', $this->operator->password));
    }
}
