<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserCrudTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_paginated_users(): void
    {
        User::factory()->count(3)->create();

        $this->getJson('/api/users')
            ->assertOk()
            ->assertJsonPath('success', true)
            ->assertJsonCount(3, 'data')
            ->assertJsonPath('meta.total', 3);
    }

    public function test_user_can_be_created(): void
    {
        $response = $this->postJson('/api/users', [
            'name' => 'Budi Santoso',
            'email' => 'budi@example.com',
            'password' => 'secret123',
        ]);

        $response
            ->assertCreated()
            ->assertJsonPath('success', true)
            ->assertJsonPath('data.email', 'budi@example.com')
            ->assertJsonMissingPath('data.password');

        $this->assertDatabaseHas('users', ['email' => 'budi@example.com']);

        $user = User::where('email', 'budi@example.com')->firstOrFail();
        $this->assertTrue(Hash::check('secret123', $user->password));
    }

    public function test_user_creation_fails_with_invalid_data(): void
    {
        $this->postJson('/api/users', [
            'name' => 'ab',
            'email' => 'not-an-email',
            'password' => 'short',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors(['name', 'email', 'password']);
    }

    public function test_user_creation_fails_with_duplicate_email(): void
    {
        User::factory()->create(['email' => 'used@example.com']);

        $this->postJson('/api/users', [
            'name' => 'Pengguna Baru',
            'email' => 'used@example.com',
            'password' => 'secret123',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    }

    public function test_user_can_be_shown(): void
    {
        $user = User::factory()->create();

        $this->getJson("/api/users/{$user->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $user->id)
            ->assertJsonMissingPath('data.password');
    }

    public function test_showing_missing_user_returns_not_found(): void
    {
        $this->getJson('/api/users/999')
            ->assertNotFound()
            ->assertJsonPath('success', false);
    }

    public function test_user_can_be_updated(): void
    {
        $user = User::factory()->create(['email' => 'lama@example.com']);

        $this->putJson("/api/users/{$user->id}", [
            'name' => 'Nama Baru',
            'email' => 'baru@example.com',
            'password' => 'newsecret123',
        ])
            ->assertOk()
            ->assertJsonPath('data.name', 'Nama Baru')
            ->assertJsonPath('data.email', 'baru@example.com');

        $user->refresh();
        $this->assertSame('baru@example.com', $user->email);
        $this->assertTrue(Hash::check('newsecret123', $user->password));
    }

    public function test_user_update_keeps_own_email_unique_rule(): void
    {
        $user = User::factory()->create(['email' => 'milik-sendiri@example.com']);

        $this->putJson("/api/users/{$user->id}", [
            'email' => 'milik-sendiri@example.com',
        ])
            ->assertOk()
            ->assertJsonPath('data.email', 'milik-sendiri@example.com');
    }

    public function test_user_update_fails_with_another_users_email(): void
    {
        User::factory()->create(['email' => 'sudah-dipakai@example.com']);
        $user = User::factory()->create();

        $this->putJson("/api/users/{$user->id}", [
            'email' => 'sudah-dipakai@example.com',
        ])
            ->assertUnprocessable()
            ->assertJsonValidationErrors('email');
    }

    public function test_user_can_be_deleted(): void
    {
        $user = User::factory()->create();

        $this->deleteJson("/api/users/{$user->id}")
            ->assertOk()
            ->assertJsonPath('success', true);

        $this->assertDatabaseMissing('users', ['id' => $user->id]);
        $this->getJson("/api/users/{$user->id}")->assertNotFound();
    }
}
