<?php

namespace App\Http\Services\Implementations;

use App\Exceptions\CantExecuteOperation;
use App\Exceptions\Constants;
use App\Http\Contracts\PlatformContract;
use App\Http\Contracts\PlatformSerieContract;
use App\Http\Contracts\SerieContract;
use App\Models\Platform;
use App\Models\PlatformSerie;
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
class PlatformSerieService implements PlatformSerieContract
{

    private $platformContract;

    private $serieContract;

    public function __construct(PlatformContract $platformContract, SerieContract $serieContract)
    {
        $this->platformContract = $platformContract;
        $this->serieContract = $serieContract;
    }

    const TABLE_NAME = "platform_series";
    const SERIES_OBJECT = "series";
    const PLATFORMS_OBJECT = "platforms";
    const SERIE_ID_FIELD = "serie_id";
    const PLATFORM_ID_FIELD = "platform_id";
    const SERIE_TITLE_FIELD = "title";
    const SERIE_SYNOPSIS_FIELD = "synopsis";
    const SERIE_RELEASE_FIELD = "release_date";
    const PLATFORM_NAME_FIELD = "name";
    const PLATFORM_DESCRIPTION_FIELD = "description";
    const PLATFORM_RELEASE_DATE_FIELD = "release_date";
    const PLATFORM_LOGO_FIELD = "logo";

    /**
     * Get all records
     * @param int $page Number page.
     * @return LengthAwarePaginator The serie set saved in database.
     * @throws HttpException If does not exist serie records in the database, $page is invalid argument, occurs an error during the query or occurs a general error.
     */
    public function getAll($page): LengthAwarePaginator
    {

        try {
            $platformSeries = PlatformSerie::paginate($page);

            if ($platformSeries->isEmpty()) {
                throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
            }
            return $platformSeries;
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
     * Get platforms by serie.
     * @param int $id Serie identifier.
     * @return \Illuminate\Database\Eloquent\Collection The platform series saved in database.
     * @throws HttpException If serie id does not exists, occurs an error during the query or occurs a general error.
     */
    public function getBySerieId($id)
    {

        try {
            $serie = Serie::findOrFail($id);

            $platforms = $serie->platforms()->whereNull(self::TABLE_NAME . '.' . Utils::DELETED_AT_AUDIT_FIELD)->get();

            $platformSeries[self::SERIES_OBJECT] = $serie;
            $platformSeries[self::PLATFORMS_OBJECT] = $platforms;

            return $platformSeries;
        } catch (ModelNotFoundException $e) {
            Log::warning("SERIE_ID {$id} record not found in database. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
        } catch (QueryException $e) {
            Log::error("Get Serie by SERIE_ID {$id} failed. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            Log::error("General Exception. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Get series by platform.
     * @param int $id Platform identifier.
     * @return \Illuminate\Database\Eloquent\Collection The series platform saved in database.
     * @throws HttpException If platform id does not exists, occurs an error during the query or occurs a general error.
     */
    public function getByPlatformId($id)
    {

        try {
            $platform = Platform::findOrFail($id);

            $series = $platform->series()->whereNull(self::TABLE_NAME . '.' . Utils::DELETED_AT_AUDIT_FIELD)->get();

            $platformSeries[self::SERIES_OBJECT] = $series;
            $platformSeries[self::PLATFORMS_OBJECT] = $platform;

            return $platformSeries;
        } catch (ModelNotFoundException $e) {
            Log::warning("PLATFORM_ID {$id} record not found in database. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
        } catch (QueryException $e) {
            Log::error("Get Platform by PLATFORM_ID {$id} failed. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            Log::error("General Exception. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Store platform series.
     * @param int $serieId Serie Identifier to will be save.
     * @param array $platformIds Platform Ids to will be save.
     * @return \Illuminate\Database\Eloquent\Collection The platform series saved in database.
     * @throws CantExecuteOperation If database operation can not be completed by QueryException.
     */
    public function store($serieId, array $platformIds)
    {
        try {

            $this->checkSerieAndPlatforms($serieId, $platformIds);

            foreach ($platformIds as $platformItem) {

                $platform = $this->platformContract->getById($platformItem);

                $existing = $platform->series()->withTrashed()->wherePivot(self::SERIE_ID_FIELD, $serieId)->first();

                if ($existing) {
                    $existing->pivot->restore();
                } else {
                    $platform->series()->attach($serieId);
                }
            }

            return $this->getBySerieId($serieId);
        } catch (QueryException $e) {
            Log::error("Error during store platform series records for SERIE_ID {$serieId}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Update plarform series.
     * @param int $id Serie identifier.
     * @param array $platformIds Platform Ids to will be save.
     * @return \Illuminate\Database\Eloquent\Collection The platform series saved in database.
     * @throws CantExecuteOperation If database operation can not be completed by QueryException
     */
    public function update($serieId, array $platformIds)
    {
        try {

            $this->checkSerieAndPlatforms($serieId, $platformIds);

            $serie = $this->serieContract->getById($serieId);

            // Get currently associated platforms, including removed ones
            $currentPlatformIds = $serie->platforms()->withTrashed()->pluck(self::PLATFORMS_OBJECT .'.'. Utils::ID_FIELD)->toArray();

            $platformsToRestore = [];

            $newPlatformIds = [];

            foreach ($platformIds as $platformItem) {
                $platform = $this->platformContract->getById($platformItem);
                $existing = $platform->series()->withTrashed()->wherePivot(self::SERIE_ID_FIELD, $serieId)->first();

                if ($existing) {
                    $existing->pivot->restore();
                    $platformsToRestore[] = $platformItem;
                } else {
                    $newPlatformIds[] = $platformItem;
                }
            }

            // Determine the platform IDs that should be removed
            // This includes current platforms that are not in the new IDs
            $platformsToRemove = array_diff($currentPlatformIds, $platformIds);

            $this->syncPlatforms($serie, $newPlatformIds,  $platformsToRestore, $platformsToRemove);

            return $this->getBySerieId($serieId);
        } catch (QueryException $e) {
            Log::error("Error during update platform series records for SERIE_ID {$serieId}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $serieId Serie Identifier
     * @param array $platformIds Platforms IDs
     * @return bool TRUE if record was deleted
     * @throws QueryException If occurs and error during delete transaction
     */
    public function delete($serieId, array $platformIds)
    {
        try {
            return DB::transaction(function () use ($serieId, $platformIds) {

                $this->checkSerieAndPlatforms($serieId, $platformIds);

                PlatformSerie::where(self::SERIE_ID_FIELD, $serieId)
                    ->whereIn(self::PLATFORM_ID_FIELD, $platformIds)
                    ->delete();
                return true;
            });
        } catch (QueryException $e) {
            Log::error("Error during delete platform series records for SERIE_ID {$serieId}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Get All Platforms Serie Data Response
     * @param array $platformSeries Series and Platforms IDs
     * @return array $data All Series and platforms
     */
    public function getDataResponse($platformSeries): array
    {
        $platformSeriesElements = [];
        foreach ($platformSeries as $platformSerie) {
            $platform = $this->platformContract->getById($platformSerie[self::PLATFORM_ID_FIELD]);
            $serie = $this->serieContract->getById($platformSerie[self::SERIE_ID_FIELD]);

            $serieObject = [
                Utils::ID_FIELD => $serie->id,
                self::SERIE_TITLE_FIELD => $serie->title,
                self::SERIE_SYNOPSIS_FIELD => $serie->synopsis,
                self::SERIE_RELEASE_FIELD => $serie->release_date,
                Utils::CREATED_AT_AUDIT_FIELD => $serie->created_at,
                Utils::UPDATED_AT_AUDIT_FIELD => $serie->updated_at,
            ];
            $platformObject = [
                Utils::ID_FIELD => $platform->id,
                self::PLATFORM_NAME_FIELD => $platform->name,
                self::PLATFORM_DESCRIPTION_FIELD => $platform->description,
                self::PLATFORM_RELEASE_DATE_FIELD => $platform->release_date,
                self::PLATFORM_LOGO_FIELD => $platform->logo,
                Utils::CREATED_AT_AUDIT_FIELD => $platform->created_at,
                Utils::UPDATED_AT_AUDIT_FIELD => $platform->updated_at,
            ];

            $platformSerie = [
                self::SERIES_OBJECT => $serieObject,
                self::PLATFORMS_OBJECT => $platformObject,
            ];
            array_push($platformSeriesElements, $platformSerie);
        }
        return $platformSeriesElements;
    }

    /**
     * Get Platforms Serie Data Response
     * @param array $platformSeries Plarforms ids
     * @return array $data Serie and related platforms
     */
    public function getPlatformDataResponse($platformSeries): array
    {

        $serie = $platformSeries[self::SERIES_OBJECT];

        $platforms = $platformSeries[self::PLATFORMS_OBJECT]->map(function ($platform) {
            return [
                Utils::ID_FIELD => $platform->id,
                self::PLATFORM_NAME_FIELD => $platform->name,
                self::PLATFORM_DESCRIPTION_FIELD => $platform->description,
                self::PLATFORM_RELEASE_DATE_FIELD => $platform->release_date,
                self::PLATFORM_LOGO_FIELD => $platform->logo,
                Utils::CREATED_AT_AUDIT_FIELD => $platform->created_at,
                Utils::UPDATED_AT_AUDIT_FIELD => $platform->updated_at,
            ];
        });

        $data = [
            Utils::ID_FIELD => $serie->id,
            self::SERIE_TITLE_FIELD => $serie->title,
            self::SERIE_SYNOPSIS_FIELD => $serie->synopsis,
            self::SERIE_RELEASE_FIELD => $serie->release_date,
            Utils::CREATED_AT_AUDIT_FIELD => $serie->created_at,
            Utils::UPDATED_AT_AUDIT_FIELD => $serie->updated_at,
            self::PLATFORMS_OBJECT => $platforms
        ];

        return $data;
    }

    /**
     * Get Series Platform Data Response
     * @param array $seriePlatforms Series ids
     * @return array $data Platform and related series
     */
    public function getSerieDataResponse($seriePlatforms): array
    {

        $platform = $seriePlatforms[self::PLATFORMS_OBJECT];

        $series = $seriePlatforms[self::SERIES_OBJECT]->map(function ($serie) {
            return [
                Utils::ID_FIELD => $serie->id,
                self::SERIE_TITLE_FIELD => $serie->title,
                self::SERIE_SYNOPSIS_FIELD => $serie->synopsis,
                self::SERIE_RELEASE_FIELD => $serie->release_date,
                Utils::CREATED_AT_AUDIT_FIELD => $serie->created_at,
                Utils::UPDATED_AT_AUDIT_FIELD => $serie->updated_at,
            ];
        });

        $data = [
            Utils::ID_FIELD => $platform->id,
            self::PLATFORM_NAME_FIELD => $platform->name,
            self::PLATFORM_DESCRIPTION_FIELD => $platform->description,
            self::PLATFORM_RELEASE_DATE_FIELD => $platform->release_date,
            self::PLATFORM_LOGO_FIELD => $platform->logo,
            Utils::CREATED_AT_AUDIT_FIELD => $platform->created_at,
            Utils::UPDATED_AT_AUDIT_FIELD => $platform->updated_at,
            self::SERIES_OBJECT => $series
        ];

        return $data;
    }

    /**
     * Check valid serie and platforms
     * @param int $serieId Serie Identifier
     * @param array $platformIds Platform identifiers
     */
    private function checkSerieAndPlatforms($serieId, $platformIds)
    {
        $this->serieContract->getById($serieId);

        foreach ($platformIds as $platformItem) {
            $this->platformContract->getById($platformItem);
        }
    }

    /**
     * Synchronize Platforms
     * @param Serie $serie Serie model
     * @param array $newPlatformIds New platform ids
     * @param array $platformsToRestore Ids of the restored platforms
     * @param array $platformsToRemove The platform IDs that should be removed
     */
    private function syncPlatforms($serie, $newPlatformIds,  $platformsToRestore, $platformsToRemove)
    {
        // Add the new and restored ones
        $serie->platforms()->sync(array_merge($newPlatformIds, $platformsToRestore));

        // remove platforms that are not in the new list
        $serie->platforms()->detach($platformsToRemove);
    }
}
