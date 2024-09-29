<?php

namespace App\Http\Controllers\Validators;

use App\Util\Utils;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PlatformDataValidator
{

    public function __construct(){}

    /**
     * Validate the structure of the JSON received as a request in the API calls, following a set of specific rules associated with the data model.
     * @param Request $request The incoming request.
     * @return object The validation result that may contain a set of errors resulting from the validation process.
     */
    public function  validatePlatformData(Request $request): array|Object
    {
        // Definition of validation rules
        $rules = [
            'name' => 'required|string|min:1|max:50',
            'description' => 'nullable|string|max:100',
            'release_date' => 'nullable|date|date_format:Y-m-d',
            'logo' => 'nullable|string|max:255', 
        ];

        // Execute the validation with the defined rules.
        $validator = Validator::make($request->all(), $rules);

        // Check if the validation has failed
        return $validator->fails() ? $validator->errors() : $validator->validated();
    }

    /**
     * Create and return an array representing the Platform object from the given Request.
     *
     * @param Request $request The HTTP Request containing the data for the Platform object.
     * @return array An array representation of the Platform object.
     * @throws HttpException If the validation of Person data fails return Bad Request HTTP.
     */
    public function createObjectPlatformRequest(Request $request): array
    {

        $utils = new Utils();

        $validationResult = $this->validatePlatformData($request);

        if ($utils->isValidationFailed($validationResult)) {

            throw new HttpException(Response::HTTP_BAD_REQUEST, $validationResult->getMessageBag() );
        }

        return array_filter((array) $validationResult, function ($value) {
            return $value != null;
        });
    }
}
