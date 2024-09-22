<?php

namespace App\Util;

use App\Exceptions\Constants;
use App\Util\ResultResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Util class that contains trasversal functions uses in all application
 */
class Utils
{

    public function __construct(){}

    /**
     * Create a response in json format
     * 
     * @param int $httpCode HTTP code.
     * @param String $message The out message
     * @param Object $data The object with the response of the executed operation."
     * 
     * @return JsonResponse The response in json format
     */
    public function createResponse(int $httpCode, String $message, $data = null): JsonResponse
    {

        $resultResponse = new ResultResponse();

        $resultResponse->setStatusCode($httpCode);
        $resultResponse->setMessage($message);
        $resultResponse->setData($data);

        return response()->json(["response " => $resultResponse], $httpCode);
    }

    /**
     * Validate validator response
     * 
     * @param Object $validationResult Validator response
     * @return bool Flag that indicates if request cointains or not errors.
     */
    public function isValidationFailed($validationResult): bool
    {
        //print_r($validationResult); //uncomment only for debug to see validation errors
        return isset($validationResult) && !empty($validationResult) && (!is_array($validationResult) && property_exists($validationResult, 'messages') && is_array($validationResult->getMessages()));
    }

    /**
     * Validate numeric parameter in HTTP Request
     * 
     * @param mixed $variable Contains the value of the parameter received in request
     * @throws NotFound|InvalidArgument InvalidArgument
     */
    public function isNumericValidArgument($variable)
    {

        if (!isset($variable) || is_null($variable) || empty($variable) || is_null(trim($variable)) || empty(trim($variable))) {
            throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
        }

        if (!(isset($variable) && !empty($variable) && trim($variable) === $variable && is_numeric($variable) && intval($variable) == $variable)) {
            //Log The variable must be a positive integer
            //retornar excepcion
            throw new HttpException(Response::HTTP_BAD_REQUEST, Constants::TXT_INVALID_ARGUMENT_CODE);
        }
    }
}
