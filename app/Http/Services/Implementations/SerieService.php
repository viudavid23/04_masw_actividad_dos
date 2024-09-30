<?php

namespace App\Http\Services\Implementations;

use App\Exceptions\CantExecuteOperation;
use App\Exceptions\Constants;
use App\Http\Contracts\SerieContract;
use App\Exceptions\ElementAlreadyExists;
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
class SerieService implements SerieContract
{

    const ID_FIELD = "id";
    const TITLE_FIELD = "title";
    const SYNOPSIS_FIELD = "synopsis";
    const RELEASE_FIELD = "release_date";

    /**
     * Get all Series
     * @param int $page Number page.
     * @param int $pageSize Page size.
     * @return LengthAwarePaginator The serie set saved in database.
     * @throws HttpException If does not exist serie records in the database, $page is invalid argument, occurs an error during the query or occurs a general error.
     */
    public function getAll($page, $pageSize): LengthAwarePaginator
    {

        try {
            $series = Serie::paginate($pageSize, ['*'], 'page', $page);

            if ($series->isEmpty()) {
                throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
            }
            return $series;
        } catch (InvalidArgumentException $e) {
            Log::warning("Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_BAD_REQUEST, Constants::TXT_INVALID_PAGE_NUMBER);
        } catch (QueryException $e) {
            Log::error("Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            Log::error("Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            if ($e instanceof HttpException) {
                throw $e;
            }
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Get serie by id.
     * @param int $id Serie identifier.
     * @return Serie The serie saved in database.
     * @throws HttpException If serie id does not exists, occurs an error during the query or occurs a general error.
     */
    public function getById($id): Serie
    {

        try {
            $serieSaved = Serie::findOrFail($id);

            return $serieSaved;
        } catch (ModelNotFoundException $e) {
            Log::warning("Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
        } catch (QueryException $e) {
            Log::error("Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            Log::error("Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Store serie.
     * @param array $newSerie Serie to will be save.
     * @return Serie The serie saved in database.
     * @throws ElementAlreadyExists If the serie is already recorded in database
     * @throws CantExecuteOperation If database operation can not be completed.
     */
    public function store($newSerie): Serie
    {

        $title = $newSerie["title"];

        try {

            $deletedSerie = $this->getSerieDeletedByTitle($title);

            if (!is_null($deletedSerie)) {
                
                $deletedSerie->restore();

                $deletedSerie->fill($newSerie);

                $deletedSerie->save();

                return $deletedSerie;
            } else {

                $existingSerie = $this->validExistingSerieByTitle($title);

                if (!is_null($existingSerie)) {

                    throw new ElementAlreadyExists(Constants::TXT_RECORD_ALREADY_SAVED);
                }

                $serie = new Serie($newSerie);

                $serie->save();

                return $serie;
            }
        } catch (QueryException $e) {
            Log::error("Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Update series.
     * @param int $id Serie identifier.
     * @param array $currentSerie Serie to will be update.
     * @return Serie The serie updated in database.
     * @throws CantExecuteOperation If database operation can not be completed.
     */
    public function update($id, array $currentSerie)
    {
        try {


            $deletedSerie = $this->getSerieDeletedByIdAndTitle($id, $currentSerie[self::TITLE_FIELD]);

            if (!is_null($deletedSerie)) {

                $deletedSerie->restore();

                $deletedSerie->fill($currentSerie);

                $deletedSerie->save();

                return $deletedSerie;
            }

            $deletedSerie = $this->getSerieDeletedByTitle($currentSerie[self::TITLE_FIELD]);

            if (!is_null($deletedSerie) && $deletedSerie->id != $id) {

                throw new CantExecuteOperation(Constants::TXT_ID_ALREADY_RELATED_TO);
            }

            $serieSaved = $this->getById($id);

            $serieSaved->fill(array_filter($currentSerie, fn($field) => $field !== null));

            $serieSaved->save();

            return $serieSaved;
        } catch (QueryException $e) {
            Log::error("Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
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

                $serie = $this->getById($id);
                $serie->delete();
                return true;
            });
        } catch (QueryException $e) {
            Log::error("Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Get Data Response Object
     * @param Serie $serie Serie model
     * @return array Serie array information
     */
    public function getDataResponse(Serie $serie): array
    {
        $data = [
            self::ID_FIELD => $serie->id,
            self::TITLE_FIELD => $serie->title,
            self::SYNOPSIS_FIELD => $serie->synopsis,
            self::RELEASE_FIELD => $serie->release_date,
            Utils::CREATED_AT_AUDIT_FIELD => $serie->created_at,
            Utils::UPDATED_AT_AUDIT_FIELD => $serie->updated_at
        ];

        return $data;
    }

    /**
     * Get Serie by name.
     * 
     * @param String $title The serie name.
     * @return Serie Serie saved
     */
    private function validExistingSerieByTitle($title): Serie|null
    {
        return Serie::whereRaw('LOWER(title) LIKE ?', [strtolower($title) . '%'])->first();
    }
    
    /**
     * Get deleted record in database, if not exists does not return nothing.
     * 
     * @param String $title The serie title.
     * @return Serie The stored Serie model.
     */
    private function getSerieDeletedByTitle($title): Serie|null
    {

        try {
            return Serie::withTrashed()
                ->whereRaw('LOWER(title) LIKE ?', [strtolower($title) . '%'])
                ->whereNotNull('deleted_at')
                ->firstOrFail();
        } catch (ModelNotFoundException $ex) {

            return null;
        }
    }

    /**
     * Get deleted record in database, if not exists does not return nothing.
     * 
     * @param int $id The serie identification.
     * @param String $title The serie title.
     * @return Serie The stored Serie model.
     */
    private function getSerieDeletedByIdAndTitle($id, $title): Serie|null
    {

        try {
            return Serie::withTrashed()
                ->where(self::ID_FIELD, $id)
                ->whereRaw('LOWER(title) LIKE ?', [strtolower($title) . '%'])
                ->whereNotNull('deleted_at')
                ->firstOrFail();
        } catch (ModelNotFoundException $ex) {

            return null;
        }
    }
}
