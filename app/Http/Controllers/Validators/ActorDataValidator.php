<?php

namespace App\Http\Controllers\Validators;

use App\Util\Utils;
use App\Models\Actor;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class ActorDataValidator
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
            'stage_name' => 'nullable|string|min:1|max:50',
            'biography' => 'required|string|min:1|max:5000',
            'awards' => 'nullable|string|min:1|max:1000',
            'height' => 'required|numeric|between:0.50,3.00'
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
     * @return Actor The Actor object mapped.
     */
    public function createModelFromRequest(Request $request): Actor
    {
        $fields = [
            'stage_name',
            'biography',
            'awards',
            'height',
            'people_id'
        ];

        $actor = new Actor();

        $actor->fill($request->only($fields));

        return $actor;
    }

    
    /**
     * Create and return an array representing the Actor object from the given Request.
     *
     * @param Request $request The HTTP Request containing the data for the Actor object.
     * @return array An array representation of the Actor object.
     * @throws HttpException If the validation of Actor data fails return Bad Request HTTP.
     */
    public function createObjectFromRequest(Request $request): array
    {

        $utils = new Utils();

        $validationActorResult = $this->validate($request);

        if ($utils->isValidationFailed($validationActorResult)) {

            throw new HttpException(Response::HTTP_BAD_REQUEST, $validationActorResult->getMessageBag() );
        }

        return array_filter((array) $validationActorResult, function ($value) {
            return $value != null;
        });
    }
}
