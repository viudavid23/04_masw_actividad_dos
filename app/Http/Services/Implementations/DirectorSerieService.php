<?php

namespace App\Http\Services\Implementations;

use App\Exceptions\CantExecuteOperation;
use App\Exceptions\Constants;
use App\Http\Contracts\DirectorContract;
use App\Http\Contracts\CountryContract;
use App\Http\Contracts\DirectorSerieContract;
use App\Http\Contracts\SerieContract;
use App\Models\DirectorSerie;
use App\Models\Director;
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
class DirectorSerieService implements DirectorSerieContract
{

    private $directorContract;

    private $serieContract;

    protected $countryService;

    public function __construct(DirectorContract $directorContract, SerieContract $serieContract, CountryContract $countryService)
    {
        $this->directorContract = $directorContract;
        $this->serieContract = $serieContract;
        $this->countryService = $countryService;
    }

    const TABLE_NAME = "director_series";
    const SERIES_OBJECT = "series";
    const DIRECTORS_OBJECT = "directors";
    const SERIE_ID_FIELD = "serie_id";
    const DIRECTOR_ID_FIELD = "director_id";
    const SERIE_TITLE_FIELD = "title";
    const SERIE_SYNOPSIS_FIELD = "synopsis";
    const SERIE_RELEASE_FIELD = "release_date";
    const DIRECTOR_BEGINNING_CAREER_FIELD = "beginning_career";
    const DIRECTOR_ACTIVE_YEARS_FIELD = "active_years";
    const DIRECTOR_BIOGRAPHY_FIELD = "biography";
    const DIRECTOR_AWARDS_FIELD = "awards";
    const DIRECTOR_PEOPLE_ID_FIELD = "people_id";
    const DIRECTOR_DOC_NUMBER_FIELD = "document_number";
    const DIRECTOR_FIRST_NAME_FIELD = "first_name";
    const DIRECTOR_LAST_NAME_FIELD = "last_name";
    const DIRECTOR_BIRTDATE_FIELD = "birthdate";
    const DIRECTOR_COUNTRY_ID_FIELD = "id";
    const DIRECTOR_COUNTRY_NAME_FIELD = "name";
    const DIRECTOR_COUNTRY_DEMONYM_FIELD = "demonym";
    const DIRECTOR_COUNTRY_OBJECT_FIELD = "country";

    /**
     * Get all records
     * @param int $page Number page.
     * @return LengthAwarePaginator The serie set saved in database.
     * @throws HttpException If does not exist serie records in the database, $page is invalid argument, occurs an error during the query or occurs a general error.
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

            $directorSeries = DirectorSerie::paginate($pageSize, ['*'], 'page', $page);

            if ($directorSeries->isEmpty()) {
                Log::warning("DIRECTOR_SERIE Records not found in database");
                throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
            }
            return $directorSeries;
        } catch (InvalidArgumentException $e) {
            Log::warning("DIRECTOR_SERIE PAGING {$page} invalid. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_BAD_REQUEST, Constants::TXT_INVALID_PAGE_NUMBER);
        } catch (QueryException $e) {
            Log::error("Get DIRECTOR_SERIE paginated by page {$page} failed. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            Log::error("General Exception trying get ALL DIRECTOR_SERIES paginated by page {$page}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            if ($e instanceof HttpException) {
                throw $e;
            }
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Get directors by serie.
     * @param int $id Serie identifier.
     * @return \Illuminate\Database\Eloquent\Collection The director series saved in database.
     * @throws HttpException If serie id does not exists, occurs an error during the query or occurs a general error.
     */
    public function getBySerieId($id)
    {

        try {
            $serie = Serie::findOrFail($id);

            $directors = $serie->directors()->whereNull(self::TABLE_NAME . '.' . Utils::DELETED_AT_AUDIT_FIELD)->get();

            $directorSeries[self::SERIES_OBJECT] = $serie;
            $directorSeries[self::DIRECTORS_OBJECT] = $directors;

            return $directorSeries;
        } catch (ModelNotFoundException $e) {
            Log::warning("SERIE_ID {$id} record not found in database. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
        } catch (QueryException $e) {
            Log::error("Get Serie by SERIE_ID {$id} failed. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            Log::error("General Exception trying get DIRECTORS by SERIE_ID {$id}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Get series by director.
     * @param int $id Director identifier.
     * @return \Illuminate\Database\Eloquent\Collection The series director saved in database.
     * @throws HttpException If director id does not exists, occurs an error during the query or occurs a general error.
     */
    public function getByDirectorId($id)
    {

        try {
            $director = Director::findOrFail($id);

            $series = $director->series()->whereNull(self::TABLE_NAME . '.' . Utils::DELETED_AT_AUDIT_FIELD)->get();

            $directorSeries[self::SERIES_OBJECT] = $series;
            $directorSeries[self::DIRECTORS_OBJECT] = $director;

            return $directorSeries;
        } catch (ModelNotFoundException $e) {
            Log::warning("PLATFORM_ID {$id} record not found in database. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
        } catch (QueryException $e) {
            Log::error("Get Director by PLATFORM_ID {$id} failed. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            Log::error("General Exception trying get SERIES by DIRECTOR_ID {$id}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Store director series.
     * @param int $serieId Serie Identifier to will be save.
     * @param array $directorIds Director Ids to will be save.
     * @return \Illuminate\Database\Eloquent\Collection The director series saved in database.
     * @throws CantExecuteOperation If database operation can not be completed by QueryException.
     */
    public function store($serieId, array $directorIds)
    {
        try {

            $this->checkSerieAndDirectors($serieId, $directorIds);

            foreach ($directorIds as $directorItem) {

                $director = $this->directorContract->getById($directorItem);

                $existing = $director->series()->withTrashed()->wherePivot(self::SERIE_ID_FIELD, $serieId)->first();

                if ($existing) {
                    $existing->pivot->restore();
                } else {
                    $director->series()->attach($serieId);
                }
            }

            return $this->getBySerieId($serieId);
        } catch (QueryException $e) {
            Log::error("Error during store director series records for SERIE_ID {$serieId}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Update plarform series.
     * @param int $id Serie identifier.
     * @param array $directorIds Director Ids to will be save.
     * @return \Illuminate\Database\Eloquent\Collection The director series saved in database.
     * @throws CantExecuteOperation If database operation can not be completed by QueryException
     */
    public function update($serieId, array $directorIds)
    {
        try {

            $this->checkSerieAndDirectors($serieId, $directorIds);

            $serie = $this->serieContract->getById($serieId);

            // Get currently associated directors, including removed ones
            $currentDirectorIds = $serie->directors()->withTrashed()->pluck(self::DIRECTORS_OBJECT . '.' . Utils::ID_FIELD)->toArray();

            $directorsToRestore = [];

            $newDirectorIds = [];

            foreach ($directorIds as $directorItem) {
                $director = $this->directorContract->getById($directorItem);
                $existing = $director->series()->withTrashed()->wherePivot(self::SERIE_ID_FIELD, $serieId)->first();

                if ($existing) {
                    $existing->pivot->restore();
                    $directorsToRestore[] = $directorItem;
                } else {
                    $newDirectorIds[] = $directorItem;
                }
            }

            // Determine the director IDs that should be removed
            // This includes current directors that are not in the new IDs
            $directorsToRemove = array_diff($currentDirectorIds, $directorIds);

            $this->syncDirectors($serie, $newDirectorIds,  $directorsToRestore, $directorsToRemove);

            return $this->getBySerieId($serieId);
        } catch (QueryException $e) {
            Log::error("Error during update director series records for SERIE_ID {$serieId}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $serieId Serie Identifier
     * @param array $directorIds Directors IDs
     * @return bool TRUE if record was deleted
     * @throws QueryException If occurs and error during delete transaction
     */
    public function delete($serieId, array $directorIds)
    {
        try {
            return DB::transaction(function () use ($serieId, $directorIds) {

                $this->checkSerieAndDirectorsBeforeDelete($serieId, $directorIds);

                DirectorSerie::where(self::SERIE_ID_FIELD, $serieId)
                    ->whereIn(self::DIRECTOR_ID_FIELD, $directorIds)
                    ->delete();
                return true;
            });
        } catch (QueryException $e) {
            Log::error("Error during delete director series records for SERIE_ID {$serieId}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Check valid serie and directors before delete
     * @param int $serieId Serie Identifier
     * @param array $directorIds Director identifiers
     * @throws HttpException Conflict if any of directors ids can be deleted
     */
    private function checkSerieAndDirectorsBeforeDelete($serieId, $directorIds)
    {
        $this->serieContract->getById($serieId);
        $directorIdsDeleted = [];
        foreach ($directorIds as $directorItem) {
            $director = $this->directorContract->getById($directorItem);

            $recordRelated = $director->series()->wherePivot(self::SERIE_ID_FIELD, $serieId)->first();
            if (!$recordRelated) {
                Log::warning("No se puede eliminar el DIRECTOR {$directorItem}, no se encuentra relacionado con la SERIE {$serieId}");
                throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
            }

            $recordAlreadyDeleted = $director->series()->withTrashed()->wherePivot(self::SERIE_ID_FIELD, $serieId)->wherePivot(self::DIRECTOR_ID_FIELD, $directorItem)->first();

            if ($recordAlreadyDeleted && $recordAlreadyDeleted->pivot->deleted_at !== null) {
                $directorIdsDeleted[] = $directorItem;
            }
        }

        $directorIds = array_diff($directorIds, $directorIdsDeleted);

        if (empty($directorIds)) {
            Log::warning("Los directores de la serie {$serieId} ya han sido eliminados de la tabla DIRECTOR_SERIE");
            throw new HttpException(Response::HTTP_CONFLICT, Constants::TXT_RECORD_DOESNT_SAVED);
        }
    }

    /**
     * Get All Directors Serie Data Response
     * @param array $directorSeries Series and directors IDs
     * @return array $data All Series and directors
     */
    public function getDataResponse($directorSeries): array
    {
        $directorSeriesElements = [];
        foreach ($directorSeries as $directorSerie) {
            $director = $this->directorContract->getById($directorSerie[self::DIRECTOR_ID_FIELD]);

            $serie = $this->serieContract->getById($directorSerie[self::SERIE_ID_FIELD]);

            $serieObject = $this->makeSerieObject($serie);

            $directorObject = $this->makeDirectorObject($director);

            $directorSerie = [
                self::SERIES_OBJECT => $serieObject,
                self::DIRECTORS_OBJECT => $directorObject,
            ];
            array_push($directorSeriesElements, $directorSerie);
        }
        return $directorSeriesElements;
    }

    /**
     * Get Directors Serie Data Response
     * @param array $directorSeries Plarforms ids
     * @return array $data Serie and related directors
     */
    public function getDirectorDataResponse($directorSeries): array
    {

        $serie = $directorSeries[self::SERIES_OBJECT];

        $directors = $directorSeries[self::DIRECTORS_OBJECT]->map(function ($director) {
            return $this->makeDirectorObject($director);
        });

        $data = $this->makeSerieObject($serie);

        $data[self::DIRECTORS_OBJECT] = $directors;

        return $data;
    }

    /**
     * Get Series Director Data Response
     * @param array $serieDirectors Series ids
     * @return array $data Director and related series
     */
    public function getSerieDataResponse($serieDirectors): array
    {

        $director = $serieDirectors[self::DIRECTORS_OBJECT];

        $series = $serieDirectors[self::SERIES_OBJECT]->map(function ($serie) {
            return $this->makeSerieObject($serie);
        });

        $data = $this->makeDirectorObject($director);

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
     * Make Director Object
     * @param Director $director Director model
     * @return array Director object built
     */
    private function makeDirectorObject(Director $director)
    {
        $person = $director->person;
        $country = $this->getDirectorCountry($director->person->country_id);

        return [
            Utils::ID_FIELD => $director->id,
            self::DIRECTOR_BEGINNING_CAREER_FIELD => $director->beginning_career,
            self::DIRECTOR_ACTIVE_YEARS_FIELD => $director->active_years,
            self::DIRECTOR_BIOGRAPHY_FIELD => $director->biography,
            self::DIRECTOR_AWARDS_FIELD => $director->awards,
            self::DIRECTOR_PEOPLE_ID_FIELD => $director->people_id,
            self::DIRECTOR_DOC_NUMBER_FIELD => $person->document_number,
            self::DIRECTOR_FIRST_NAME_FIELD => $person->first_name,
            self::DIRECTOR_LAST_NAME_FIELD => $person->last_name,
            self::DIRECTOR_BIRTDATE_FIELD => $person->birthdate,
            self::DIRECTOR_COUNTRY_OBJECT_FIELD => $country,
            Utils::CREATED_AT_AUDIT_FIELD => $director->created_at,
            Utils::UPDATED_AT_AUDIT_FIELD => $director->updated_at,
        ];
    }

    /**
     * Get Director Country
     * @param int $countryId Country Identifier
     */
    public function getDirectorCountry($countryId)
    {
        $countrySaved = $this->countryService->getById($countryId);
        $country = [];
        $country[self::DIRECTOR_COUNTRY_ID_FIELD] = $countrySaved->id;
        $country[self::DIRECTOR_COUNTRY_NAME_FIELD] = $countrySaved->name;
        $country[self::DIRECTOR_COUNTRY_DEMONYM_FIELD] = $countrySaved->demonym;
        return $country;
    }

    /**
     * Check valid serie and directors
     * @param int $serieId Serie Identifier
     * @param array $directorIds Director identifiers
     */
    private function checkSerieAndDirectors($serieId, $directorIds)
    {
        $this->serieContract->getById($serieId);

        foreach ($directorIds as $directorItem) {
            $director = $this->directorContract->getById($directorItem);
        }
    }

    /**
     * Synchronize Directors
     * @param Serie $serie Serie model
     * @param array $newDirectorIds New director ids
     * @param array $directorsToRestore Ids of the restored directors
     * @param array $directorsToRemove The director IDs that should be removed
     */
    private function syncDirectors($serie, $newDirectorIds,  $directorsToRestore, $directorsToRemove)
    {
        // Add the new and restored ones
        $serie->directors()->sync(array_merge($newDirectorIds, $directorsToRestore));

        // remove directors that are not in the new list
        $serie->directors()->detach($directorsToRemove);
    }
}
