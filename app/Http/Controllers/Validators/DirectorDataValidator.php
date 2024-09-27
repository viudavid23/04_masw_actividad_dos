<?php

namespace App\Http\Controllers\Validators;

use App\Util\Utils;
use App\Models\Director;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class DirectorDataValidator
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
            'beginning_career' => 'nullable|date|date_format:Y-m-d',
            'active_years' => 'required|numeric|between:0,99',
            'biography' => 'required|string|min:1|max:5000',
            'awards' => 'nullable|string|min:1|max:1000'
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
     * @return Director The Director object mapped.
     */
    public function createModelFromRequest(Request $request): Director
    {
        $fields = [
            'beginning_career',
            'active_years',
            'biography',
            'awards',
            'people_id'
        ];

        $director = new Director();

        $director->fill($request->only($fields));

        return $director;
    }

    
    /**
     * Create and return an array representing the Director object from the given Request.
     *
     * @param Request $request The HTTP Request containing the data for the Director object.
     * @return array An array representation of the Director object.
     * @throws HttpException If the validation of Director data fails return Bad Request HTTP.
     */
    public function createObjectFromRequest(Request $request): array
    {

        $utils = new Utils();

        $validationDirectorResult = $this->validate($request);

        if ($utils->isValidationFailed($validationDirectorResult)) {

            throw new HttpException(Response::HTTP_BAD_REQUEST, $validationDirectorResult->getMessageBag() );
        }

        return array_filter((array) $validationDirectorResult, function ($value) {
            return $value != null;
        });
    }
}
