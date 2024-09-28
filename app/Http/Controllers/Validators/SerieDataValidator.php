<?php

namespace App\Http\Controllers\Validators;

use App\Util\Utils;
use App\Models\Platform;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class SerieDataValidator
{

    public function __construct(){}

    /**
     * Validate the structure of the JSON received as a request in the API calls, following a set of specific rules associated with the data model.
     * @param Request $request The incoming request.
     * @return object The validation result that may contain a set of errors resulting from the validation process.
     */
    public function  validate(Request $request): array|Object
    {
        // Definition of validation rules
        $rules = [
            'title' => 'required|string|min:1|max:50',
            'synopsis' => 'required|string|min:1|max:1000',
            'release_date' => 'required|date|date_format:Y-m-d'
        ];

        // Execute the validation with the defined rules.
        $validator = Validator::make($request->all(), $rules);

        // Check if the validation has failed
        return $validator->fails() ? $validator->errors() : $validator->validated();
    }

    /**
     * Map an object from the data received in the request.
     * 
     * @param Request $request. The incoming request.
     * @return Platform The Relative object mapped.
     */
    public function createModelFromRequest(Request $request): Platform
    {
        $fields = [
            'title',
            'synopsis',
            'release_date'
        ];

        $platform = new Platform();

        $platform->fill($request->only($fields));

        return $platform;
    }

    
    /**
     * Create and return an array representing the Relative object from the given Request.
     *
     * @param Request $request The HTTP Request containing the data for the Relative object.
     * @return array An array representation of the Relative object.
     * @throws HttpException If the validation of Person data fails return Bad Request HTTP.
     */
    public function createObjectFromRequest(Request $request): array
    {

        $utils = new Utils();

        $validationPlatformResult = $this->validate($request);

        if ($utils->isValidationFailed($validationPlatformResult)) {

            throw new HttpException(Response::HTTP_BAD_REQUEST, $validationPlatformResult->getMessageBag() );
        }

        return array_filter((array) $validationPlatformResult, function ($value) {
            return $value != null;
        });
    }
}
