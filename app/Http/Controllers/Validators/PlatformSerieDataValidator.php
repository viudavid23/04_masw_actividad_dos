<?php

namespace App\Http\Controllers\Validators;

use App\Util\Utils;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Validator;
use Symfony\Component\HttpKernel\Exception\HttpException;

class PlatformSerieDataValidator
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
            'serie_id' => 'numeric|between:0,9999',
            'platform_ids' => 'required|array',
            'platform_ids.*' => 'numeric|between:0,9999'
        ];

        // Execute the validation with the defined rules.
        $validator = Validator::make($request->all(), $rules);

        // Check if the validation has failed
        return $validator->fails() ? $validator->errors() : $validator->validated();
    }

    /**
     * Create and return an array representing the PlatformSerie object from the given Request.
     *
     * @param Request $request The HTTP Request containing the data for the PlatformSerie object.
     * @return array An array representation of the PlatformSerie object.
     * @throws HttpException If the validation of PlatformSerie data fails return Bad Request HTTP.
     */
    public function createObjectFromRequest(Request $request): array
    {

        $utils = new Utils();

        $SerievalidationResult = $this->validate($request);

        if ($utils->isValidationFailed($SerievalidationResult)) {

            throw new HttpException(Response::HTTP_BAD_REQUEST, $SerievalidationResult->getMessageBag() );
        }

        return array_filter((array) $SerievalidationResult, function ($value) {
            return $value != null;
        });
    }
}
