<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Display a paginated listing of users.
     */
    public function index(): JsonResponse
    {
        $users = User::query()->latest('id')->paginate(15);

        return $this->paginated($users, 'Daftar user berhasil diambil.');
    }

    /**
     * Store a newly created user.
     */
    public function store(StoreUserRequest $request): JsonResponse
    {
        try {
            $user = User::create($request->validated());

            return $this->success($user, 'User berhasil dibuat.', 201);
        } catch (\Throwable $exception) {
            return $this->handleException($exception, 'Gagal membuat user.');
        }
    }

    /**
     * Display the specified user.
     */
    public function show(User $user): JsonResponse
    {
        return $this->success($user, 'Detail user berhasil diambil.');
    }

    /**
     * Update the specified user.
     */
    public function update(UpdateUserRequest $request, User $user): JsonResponse
    {
        try {
            $user->update($request->validated());

            return $this->success($user->refresh(), 'User berhasil diperbarui.');
        } catch (\Throwable $exception) {
            return $this->handleException($exception, 'Gagal memperbarui user.');
        }
    }

    /**
     * Remove the specified user.
     */
    public function destroy(User $user): JsonResponse
    {
        try {
            $user->delete();

            return $this->success(null, 'User berhasil dihapus.');
        } catch (\Throwable $exception) {
            return $this->handleException($exception, 'Gagal menghapus user.');
        }
    }
}
