<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Concerns\ApiResponse;
use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class UserController extends Controller
{
    use ApiResponse;

    /**
     * Display a paginated listing of users.
     */
    public function index(Request $request): JsonResponse
    {
        $query = User::query();

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $perPage = (int) $request->input('per_page', 15);
        $users = $query->latest('id')->paginate($perPage);

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
