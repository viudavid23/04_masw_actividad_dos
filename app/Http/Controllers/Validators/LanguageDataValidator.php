<?php

namespace App\Http\Controllers\Validators;

use App\Util\Utils;
use App\Models\Language;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class LanguageDataValidator
{

    public function __construct(){}

    /**
     * Validate the structure of the JSON received as a request in the API calls, following a set of specific rules associated with the data model.
     * @param Request $request The incoming request.
     * @return object The validation result that may contain a set of errors resulting from the validation process.
     */
    public function validate(Request $request): array|Object
    {
        // Definition of validation rules
        $rules = [
            'name' => 'required|string|min:1|max:50',
            'iso_code' => [
                'required',
                'string',
                'regex:/^([A-Z]{2,3}|\d{3})$/', // Accepts ISO format
                'min:2',
                'max:3'
            ]
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
     * @return Language The Language object mapped.
     */
    public function createModelFromRequest(Request $request): Language
    {
        $fields = [
            'name',
            'iso_code'
        ];

        $Language = new Language();

        $Language->fill($request->only($fields));

        return $Language;
    }

    
    /**
     * Create and return an array representing the Language object from the given Request.
     *
     * @param Request $request The HTTP Request containing the data for the Language object.
     * @return array An array representation of the Language object.
     * @throws BadRequestException If the validation of Language data fails.
     */
    public function createObjectFromRequest(Request $request): array
    {

        $utils = new Utils();

        $validationLanguageResult = $this->validate($request);

        if ($utils->isValidationFailed($validationLanguageResult)) {

            throw new HttpException(Response::HTTP_BAD_REQUEST, $validationLanguageResult->getMessageBag() );
        }

        return array_filter((array) $validationLanguageResult, function ($value) {
            return $value != null;
        });
    }
}
