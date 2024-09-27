<?php

namespace App\Http\Services\Implementations;

use App\Exceptions\CantExecuteOperation;
use App\Exceptions\Constants;
use App\Util\Utils;
use App\Http\Contracts\ActorContract;
use App\Exceptions\ElementAlreadyExists;
use App\Http\Contracts\CountryContract;
use App\Http\Contracts\PersonContract;
use App\Models\Actor;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Response;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\HttpException;

use function Ramsey\Uuid\v1;

/**
 * Service class responsible to implement Actor model logic
 * 
 */
class ActorService implements ActorContract
{

    const ID_FIELD = "id";
    const STAGE_NAME_FIELD = "stage_name";
    const BIOGRAPHY_FIELD = "biography";
    const AWARDS_FIELD = "awards";
    const HEIGHT_FIELD = "height";
    const PEOPLE_ID_FIELD = "people_id";
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
     * Get all People
     * @param int $page Number page.
     * @return LengthAwarePaginator The Actor set saved in database.
     * @throws HttpException If does not exist Actor records in the database, $page is invalid argument, occurs an error during the query or occurs a general error.
     */
    public function getAll($page): LengthAwarePaginator
    {

        try {
            $actors = Actor::with("person")->paginate($page);

            if ($actors->isEmpty()) {
                throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
            }
            return $actors;
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
     * Get Actor by id.
     * @param int $id Actor identifier.
     * @return Actor The Actor saved in database.
     * @throws HttpException If Actor id does not exists, occurs an error during the query or occurs a general error.
     */
    public function getById($id): Actor
    {

        try {
            $actorSaved = Actor::findOrFail($id);

            return $actorSaved;
        } catch (ModelNotFoundException $e) {
            throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
        } catch (QueryException $e) {
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Store Actor.
     * @param array $newActor Actor to will be save.
     * @param array $newPerson Person to will be save.
     * @return Actor The Actor saved in database.
     * @throws ElementAlreadyExists If the Actor is already recorded in database
     * @throws CantExecuteOperation If database operation can not be completed.
     */
    public function store(array $newActor, array $newPerson): Actor
    {
        try {
            return DB::transaction(function () use ($newActor, $newPerson) {

                $actorDeleted = $this->getActorDeleted($newActor);

                if (!is_null($actorDeleted)) {

                    $personDeleted = $this->personService->getPersonDeleted($newPerson);

                    $personDeleted->restore();

                    $personDeleted->fill($newPerson);

                    $personDeleted->save();

                    $actorDeleted->restore();

                    $actorDeleted->fill($newActor);

                    $actorDeleted->save();

                    return $actorDeleted;
                } else {

                    $personSaved = $this->personService->store($newPerson);

                    $newActor[self::PEOPLE_ID_FIELD] = $personSaved->id;

                    $actor = new Actor($newActor);

                    $actor->save();

                    return $actor;
                }
            });
        } catch (QueryException $e) {

            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Update People.
     * @param int $id Actor identifier.
     * @param array $currentActor Actor to will be update.
     * @return Actor The Actor updated in database.
     * @throws CantExecuteOperation If database operation can not be completed.
     */
    public function update($id, array $currentActor, array $currentPerson)
    {

        $this->validExistingActorById($id);

        try {
            return DB::transaction(function () use ($id, $currentActor, $currentPerson) {

                $actorSaved = $this->getById($id);

                if (is_null($actorSaved)) {
                    throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
                }

                $actorSaved->fill(array_filter($currentActor, fn($field) => $field !== null, ARRAY_FILTER_USE_BOTH));

                $actorSaved->save();

                $personUpdated = $this->personService->update($actorSaved->person->id, $currentPerson);

                $elementsUpdated = [];

                $elementsUpdated['person'] = $personUpdated;
                $elementsUpdated['actor'] = $actorSaved;

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

        $this->validExistingActorById($id);

        try {

            return DB::transaction(function () use ($id) {

                $actorDeleted = $this->getById($id);
                $personId = $actorDeleted->people_id;

                $actorDeleted->delete();

                if ($actorDeleted) {

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
     * @param Actor $Actor Actor model
     * @return array Actor array information
     */
    public function getDataResponse(Actor $actor): array
    {

        $country = $this->getActorCountry($actor->person->country_id);

        $data = [
            self::ID_FIELD => $actor->id,
            self::FIRST_NAME_FIELD => $actor->person->first_name,
            self::LAST_NAME_FIELD => $actor->person->last_name,
            self::BIRTDATE_FIELD => $actor->person->birthdate,
            self::COUNTRY_OBJECT_FIELD => $country,
            self::STAGE_NAME_FIELD => $actor->stage_name,
            self::BIOGRAPHY_FIELD => $actor->biography,
            self::AWARDS_FIELD => $actor->awards,
            self::HEIGHT_FIELD => $actor->height,
            Utils::CREATED_AT_AUDIT_FIELD => $actor->created_at,
            Utils::UPDATED_AT_AUDIT_FIELD => $actor->updated_at
        ];

        return $data;
    }

    /**
     * Make Data Response Object
     * @param array $elementsUpdated Actor and Person model
     * @return array actor array information
     */
    public function makeDataResponse(array $elementsUpdated): array
    {

        $person = $elementsUpdated['person'];
        $actor = $elementsUpdated['actor'];
        $country = $this->getActorCountry($person[self::COUNTRY_ID_FIELD]);

        $data = [
            self::ID_FIELD => $actor->id,
            self::FIRST_NAME_FIELD => $person[self::FIRST_NAME_FIELD],
            self::LAST_NAME_FIELD => $person[self::LAST_NAME_FIELD],
            self::BIRTDATE_FIELD => $person[self::BIRTDATE_FIELD],
            self::COUNTRY_OBJECT_FIELD => $country,
            self::STAGE_NAME_FIELD => $actor[self::STAGE_NAME_FIELD],
            self::BIOGRAPHY_FIELD => $actor[self::BIOGRAPHY_FIELD],
            self::AWARDS_FIELD => $actor[self::AWARDS_FIELD],
            self::HEIGHT_FIELD => $actor[self::HEIGHT_FIELD],
            Utils::CREATED_AT_AUDIT_FIELD => $actor->created_at,
            Utils::UPDATED_AT_AUDIT_FIELD => $actor->updated_at
        ];

        return $data;
    }

    /**
     * Get Actor Country
     * @param int $countryId Country Identifier
     */
    public function getActorCountry($countryId)
    {
        $countrySaved = $this->countryService->getById($countryId);
        $country = [];
        $country['id'] = $countrySaved->id;
        $country['name'] = $countrySaved->name;
        $country['demonym'] = $countrySaved->demonym;
        return $country;
    }

    /**
     * Valid existing Actor recorded by id.
     * 
     * @param int $id The Actor id.
     * @throws HttpException Not found exception if does not exist a record in database.
     */
    private function validExistingActorById($id)
    {
        $actorExists = Actor::where(self::ID_FIELD, $id)->exists();

        if (!$actorExists) {
            throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
        }
    }

    /**
     * Get deleted record in database, if not exists does not return nothing.
     * 
     * @param array $Actor The Actor array information.
     * @return Actor The stored Actor model.
     */
    private function getActorDeleted(array $actor): Actor|null
    {
        try {
            return Actor::withTrashed()
                ->where(self::STAGE_NAME_FIELD, $actor[self::STAGE_NAME_FIELD])
                ->where(self::BIOGRAPHY_FIELD, $actor[self::BIOGRAPHY_FIELD])
                ->where(self::AWARDS_FIELD, $actor[self::AWARDS_FIELD])
                ->whereNotNull(Utils::DELETED_AT_AUDIT_FIELD)
                ->firstOrFail();
        } catch (ModelNotFoundException $ex) {

            return null;
        }
    }
}
