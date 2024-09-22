<?php

use App\Exceptions\CantExecuteOperation;
use App\Exceptions\Constants;
use App\Exceptions\ElementAlreadyExists;
use App\Util\Utils;
use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Validation\ValidationException;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware) {
        //
    })
    ->withExceptions(function (Exceptions $exceptions) {


        $exceptions->render(function (Throwable $e, Request $request) {

            $utils = new Utils();

            $exceptionResponses = [
                ElementAlreadyExists::class => Response::HTTP_CONFLICT,
                CantExecuteOperation::class => Response::HTTP_UNPROCESSABLE_ENTITY,
                ValidationException::class => Response::HTTP_UNPROCESSABLE_ENTITY,
            ];

            // Si es una HttpException, obtenemos el código HTTP directamente
            if ($e instanceof \Symfony\Component\HttpKernel\Exception\HttpException) {
                $httpCode = $e->getStatusCode();
            } else {
                // Busca el código de estado en el array basado en la clase de excepción
                $httpCode = $exceptionResponses[get_class($e)] ?? Response::HTTP_INTERNAL_SERVER_ERROR;
            }

            if ($httpCode < 100 || $httpCode > 599) {
                $httpCode = Response::HTTP_INTERNAL_SERVER_ERROR;
            }

            // Obtener el mensaje de la excepción, si no hay mensaje usa uno por defecto
            $message = $e->getMessage() ?: Constants::TXT_INTERNAL_SERVER_ERROR_CODE;

            // Crear la respuesta
            return $utils->createResponse($httpCode, $message);
        });
    })->create();
