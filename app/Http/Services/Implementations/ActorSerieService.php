<?php

namespace App\Http\Services\Implementations;

use App\Exceptions\CantExecuteOperation;
use App\Exceptions\Constants;
use App\Http\Contracts\ActorSerieContract;
use App\Http\Contracts\ActorContract;
use App\Http\Contracts\CountryContract;
use App\Http\Contracts\SerieContract;
use App\Models\ActorSerie;
use App\Models\Actor;
use App\Models\Serie;
use App\Util\Utils;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Log;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Service class responsible to implement serie model logic
 * 
 */
class ActorSerieService implements ActorSerieContract
{

    private $actorContract;

    private $serieContract;

    protected $countryService;

    public function __construct(ActorContract $actorContract, SerieContract $serieContract, CountryContract $countryService)
    {
        $this->actorContract = $actorContract;
        $this->serieContract = $serieContract;
        $this->countryService = $countryService;
    }

    const TABLE_NAME = "actor_series";
    const SERIES_OBJECT = "series";
    const ACTORS_OBJECT = "actors";
    const SERIE_ID_FIELD = "serie_id";
    const ACTOR_ID_FIELD = "actor_id";
    const SERIE_TITLE_FIELD = "title";
    const SERIE_SYNOPSIS_FIELD = "synopsis";
    const SERIE_RELEASE_FIELD = "release_date";
    const ACTOR_STAGE_NAME_FIELD = "stage_name";
    const ACTOR_BIOGRAPHY_FIELD = "biography";
    const ACTOR_AWARDS_FIELD = "awards";
    const ACTOR_HEIGHT_FIELD = "height";
    const ACTOR_PEOPLE_ID_FIELD = "people_id";
    const ACTOR_DOC_NUMBER_FIELD = "document_number";
    const ACTOR_FIRST_NAME_FIELD = "first_name";
    const ACTOR_LAST_NAME_FIELD = "last_name";
    const ACTOR_BIRTDATE_FIELD = "birthdate";
    const ACTOR_COUNTRY_ID_FIELD = "id";
    const ACTOR_COUNTRY_NAME_FIELD = "name";
    const ACTOR_COUNTRY_DEMONYM_FIELD = "demonym";
    const ACTOR_COUNTRY_OBJECT_FIELD = "country";

    /**
     * Get all records
     * @param int $page Number page.
     * @return LengthAwarePaginator The serie set saved in database.
     * @throws HttpException If does not exist serie records in the database, $page is invalid argument, occurs an error during the query or occurs a general error.
     */
    public function getAll($page): LengthAwarePaginator
    {

        try {
            $actorSeries = ActorSerie::paginate($page);

            if ($actorSeries->isEmpty()) {
                Log::warning("ACTOR_SERIE Records not found in database");
                throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
            }
            return $actorSeries;
        } catch (InvalidArgumentException $e) {
            Log::warning("ACTOR_SERIE PAGING {$page} invalid. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_BAD_REQUEST, Constants::TXT_INVALID_PAGE_NUMBER);
        } catch (QueryException $e) {
            Log::error("Get ACTOR_SERIE paginated by page {$page} failed. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            Log::error("General Exception trying get ALL ACTOR_SERIES paginated by page {$page}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            if ($e instanceof HttpException) {
                throw $e;
            }
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Get actors by serie.
     * @param int $id Serie identifier.
     * @return \Illuminate\Database\Eloquent\Collection The actor series saved in database.
     * @throws HttpException If serie id does not exists, occurs an error during the query or occurs a general error.
     */
    public function getBySerieId($id)
    {

        try {
            $serie = Serie::findOrFail($id);

            $actors = $serie->actors()->whereNull(self::TABLE_NAME . '.' . Utils::DELETED_AT_AUDIT_FIELD)->get();

            $actorSeries[self::SERIES_OBJECT] = $serie;
            $actorSeries[self::ACTORS_OBJECT] = $actors;

            return $actorSeries;
        } catch (ModelNotFoundException $e) {
            Log::warning("SERIE_ID {$id} record not found in database. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
        } catch (QueryException $e) {
            Log::error("Get Serie by SERIE_ID {$id} failed. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            Log::error("General Exception trying get ACTORS by SERIE_ID {$id}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Get series by actor.
     * @param int $id Actor identifier.
     * @return \Illuminate\Database\Eloquent\Collection The series actor saved in database.
     * @throws HttpException If actor id does not exists, occurs an error during the query or occurs a general error.
     */
    public function getByActorId($id)
    {

        try {
            $actor = Actor::findOrFail($id);

            $series = $actor->series()->whereNull(self::TABLE_NAME . '.' . Utils::DELETED_AT_AUDIT_FIELD)->get();

            $actorSeries[self::SERIES_OBJECT] = $series;
            $actorSeries[self::ACTORS_OBJECT] = $actor;

            return $actorSeries;
        } catch (ModelNotFoundException $e) {
            Log::warning("PLATFORM_ID {$id} record not found in database. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
        } catch (QueryException $e) {
            Log::error("Get Actor by PLATFORM_ID {$id} failed. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            Log::error("General Exception trying get SERIES by ACTOR_ID {$id}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Store actor series.
     * @param int $serieId Serie Identifier to will be save.
     * @param array $actorIds Actor Ids to will be save.
     * @return \Illuminate\Database\Eloquent\Collection The actor series saved in database.
     * @throws CantExecuteOperation If database operation can not be completed by QueryException.
     */
    public function store($serieId, array $actorIds)
    {
        try {

            $this->checkSerieAndActors($serieId, $actorIds);

            foreach ($actorIds as $actorItem) {

                $actor = $this->actorContract->getById($actorItem);

                $existing = $actor->series()->withTrashed()->wherePivot(self::SERIE_ID_FIELD, $serieId)->first();

                if ($existing) {
                    $existing->pivot->restore();
                } else {
                    $actor->series()->attach($serieId);
                }
            }

            return $this->getBySerieId($serieId);
        } catch (QueryException $e) {
            Log::error("Error during store actor series records for SERIE_ID {$serieId}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Update plarform series.
     * @param int $id Serie identifier.
     * @param array $actorIds Actor Ids to will be save.
     * @return \Illuminate\Database\Eloquent\Collection The actor series saved in database.
     * @throws CantExecuteOperation If database operation can not be completed by QueryException
     */
    public function update($serieId, array $actorIds)
    {
        try {

            $this->checkSerieAndActors($serieId, $actorIds);

            $serie = $this->serieContract->getById($serieId);

            // Get currently associated actors, including removed ones
            $currentActorIds = $serie->actors()->withTrashed()->pluck(self::ACTORS_OBJECT . '.' . Utils::ID_FIELD)->toArray();

            $actorsToRestore = [];

            $newActorIds = [];

            foreach ($actorIds as $actorItem) {
                $actor = $this->actorContract->getById($actorItem);
                $existing = $actor->series()->withTrashed()->wherePivot(self::SERIE_ID_FIELD, $serieId)->first();

                if ($existing) {
                    $existing->pivot->restore();
                    $actorsToRestore[] = $actorItem;
                } else {
                    $newActorIds[] = $actorItem;
                }
            }

            // Determine the actor IDs that should be removed
            // This includes current actors that are not in the new IDs
            $actorsToRemove = array_diff($currentActorIds, $actorIds);

            $this->syncActors($serie, $newActorIds,  $actorsToRestore, $actorsToRemove);

            return $this->getBySerieId($serieId);
        } catch (QueryException $e) {
            Log::error("Error during update actor series records for SERIE_ID {$serieId}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $serieId Serie Identifier
     * @param array $actorIds Actors IDs
     * @return bool TRUE if record was deleted
     * @throws QueryException If occurs and error during delete transaction
     */
    public function delete($serieId, array $actorIds)
    {
        try {
            return DB::transaction(function () use ($serieId, $actorIds) {

                $this->checkSerieAndActorsBeforeDelete($serieId, $actorIds);

                ActorSerie::where(self::SERIE_ID_FIELD, $serieId)
                    ->whereIn(self::ACTOR_ID_FIELD, $actorIds)
                    ->delete();
                return true;
            });
        } catch (QueryException $e) {
            Log::error("Error during delete actor series records for SERIE_ID {$serieId}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Check valid serie and actors before delete
     * @param int $serieId Serie Identifier
     * @param array $actorIds Actor identifiers
     * @throws HttpException Conflict if any of actors ids can be deleted
     */
    private function checkSerieAndActorsBeforeDelete($serieId, $actorIds)
    {
        $this->serieContract->getById($serieId);
        $actorIdsDeleted = [];
        foreach ($actorIds as $actorItem) {
            $actor = $this->actorContract->getById($actorItem);

            $recordRelated = $actor->series()->wherePivot(self::SERIE_ID_FIELD, $serieId)->first();
            if (!$recordRelated) {
                Log::warning("No se puede eliminar el ACTOR {$actorItem}, no se encuentra relacionado con la SERIE {$serieId}");
                throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
            }

            $recordAlreadyDeleted = $actor->series()->withTrashed()->wherePivot(self::SERIE_ID_FIELD, $serieId)->wherePivot(self::ACTOR_ID_FIELD, $actorItem)->first();

            if ($recordAlreadyDeleted && $recordAlreadyDeleted->pivot->deleted_at !== null) {
                $actorIdsDeleted[] = $actorItem;
            }
        }

        $actorIds = array_diff($actorIds, $actorIdsDeleted);

        if (empty($actorIds)) {
            Log::warning("Los actores de la serie {$serieId} ya han sido eliminados de la tabla ACTOR_SERIE");
            throw new HttpException(Response::HTTP_CONFLICT, Constants::TXT_RECORD_DOESNT_SAVED);
        }
    }

    /**
     * Get All Actors Serie Data Response
     * @param array $actorSeries Series and actors IDs
     * @return array $data All Series and actors
     */
    public function getDataResponse($actorSeries): array
    {
        $actorSeriesElements = [];
        foreach ($actorSeries as $actorSerie) {
            $actor = $this->actorContract->getById($actorSerie[self::ACTOR_ID_FIELD]);

            $serie = $this->serieContract->getById($actorSerie[self::SERIE_ID_FIELD]);

            $serieObject = $this->makeSerieObject($serie);

            $actorObject = $this->makeActorObject($actor);

            $actorSerie = [
                self::SERIES_OBJECT => $serieObject,
                self::ACTORS_OBJECT => $actorObject,
            ];
            array_push($actorSeriesElements, $actorSerie);
        }
        return $actorSeriesElements;
    }

    /**
     * Get Actors Serie Data Response
     * @param array $actorSeries Plarforms ids
     * @return array $data Serie and related actors
     */
    public function getActorDataResponse($actorSeries): array
    {

        $serie = $actorSeries[self::SERIES_OBJECT];

        $actors = $actorSeries[self::ACTORS_OBJECT]->map(function ($actor) {
            return $this->makeActorObject($actor);
        });

        $data = $this->makeSerieObject($serie);

        $data[self::ACTORS_OBJECT] = $actors;

        return $data;
    }

    /**
     * Get Series Actor Data Response
     * @param array $serieActors Series ids
     * @return array $data Actor and related series
     */
    public function getSerieDataResponse($serieActors): array
    {

        $actor = $serieActors[self::ACTORS_OBJECT];

        $series = $serieActors[self::SERIES_OBJECT]->map(function ($serie) {
            return $this->makeSerieObject($serie);
        });

        $data = $this->makeActorObject($actor);

        $data[self::SERIES_OBJECT] = $series;

        return $data;
    }

    /**
     * Make Serie Object
     * @param Serie $serie Serie model
     * @return array Serie object built
     */
    private function makeSerieObject(Serie $serie)
    {
        return [
            Utils::ID_FIELD => $serie->id,
            self::SERIE_TITLE_FIELD => $serie->title,
            self::SERIE_SYNOPSIS_FIELD => $serie->synopsis,
            self::SERIE_RELEASE_FIELD => $serie->release_date,
            Utils::CREATED_AT_AUDIT_FIELD => $serie->created_at,
            Utils::UPDATED_AT_AUDIT_FIELD => $serie->updated_at,
        ];
    }

    /**
     * Make Actor Object
     * @param Actor $actor Actor model
     * @return array Actor object built
     */
    private function makeActorObject(Actor $actor)
    {
        $person = $actor->person;
        $country = $this->getActorCountry($actor->person->country_id);

        return [
            Utils::ID_FIELD => $actor->id,
            self::ACTOR_STAGE_NAME_FIELD => $actor->stage_name,
            self::ACTOR_BIOGRAPHY_FIELD => $actor->biography,
            self::ACTOR_AWARDS_FIELD => $actor->awards,
            self::ACTOR_HEIGHT_FIELD => $actor->height,
            self::ACTOR_PEOPLE_ID_FIELD => $actor->people_id,
            self::ACTOR_DOC_NUMBER_FIELD => $person->document_number,
            self::ACTOR_FIRST_NAME_FIELD => $person->first_name,
            self::ACTOR_LAST_NAME_FIELD => $person->last_name,
            self::ACTOR_BIRTDATE_FIELD => $person->birthdate,
            self::ACTOR_COUNTRY_OBJECT_FIELD => $country,
            Utils::CREATED_AT_AUDIT_FIELD => $actor->created_at,
            Utils::UPDATED_AT_AUDIT_FIELD => $actor->updated_at,
        ];
    }

    /**
     * Get Actor Country
     * @param int $countryId Country Identifier
     */
    public function getActorCountry($countryId)
    {
        $countrySaved = $this->countryService->getById($countryId);
        $country = [];
        $country[self::ACTOR_COUNTRY_ID_FIELD] = $countrySaved->id;
        $country[self::ACTOR_COUNTRY_NAME_FIELD] = $countrySaved->name;
        $country[self::ACTOR_COUNTRY_DEMONYM_FIELD] = $countrySaved->demonym;
        return $country;
    }

    /**
     * Check valid serie and actors
     * @param int $serieId Serie Identifier
     * @param array $actorIds Actor identifiers
     */
    private function checkSerieAndActors($serieId, $actorIds)
    {
        $this->serieContract->getById($serieId);

        foreach ($actorIds as $actorItem) {
            $actor = $this->actorContract->getById($actorItem);
        }
    }

    /**
     * Synchronize Actors
     * @param Serie $serie Serie model
     * @param array $newActorIds New actor ids
     * @param array $actorsToRestore Ids of the restored actors
     * @param array $actorsToRemove The actor IDs that should be removed
     */
    private function syncActors($serie, $newActorIds,  $actorsToRestore, $actorsToRemove)
    {
        // Add the new and restored ones
        $serie->actors()->sync(array_merge($newActorIds, $actorsToRestore));

        // remove actors that are not in the new list
        $serie->actors()->detach($actorsToRemove);
    }
}
