<?php

namespace App\Http\Controllers\Validators;

use App\Util\Utils;
use App\Models\Person;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PersonDataValidator
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
            'first_name' => 'required|string|min:1|max:50',
            'last_name' => 'required|string|min:1|max:50',
            'birthdate' => 'required|date|date_format:Y-m-d',
            'country_id' => 'required|integer|exists:countries,id'
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
     * @return Person $person The Person object mapped.
     */
    public function createModelFromRequest(Request $request): Person
    {
        $fields = [
            'first_name',
            'last_name',
            'birthdate',
            'country_id'
        ];

        $person = new Person();

        $person->fill($request->only($fields));

        return $person;
    }

    
    /**
     * Create and return an array representing the Person object from the given Request.
     *
     * @param Request $request The HTTP Request containing the data for the Person object.
     * @return array An array representation of the Person object.
     * @throws HttpException If the validation of Person data fails return Bad Request HTTP.
     */
    public function createObjectFromRequest(Request $request): array
    {

        $utils = new Utils();

        $validationPersonResult = $this->validate($request);

        if ($utils->isValidationFailed($validationPersonResult)) {

            throw new HttpException(Response::HTTP_BAD_REQUEST, $validationPersonResult->getMessageBag() );
        }

        return array_filter((array) $validationPersonResult, function ($value) {
            return $value != null;
        });
    }
}
