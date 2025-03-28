<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Throwable;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Symfony\Component\HttpKernel\Exception\HttpException;


class Handler extends ExceptionHandler
{
    protected $dontReport = [
        AuthenticationException::class,
        AuthorizationException::class,
        ValidationException::class,
        TokenMismatchException::class,
    ];
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
        $this->renderable(function (Throwable $e) {
            if ($e instanceof ModelNotFoundException) {
                return redirect()->route('error')->with('error', 'Resource not found.');
            }

            if ($e instanceof QueryException) {
                return redirect()->route('error')->with('error', 'Database error occurred.');
            }

            if ($e instanceof AuthorizationException) {
                return redirect()->route('error')->with('error', 'Unauthorized action.');
            }

            if ($e instanceof AuthenticationException) {
                return redirect()->route('login')->with('error', 'Please login first.');
            }

            if ($e instanceof TokenMismatchException) {
                return redirect()->back()->with('error', 'Your session has expired. Please try again.');
            }

            if ($e instanceof HttpException) {
                $statusCode = $e->getStatusCode();
                switch ($statusCode) {
                    case 404:
                        return redirect()->route('error')->with('error', 'Page not found.');
                    case 403:
                        return redirect()->route('error')->with('error', 'Forbidden action.');
                    case 500:
                        return redirect()->route('error')->with('error', 'Server error occurred.');
                    default:
                        return redirect()->route('error')->with('error', 'An error occurred.');
                }
            }
        });
    }
}
