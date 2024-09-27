<?php

namespace App\Http\Services\Implementations;

use App\Exceptions\CantExecuteOperation;
use App\Exceptions\Constants;
use App\Util\Utils;
use App\Http\Contracts\CountryContract;
use App\Models\Country;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;
use Illuminate\Http\Response;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Service class responsible to implement Country model logic
 * 
 */
class CountryService implements CountryContract
{

    const ID_FIELD = "id";
    const NAME_FIELD = "name";
    const DEMONYM_FIELD = "demonym";

    /**
     * Get all Countries
     * @param int $page Number page.
     * @return LengthAwarePaginator The Country set saved in database.
     * @throws HttpException If does not exist Country records in the database, $page is invalid argument, occurs an error during the query or occurs a general error.
     */
    public function getAll($page): LengthAwarePaginator
    {

        try {
            $countries = Country::paginate($page);

            if ($countries->isEmpty()) {
                throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
            }
            return $countries;
        } catch (InvalidArgumentException $e) {
            throw new HttpException(Response::HTTP_BAD_REQUEST, Constants::TXT_INVALID_PAGE_NUMBER);
        } catch (QueryException $e) {
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            if ($e instanceof HttpException) {
                throw $e;
            }
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Get Country by id.
     * @param int $id Country identifier.
     * @return Country The Country saved in database.
     * @throws HttpException If Country id does not exists, occurs an error during the query or occurs a general error.
     */
    public function getById($id): Country
    {

        try {
            $countrySaved = Country::findOrFail($id);

            return $countrySaved;
        } catch (ModelNotFoundException $e) {
            throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
        } catch (QueryException $e) {
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Get Data Response Object
     * @param Country $country Country model
     * @return array Country array information
     */
    public function getDataResponse(Country $country): array
    {
        $data = [
            self::ID_FIELD => $country->id,
            self::NAME_FIELD => $country->name,
            self::DEMONYM_FIELD => $country->demonym,
            Utils::CREATED_AT_AUDIT_FIELD => $country->created_at,
            Utils::UPDATED_AT_AUDIT_FIELD => $country->updated_at
        ];

        return $data;
    }
}
