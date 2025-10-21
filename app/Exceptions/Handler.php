<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * The list of the inputs that are never flashed to the session on validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });
    }

    public function render($request, Throwable $e): Response|JsonResponse|\Symfony\Component\HttpFoundation\Response|RedirectResponse
    {
        if ($e instanceof ValidationException) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'errors' => $e->errors(),
                'code' => 'VALIDATION_ERROR',
            ], 422);
        }

        if ($e instanceof NotFoundHttpException) {
            return response()->json([
                'message' => 'Not found.',
                'code' => 'NOT_FOUND',
            ], 404);
        }

        if ($e instanceof MethodNotAllowedHttpException) {
            return response()->json([
                'message' => 'Method not allowed.',
                'code' => 'METHOD_NOT_ALLOWED',
            ], 405);
        }

        // Логируем неожиданные ошибки
        if ($this->isDebugMode()) {
            return parent::render($request, $e);
        }

        // Все остальные ошибки — 500 с единым сообщением
        return response()->json([
            'message' => 'Something went wrong.',
            'code' => 'SERVER_ERROR',
        ], 500);
    }

    protected function isDebugMode()
    {
        return config('app.debug', false);
    }
}
