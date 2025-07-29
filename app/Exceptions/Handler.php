<?php

namespace App\Exceptions;

use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    protected $dontReport = [
        //
    ];

    protected $dontFlash = ['password', 'password_confirmation'];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // Optional: Log or notify exception here
        });
    }

    /**
     * Handle unauthenticated user (auth:sanctum, etc.)
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        return response()->json([
            'message' => 'Unauthenticated',
        ], 401);
    }

    /**
     * Convert validation errors to JSON
     */
    protected function invalidJson($request, ValidationException $exception)
    {
        return response()->json([
            'message' => 'Validation Failed',
            'errors' => $exception->errors(),
        ], 422);
    }

    /**
     * Handle all other exceptions
     */
    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ModelNotFoundException) {
            return response()->json([
                'message' => 'Resource not found.',
            ], 404);
        }

        if ($exception instanceof HttpException) {
            return response()->json([
                'message' => $exception->getMessage() ?: 'HTTP error',
            ], $exception->getStatusCode());
        }

        // fallback for all unhandled exceptions
        return response()->json([
            'message' => 'Server Error',
            'error' => $exception->getMessage(), // hide this in production if needed
        ], 500);
    }
}
