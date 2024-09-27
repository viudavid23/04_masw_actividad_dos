<?php

namespace App\Http\Services\Implementations;

use App\Exceptions\CantExecuteOperation;
use App\Exceptions\Constants;
use App\Util\Utils;
use App\Http\Contracts\PersonContract;
use App\Exceptions\ElementAlreadyExists;
use App\Models\Person;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Response;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Service class responsible to implement Person model logic
 * 
 */
class PersonService implements PersonContract
{

    const ID_FIELD = "id";
    const FIRST_NAME_FIELD = "first_name";
    const LAST_NAME_FIELD = "last_name";
    const BIRTDATE_FIELD = "birthdate";
    const COUNTRY_ID_FIELD = "country_id";

    /**
     * Get all People
     * @param int $page Number page.
     * @return LengthAwarePaginator The Person set saved in database.
     * @throws HttpException If does not exist Person records in the database, $page is invalid argument, occurs an error during the query or occurs a general error.
     */
    public function getAll($page): LengthAwarePaginator
    {

        try {
            $people = Person::paginate($page);

            if ($people->isEmpty()) {
                throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
            }
            return $people;
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
     * Get Person by id.
     * @param int $id Person identifier.
     * @return Person The Person saved in database.
     * @throws HttpException If Person id does not exists, occurs an error during the query or occurs a general error.
     */
    public function getById($id): Person
    {

        try {
            $personSaved = Person::findOrFail($id);

            return $personSaved;
        } catch (ModelNotFoundException $e) {
            throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
        } catch (QueryException $e) {
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Store Person.
     * @param array $newPerson Person to will be save.
     * @return Person The Person saved in database.
     * @throws ElementAlreadyExists If the Person is already recorded in database
     * @throws CantExecuteOperation If database operation can not be completed.
     */
    public function store($newPerson): Person
    {
        try {

            $deletedPerson = $this->getPersonDeleted($newPerson);

            if (!is_null($deletedPerson)) {

                $deletedPerson->restore();

                $deletedPerson->fill($newPerson);

                $deletedPerson->save();

                return $deletedPerson;
            } else {

                $personSaved = $this->getExistingPerson($newPerson);

                if (!is_null($personSaved)) {
                    throw new ElementAlreadyExists(Constants::TXT_RECORD_ALREADY_SAVED);
                }

                $person = new Person($newPerson);

                $person->save();

                return $person;
            }
        } catch (QueryException $e) {

            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Update People.
     * @param int $id Person identifier.
     * @param array $currentPerson Person to will be update.
     * @return Person The Person updated in database.
     * @throws CantExecuteOperation If database operation can not be completed.
     */
    public function update($id, array $currentPerson)
    {

        $this->validExistingPersonById($id);

        try {

            $personSaved = $this->getById($id);

            if (is_null($personSaved)) {
                throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
            }

            $personSaved->fill(array_filter($currentPerson, fn($field) => $field !== null));

            $personSaved->save();

            return $personSaved;
        } catch (QueryException $e) {

            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {

        $this->validExistingPersonById($id);

        try {

            return DB::transaction(function () use ($id) {

                $person = $this->getById($id);
                $person->delete();
                return true;
            });
        } catch (QueryException $e) {

            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Get Data Response Object
     * @param Person $person Person model
     * @return array Person array information
     */
    public function getDataResponse(Person $person): array
    {
        $data = [
            self::ID_FIELD => $person->id,
            self::FIRST_NAME_FIELD => $person->first_name,
            self::LAST_NAME_FIELD => $person->last_name,
            self::BIRTDATE_FIELD => $person->birthdate,
            Utils::CREATED_AT_AUDIT_FIELD => $person->created_at,
            Utils::UPDATED_AT_AUDIT_FIELD => $person->updated_at
        ];

        return $data;
    }

    /**
     * Get existing person recorded.
     * 
     * @param array $person The person array information.
     * @return Person Person model recorded
     */
    private function getExistingPerson(array $person): Person|null
    {
        return Person::where(self::FIRST_NAME_FIELD, $person[self::FIRST_NAME_FIELD])
        ->where(self::LAST_NAME_FIELD, $person[self::LAST_NAME_FIELD])
        ->where(self::BIRTDATE_FIELD, $person[self::BIRTDATE_FIELD])
        ->where(self::COUNTRY_ID_FIELD, $person[self::COUNTRY_ID_FIELD])
        ->first();
    }

    /**
     * Valid existing person recorded by id.
     * 
     * @param int $id The person id.
     * @throws HttpException Not found exception if does not exist a record in database.
     */
    private function validExistingPersonById($id)
    {
        $personExists = Person::where(self::ID_FIELD, $id)->exists();

        if (!$personExists) {
            throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
        }
    }

    /**
     * Get deleted record in database, if not exists does not return nothing.
     * 
     * @param array $person The person array information.
     * @return Person The stored Person model.
     */
    private function getPersonDeleted(array $person): Person|null
    {
        try {
            return Person::withTrashed()
                ->where(self::FIRST_NAME_FIELD, $person[self::FIRST_NAME_FIELD])
                ->where(self::LAST_NAME_FIELD, $person[self::LAST_NAME_FIELD])
                ->where(self::BIRTDATE_FIELD, $person[self::BIRTDATE_FIELD])
                ->where(self::COUNTRY_ID_FIELD, $person[self::COUNTRY_ID_FIELD])
                ->whereNotNull(Utils::DELETED_AT_AUDIT_FIELD)
                ->firstOrFail();
        } catch (ModelNotFoundException $ex) {

            return null;
        }
    }
}
