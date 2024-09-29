<?php

namespace App\Http\Services\Implementations;

use App\Exceptions\CantExecuteOperation;
use App\Exceptions\Constants;
use App\Util\Utils;
use App\Http\Contracts\DirectorContract;
use App\Exceptions\ElementAlreadyExists;
use App\Http\Contracts\CountryContract;
use App\Http\Contracts\PersonContract;
use App\Models\Director;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Response;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Service class responsible to implement Director model logic
 * 
 */
class DirectorService implements DirectorContract
{

    const ID_FIELD = "id";
    const BEGINNING_CAREER_FIELD = "beginning_career";
    const ACTIVE_YEARS_FIELD = "active_years";
    const BIOGRAPHY_FIELD = "biography";
    const AWARDS_FIELD = "awards";
    const PEOPLE_ID_FIELD = "people_id";
    const DOC_NUMBER_FIELD = "document_number";
    const FIRST_NAME_FIELD = "first_name";
    const LAST_NAME_FIELD = "last_name";
    const BIRTDATE_FIELD = "birthdate";
    const COUNTRY_ID_FIELD = "country_id";
    const COUNTRY_OBJECT_FIELD = "country";

    protected PersonContract $personService;

    protected CountryContract $countryService;

    public function __construct(PersonContract $personService, CountryContract $countryService)
    {
        $this->personService = $personService;
        $this->countryService = $countryService;
    }

    /**
     * Get all Directors
     * @param int $page Number page.
     * @param int $pageSize Page size.
     * @return LengthAwarePaginator The Director set saved in database.
     * @throws HttpException If does not exist Director records in the database, $page is invalid argument, occurs an error during the query or occurs a general error.
     */
    public function getAll($page, $pageSize): LengthAwarePaginator
    {

        try {
            
            if (!is_numeric($page) || $page <= 0) {
                throw new HttpException(Response::HTTP_BAD_REQUEST, Constants::TXT_INVALID_PAGE_NUMBER);
            }
    
            if (!is_numeric($pageSize) || $pageSize <= 0) {
                throw new HttpException(Response::HTTP_BAD_REQUEST, Constants::TXT_INVALID_PAGE_SIZE);
            }
            
            $directors = Director::with("person")->paginate($pageSize, ['*'], 'page', $page);

            if ($directors->isEmpty()) {
                throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
            }
            return $directors;
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
     * Get Director by id.
     * @param int $id Director identifier.
     * @return Director The Director saved in database.
     * @throws HttpException If Director id does not exists, occurs an error during the query or occurs a general error.
     */
    public function getById($id): Director
    {

        try {
            $directorSaved = Director::findOrFail($id);

            return $directorSaved;
        } catch (ModelNotFoundException $e) {
            throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
        } catch (QueryException $e) {
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Store Director.
     * @param array $newDirector Director to will be save.
     * @param array $newPerson Person to will be save.
     * @return Director The Director saved in database.
     * @throws ElementAlreadyExists If the Director is already recorded in database
     * @throws CantExecuteOperation If database operation can not be completed.
     */
    public function store(array $newDirector, array $newPerson): Director
    {
        try {
            return DB::transaction(function () use ($newDirector, $newPerson) {

                $personSaved = $this->personService->store($newPerson);

                $directorDeleted = $this->getDirectorDeleted($personSaved->id);

                if (!is_null($directorDeleted)) {

                    $directorDeleted->restore();

                    $directorDeleted->fill($newDirector);

                    $directorDeleted->save();

                    return $directorDeleted;
                } else {

                    $directorExists = $this->getExistingDirectorByPeopleId($personSaved->id);
                    
                    if ($directorExists) {
                        throw new HttpException(Response::HTTP_CONFLICT, Constants::TXT_RECORD_ALREADY_SAVED);
                    }

                    $newDirector[self::PEOPLE_ID_FIELD] = $personSaved->id;

                    $director = new Director($newDirector);

                    $director->save();

                    return $director;
                }
            });
        } catch (QueryException $e) {

            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Update Director.
     * @param int $id Director identifier.
     * @param array $currentDirector Director to will be update.
     * @return Director The Director updated in database.
     * @throws CantExecuteOperation If database operation can not be completed.
     */
    public function update($id, array $currentDirector, array $currentPerson)
    {
        try {
            return DB::transaction(function () use ($id, $currentDirector, $currentPerson) {

                $directorSaved = $this->getById($id);

                if (is_null($directorSaved)) {
                    throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
                }

                $directorSaved->fill(array_filter($currentDirector, fn($field) => $field !== null, ARRAY_FILTER_USE_BOTH));

                $directorSaved->save();

                $personUpdated = $this->personService->update($directorSaved->person->id, $currentPerson);

                $elementsUpdated = [];

                $elementsUpdated['person'] = $personUpdated;
                $elementsUpdated['director'] = $directorSaved;

                return $elementsUpdated;
            });
        } catch (QueryException $e) {

            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {
        try {

            return DB::transaction(function () use ($id) {

                $directorDeleted = $this->getById($id);
                $personId = $directorDeleted->people_id;

                $directorDeleted->delete();

                if ($directorDeleted) {

                    $this->personService->delete($personId);
                    return true;
                }
            });
        } catch (QueryException $e) {

            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Get Data Response Object
     * @param Director $director Director model
     * @return array Director array information
     */
    public function getDataResponse(Director $director): array
    {

        $country = $this->getDirectorCountry($director->person->country_id);

        $data = [
            self::ID_FIELD => $director->id,
            self::DOC_NUMBER_FIELD => $director->person->document_number,
            self::FIRST_NAME_FIELD => $director->person->first_name,
            self::LAST_NAME_FIELD => $director->person->last_name,
            self::BIRTDATE_FIELD => $director->person->birthdate,
            self::COUNTRY_OBJECT_FIELD => $country,
            self::BEGINNING_CAREER_FIELD => $director->beginning_career,
            self::ACTIVE_YEARS_FIELD => $director->active_years,
            self::BIOGRAPHY_FIELD => $director->biography,
            self::AWARDS_FIELD => $director->awards,
            Utils::CREATED_AT_AUDIT_FIELD => $director->created_at,
            Utils::UPDATED_AT_AUDIT_FIELD => $director->updated_at
        ];

        return $data;
    }

    /**
     * Make Data Response Object
     * @param array $elementsUpdated Director and Person model
     * @return array director array information
     */
    public function makeDataResponse(array $elementsUpdated): array
    {

        $person = $elementsUpdated['person'];
        $director = $elementsUpdated['director'];
        $country = $this->getDirectorCountry($person[self::COUNTRY_ID_FIELD]);

        $data = [
            self::ID_FIELD => $director->id,
            self::DOC_NUMBER_FIELD => $person[self::DOC_NUMBER_FIELD],
            self::FIRST_NAME_FIELD => $person[self::FIRST_NAME_FIELD],
            self::LAST_NAME_FIELD => $person[self::LAST_NAME_FIELD],
            self::BIRTDATE_FIELD => $person[self::BIRTDATE_FIELD],
            self::COUNTRY_OBJECT_FIELD => $country,
            self::BEGINNING_CAREER_FIELD => $director[self::BEGINNING_CAREER_FIELD],
            self::ACTIVE_YEARS_FIELD => $director[self::ACTIVE_YEARS_FIELD],
            self::BIOGRAPHY_FIELD => $director[self::BIOGRAPHY_FIELD],
            self::AWARDS_FIELD => $director[self::AWARDS_FIELD],
            Utils::CREATED_AT_AUDIT_FIELD => $director->created_at,
            Utils::UPDATED_AT_AUDIT_FIELD => $director->updated_at
        ];

        return $data;
    }

    /**
     * Get Director Country
     * @param int $countryId Country Identifier
     */
    public function getDirectorCountry($countryId)
    {
        $countrySaved = $this->countryService->getById($countryId);
        $country = [];
        $country['id'] = $countrySaved->id;
        $country['name'] = $countrySaved->name;
        $country['demonym'] = $countrySaved->demonym;
        return $country;
    }

    /**
     * Get existing Director recorded by people id.
     * 
     * @param int $peopleId The Director people identification.
     */
    private function getExistingDirectorByPeopleId($peopleId)
    {
        return Director::where(self::PEOPLE_ID_FIELD, $peopleId)->exists();
    }

    /**
     * Get deleted record in database, if not exists does not return nothing.
     * 
     * @param int $people_id The Director people identification.
     * @return Director The stored Director model.
     */
    private function getDirectorDeleted($peopleId): Director|null
    {
        try {
            return Director::withTrashed()
                ->where(self::PEOPLE_ID_FIELD, $peopleId)
                ->whereNotNull(Utils::DELETED_AT_AUDIT_FIELD)
                ->firstOrFail();
        } catch (ModelNotFoundException $ex) {

            return null;
        }
    }
}
