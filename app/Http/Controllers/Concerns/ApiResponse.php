<?php

namespace App\Http\Controllers\Concerns;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Http\JsonResponse;

trait ApiResponse
{
    /**
     * Build a successful JSON response.
     */
    protected function success(mixed $data = null, string $message = 'OK', int $status = 200): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $data,
        ], $status);
    }

    /**
     * Build a paginated JSON response.
     */
    protected function paginated(LengthAwarePaginator $paginator, string $message): JsonResponse
    {
        return response()->json([
            'success' => true,
            'message' => $message,
            'data' => $paginator->items(),
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'last_page' => $paginator->lastPage(),
                'total' => $paginator->total(),
            ],
        ]);
    }

    /**
     * Build a failed JSON response.
     */
    protected function error(string $message, int $status): JsonResponse
    {
        return response()->json([
            'success' => false,
            'message' => $message,
        ], $status);
    }

    /**
     * Convert an unexpected exception into a clean JSON error response.
     */
    protected function handleException(\Throwable $exception, string $message): JsonResponse
    {
        if ($exception instanceof UniqueConstraintViolationException) {
            return $this->error('Data dengan nilai unik tersebut sudah ada.', 409);
        }

        report($exception);

        return $this->error($message, 500);
    }
}
