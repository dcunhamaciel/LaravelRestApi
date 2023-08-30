<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Throwable;
use Illuminate\Http\Request;

class Handler extends ExceptionHandler
{
    /**
     * A list of exception types with their corresponding custom log levels.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        //
    ];

    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        ResponseFieldError::class,
        ResponseMensageError::class,
        ResponseAcl::class
    ];

    /**
     * A list of the inputs that are never flashed to the session on validation exceptions.
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
     *
     * @return void
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            //
        });

        /**
         * Realiza o retorno da API
         */
        $this->renderable(function (HttpException $e, Request $request) {
            if (!empty($request->route()) && in_array("api", explode("/", $request->route()->getPrefix())))
                switch ($e->getStatusCode()) {
                    case 403:
                        throw new ResponseApi("Token não possui um formato válido.", 403);
                    case 401:
                        throw new ResponseApi("Não foi possível autenticar na API.", 401);
                    default:
                        throw new ResponseApi($e->getMessage(), $e->getStatusCode());
                }
        });
    }
}
