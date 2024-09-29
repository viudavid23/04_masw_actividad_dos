<?php

namespace App\Http\Services\Implementations;

use App\Exceptions\CantExecuteOperation;
use App\Exceptions\Constants;
use App\Http\Contracts\LanguageSerieContract;
use App\Http\Contracts\LanguageContract;
use App\Http\Contracts\SerieContract;
use App\Models\LanguageSerie;
use App\Models\Language;
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
class LanguageSerieService implements LanguageSerieContract
{

    private $languageContract;

    private $serieContract;

    public function __construct(LanguageContract $languageContract, SerieContract $serieContract)
    {
        $this->languageContract = $languageContract;
        $this->serieContract = $serieContract;
    }

    const TABLE_NAME = "language_series";
    const SERIES_OBJECT = "series";
    const LANGUAGES_OBJECT = "languages";
    const SERIE_ID_FIELD = "serie_id";
    const LANGUAGE_ID_FIELD = "language_id";
    const SERIE_TITLE_FIELD = "title";
    const SERIE_SYNOPSIS_FIELD = "synopsis";
    const SERIE_RELEASE_FIELD = "release_date";
    const LANGUAGE_NAME_FIELD = "name";
    const LANGUAGE_ISO_CODE_FIELD = "iso_code";
    const LANGUAGE_AUDIO_FIELD = "audio";
    const LANGUAGE_SUBTITLE_FIELD = "subtitle";
    const LANGUAGE_ATTRIBUTES = "language_attributes";


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

            $languageSeries = LanguageSerie::paginate($pageSize, ['*'], 'page', $page);

            if ($languageSeries->isEmpty()) {
                Log::warning("LANGUAGE_SERIE Records not found in database");
                throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
            }
            return $languageSeries;
        } catch (InvalidArgumentException $e) {
            Log::warning("LANGUAGE_SERIE PAGING {$page} invalid. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_BAD_REQUEST, Constants::TXT_INVALID_PAGE_NUMBER);
        } catch (QueryException $e) {
            Log::error("Get LANGUAGE_SERIE paginated by page {$page} failed. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            Log::error("General Exception trying get ALL LANGUAGE_SERIES paginated by page {$page}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            if ($e instanceof HttpException) {
                throw $e;
            }
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Get languages by serie.
     * @param int $id Serie identifier.
     * @return \Illuminate\Database\Eloquent\Collection The language series saved in database.
     * @throws HttpException If serie id does not exists, occurs an error during the query or occurs a general error.
     */
    public function getBySerieId($id)
    {

        try {
            $serie = Serie::findOrFail($id);

            $languages = $serie->languages()
                ->withPivot(self::LANGUAGE_AUDIO_FIELD, self::LANGUAGE_SUBTITLE_FIELD)
                ->whereNull(self::TABLE_NAME . '.' . Utils::DELETED_AT_AUDIT_FIELD)->get();

            $languageSeries[self::SERIES_OBJECT] = $serie;

            $languageSeries[self::LANGUAGES_OBJECT] = $languages->map(function ($language) {
                return [
                    Utils::ID_FIELD => $language->id,
                    self::LANGUAGE_NAME_FIELD => $language->name,
                    self::LANGUAGE_ISO_CODE_FIELD => $language->iso_code,
                    self::LANGUAGE_AUDIO_FIELD => $language->pivot->audio,
                    self::LANGUAGE_SUBTITLE_FIELD => $language->pivot->subtitle,
                    Utils::CREATED_AT_AUDIT_FIELD => $language->created_at,
                    Utils::UPDATED_AT_AUDIT_FIELD => $language->updated_at
                ];
            });

            return $languageSeries;
        } catch (ModelNotFoundException $e) {
            Log::warning("SERIE_ID {$id} record not found in database. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
        } catch (QueryException $e) {
            Log::error("Get Serie by SERIE_ID {$id} failed. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            Log::error("General Exception trying get LANGUAGES by SERIE_ID {$id}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Get series by language.
     * @param int $id Language identifier.
     * @return \Illuminate\Database\Eloquent\Collection The series language saved in database.
     * @throws HttpException If language id does not exists, occurs an error during the query or occurs a general error.
     */
    public function getByLanguageId($id)
    {

        try {
            $language = Language::findOrFail($id);

            $series = $language->series()
                ->withPivot(self::LANGUAGE_ID_FIELD, self::LANGUAGE_AUDIO_FIELD, self::LANGUAGE_SUBTITLE_FIELD)
                ->whereNull(self::TABLE_NAME . '.' . Utils::DELETED_AT_AUDIT_FIELD)->get();

            $languageSeries[self::LANGUAGES_OBJECT] = $language;

            $languageSeries[self::SERIES_OBJECT] = $series->map(function ($serie) {

                return [
                    self::SERIE_ID_FIELD => $serie->id,
                    self::SERIE_TITLE_FIELD => $serie->title,
                    self::SERIE_SYNOPSIS_FIELD => $serie->synopsis,
                    self::SERIE_RELEASE_FIELD => $serie->release_date,
                    Utils::CREATED_AT_AUDIT_FIELD => $serie->created_at,
                    Utils::UPDATED_AT_AUDIT_FIELD => $serie->updated_at,
                    self::LANGUAGE_ATTRIBUTES => [
                        self::LANGUAGE_AUDIO_FIELD => $this->convertStatusModel($serie->pivot->audio),
                        self::LANGUAGE_SUBTITLE_FIELD => $this->convertStatusModel($serie->pivot->subtitle),
                    ]
                ];
            });

            return $languageSeries;
        } catch (ModelNotFoundException $e) {
            Log::warning("PLATFORM_ID {$id} record not found in database. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
        } catch (QueryException $e) {
            Log::error("Get Language by PLATFORM_ID {$id} failed. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            Log::error("General Exception trying get SERIES by LANGUAGE_ID {$id}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Store language series.
     * @param int $serieId Serie Identifier to will be save.
     * @param array $languages Languages complex object to will be save.
     * @return \Illuminate\Database\Eloquent\Collection The language series saved in database.
     * @throws CantExecuteOperation If database operation can not be completed by QueryException.
     */
    public function store($serieId, array $languages)
    {
        try {

            $this->checkSerieAndLanguages($serieId, $languages);

            foreach ($languages as $languageItem) {

                $languageId = $languageItem[Utils::ID_FIELD];
                $audio = $languageItem[self::LANGUAGE_AUDIO_FIELD];
                $subtitle = $languageItem[self::LANGUAGE_SUBTITLE_FIELD];

                $language = $this->languageContract->getById($languageId);

                $existing = $language->series()->withTrashed()->wherePivot(self::SERIE_ID_FIELD, $serieId)->first();

                if ($existing) {

                    $existing->pivot->restore();

                    $existing->pivot->update([
                        self::LANGUAGE_AUDIO_FIELD => $audio,
                        self::LANGUAGE_SUBTITLE_FIELD => $subtitle,
                    ]);
                } else {

                    $language->series()->attach($serieId, [
                        self::LANGUAGE_AUDIO_FIELD => $audio,
                        self::LANGUAGE_SUBTITLE_FIELD => $subtitle,
                    ]);
                }
            }

            return $this->getBySerieId($serieId);
        } catch (QueryException $e) {
            Log::error("Error during store language series records for SERIE_ID {$serieId}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Update plarform series.
     * @param int $id Serie identifier.
     * @param array $languages Language Languages complex object to will be save.
     * @return \Illuminate\Database\Eloquent\Collection The language series saved in database.
     * @throws CantExecuteOperation If database operation can not be completed by QueryException
     */
    public function update($serieId, array $languages)
    {
        try {

            $this->checkSerieAndLanguages($serieId, $languages);

            $serie = $this->serieContract->getById($serieId);

            // Get currently associated languages, including removed ones
            $currentLanguageIds = $serie->languages()->withTrashed()->pluck(Utils::ID_FIELD)->toArray();

            $languagesToRestore = [];
            $newLanguageData = []; // Inicia el array aquí

            foreach ($languages as $languageItem) {
                $languageId = $languageItem[Utils::ID_FIELD];
                $language = $this->languageContract->getById($languageId);
                $existing = $language->series()->withTrashed()->wherePivot(self::SERIE_ID_FIELD, $serieId)->first();

                if ($existing) {
                    $existing->pivot->restore();
                    $languagesToRestore[] = $languageId;
                }

                $newLanguageData[$languageId] = [
                    self::LANGUAGE_AUDIO_FIELD => $languageItem[self::LANGUAGE_AUDIO_FIELD],
                    self::LANGUAGE_SUBTITLE_FIELD => $languageItem[self::LANGUAGE_SUBTITLE_FIELD],
                ];
            }

            // Determine the language IDs that should be removed
            // This includes current languages that are not in the new IDs
            $languagesToRemove = array_diff($currentLanguageIds, array_keys($newLanguageData));

            $this->syncLanguages($serie, $newLanguageData,  $languagesToRestore, $languagesToRemove);

            return $this->getBySerieId($serieId);
        } catch (QueryException $e) {
            Log::error("Error during update language series records for SERIE_ID {$serieId}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Remove the specified resource from storage.
     * @param int $serieId Serie Identifier
     * @param array $languageIds Languages complex object to will be save.
     * @return bool TRUE if record was deleted
     * @throws QueryException If occurs and error during delete transaction
     */
    public function delete($serieId, array $languages)
    {
        try {
            return DB::transaction(function () use ($serieId, $languages) {

                // Extract Languages ids from complex array
                $languageIds = array_map(function ($languageItem) {
                    return $languageItem[Utils::ID_FIELD];
                }, $languages);

                $this->checkSerieAndLanguagesBeforeDelete($serieId, $languageIds);

                LanguageSerie::where(self::SERIE_ID_FIELD, $serieId)
                    ->whereIn(self::LANGUAGE_ID_FIELD, $languageIds)
                    ->delete();
                return true;
            });
        } catch (QueryException $e) {
            Log::error("Error during delete language series records for SERIE_ID {$serieId}. Tracking -> Code: {$e->getCode()} Message: {$e->getMessage()} Exception: {$e}");
            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Check valid serie and languages before delete
     * @param int $serieId Serie Identifier
     * @param array $languageIds Language identifiers
     * @throws HttpException Conflict if any of languages ids can be deleted
     */
    private function checkSerieAndLanguagesBeforeDelete($serieId, $languageIds)
    {
        $this->serieContract->getById($serieId);
        $languageIdsDeleted = [];
        foreach ($languageIds as $languageItem) {
            $language = $this->languageContract->getById($languageItem);

            $recordRelated = $language->series()->wherePivot(self::SERIE_ID_FIELD, $serieId)->first();
            if (!$recordRelated) {
                Log::warning("No se puede eliminar el LANGUAGE {$languageItem}, no se encuentra relacionado con la SERIE {$serieId}");
                throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
            }

            $recordAlreadyDeleted = $language->series()->withTrashed()->wherePivot(self::SERIE_ID_FIELD, $serieId)->wherePivot(self::LANGUAGE_ID_FIELD, $languageItem)->first();

            if ($recordAlreadyDeleted && $recordAlreadyDeleted->pivot->deleted_at !== null) {
                $languageIdsDeleted[] = $languageItem;
            }
        }

        $languageIds = array_diff($languageIds, $languageIdsDeleted);

        if (empty($languageIds)) {
            Log::warning("Los languagees de la serie {$serieId} ya han sido eliminados de la tabla LANGUAGE_SERIE");
            throw new HttpException(Response::HTTP_CONFLICT, Constants::TXT_RECORD_DOESNT_SAVED);
        }
    }

    /**
     * Synchronize Languages
     * @param Serie $serie Serie model
     * @param array $newLanguageData New language ids
     * @param array $languagesToRestore Ids of the restored languages
     * @param array $languagesToRemove The language IDs that should be removed
     */
    private function syncLanguages($serie, $newLanguageData, $languagesToRestore, $languagesToRemove)
    {
        // Restore languages
        foreach ($languagesToRestore as $languageId) {
            $serie->languages()->withTrashed()->updateExistingPivot($languageId, [Utils::DELETED_AT_AUDIT_FIELD => null]);
        }

        // Prepare the array for synchronization that includes the new audio and subtitle data
        $syncData = [];

        // Add the new and restored ones with their respective information
        foreach ($newLanguageData as $languageId => $data) {
            $syncData[$languageId] = [
                self::LANGUAGE_AUDIO_FIELD => $data[self::LANGUAGE_AUDIO_FIELD],
                self::LANGUAGE_SUBTITLE_FIELD => $data[self::LANGUAGE_SUBTITLE_FIELD],
            ];
        }

        // Synchronize only the new languages
        $serie->languages()->syncWithoutDetaching($syncData);

        // Remove languages that are not in the new list
        if (!empty($languagesToRemove)) {
            $serie->languages()->detach($languagesToRemove);
        }
    }

    /**
     * Get All Languages Serie Data Response
     * @param array $languageSeries Series and languages IDs
     * @return array $data All Series and languages
     */
    public function getDataResponse($languageSeries): array
    {
        $languageSeriesElements = [];
        foreach ($languageSeries as $languageSerie) {
            $language = $this->languageContract->getById($languageSerie[self::LANGUAGE_ID_FIELD]);

            $serie = $this->serieContract->getById($languageSerie[self::SERIE_ID_FIELD]);

            $serieObject = $this->makeSerieObject($serie);

            $languageObject = $this->makeLanguageObject($language);

            $languageSerie = [
                self::SERIES_OBJECT => $serieObject,
                self::LANGUAGES_OBJECT => $languageObject,
            ];
            array_push($languageSeriesElements, $languageSerie);
        }
        return $languageSeriesElements;
    }

    /**
     * Get Languages Serie Data Response
     * @param array $languageSeries Languages ids
     * @return array $data Serie and related languages
     */
    public function getLanguageDataResponse($languageSeries): array
    {

        $serie = $languageSeries[self::SERIES_OBJECT];

        $languages = $languageSeries[self::LANGUAGES_OBJECT]->map(function ($language) {
            return $this->makeLanguageObject($language, true);
        });

        $data = $this->makeSerieObject($serie);

        $data[self::LANGUAGES_OBJECT] = $languages;

        return $data;
    }

    /**
     * Get Series Language Data Response
     * @param array $serieLanguages Series ids
     * @return array $data Language and related series
     */
    public function getSerieDataResponse($serieLanguages): array
    {

        $language = $serieLanguages[self::LANGUAGES_OBJECT];

        $series = $serieLanguages[self::SERIES_OBJECT]->map(function ($serie) {

            return $this->makeSerieObject($serie);
        });

        $data = $this->makeLanguageObject($language, false);

        $data[self::SERIES_OBJECT] = $series;

        return $data;
    }

    /**
     * Make Serie Object
     * @param array $serie Serie model
     * @return array Serie object built
     */
    private function makeSerieObject($serie)
    {
        $attribute = [];
        $languageInfo = $serie[self::LANGUAGE_ATTRIBUTES];
        if (!empty($serie[self::LANGUAGE_ATTRIBUTES])) {
            $attribute = [
                self::LANGUAGE_AUDIO_FIELD => $languageInfo[self::LANGUAGE_AUDIO_FIELD],
                self::LANGUAGE_SUBTITLE_FIELD => $languageInfo[self::LANGUAGE_SUBTITLE_FIELD]
            ];
        }

        $serieObject = [
            Utils::ID_FIELD => $serie[self::SERIE_ID_FIELD] == null ? $serie[Utils::ID_FIELD] : $serie[self::SERIE_ID_FIELD],
            self::SERIE_TITLE_FIELD => $serie[self::SERIE_TITLE_FIELD],
            self::SERIE_SYNOPSIS_FIELD => $serie[self::SERIE_SYNOPSIS_FIELD],
            self::SERIE_RELEASE_FIELD => $serie[self::SERIE_RELEASE_FIELD]
        ];

        if (!empty($attribute)) {
            $serieObject[self::LANGUAGE_ATTRIBUTES] = $attribute;
        }

        $serieObject[Utils::CREATED_AT_AUDIT_FIELD] = $serie[Utils::CREATED_AT_AUDIT_FIELD];
        $serieObject[Utils::UPDATED_AT_AUDIT_FIELD] = $serie[Utils::UPDATED_AT_AUDIT_FIELD];

        return $serieObject;
    }


    /**
     * Make Language Object
     * @param array $language Language model
     * @return array Language object built
     */
    private function makeLanguageObject($language, $attributes = false)
    {

        $languageObject = [
            Utils::ID_FIELD => $language[Utils::ID_FIELD],
            self::LANGUAGE_NAME_FIELD => $language[self::LANGUAGE_NAME_FIELD],
            self::LANGUAGE_ISO_CODE_FIELD => $language[self::LANGUAGE_ISO_CODE_FIELD],
            Utils::CREATED_AT_AUDIT_FIELD => $language[Utils::CREATED_AT_AUDIT_FIELD],
            Utils::UPDATED_AT_AUDIT_FIELD => $language[Utils::UPDATED_AT_AUDIT_FIELD]
        ];

        if ($attributes) {
            $languageObject[self::LANGUAGE_AUDIO_FIELD] = $this->convertStatusModel($language[self::LANGUAGE_AUDIO_FIELD]);
            $languageObject[self::LANGUAGE_SUBTITLE_FIELD] = $this->convertStatusModel($language[self::LANGUAGE_SUBTITLE_FIELD]);
        }

        return $languageObject;
    }

    /**
     * Convert boolean to string
     * @param int $statusModel represents flag for audio and subtitle
     * @return string status parsed to string
     */
    private function convertStatusModel(int $statusModel): string
    {
        return $statusModel == 1 ? Utils::ACTIVE_STATUS_FIELD : Utils::INACTIVE_STATUS_FIELD;
    }

    /**
     * Check valid serie and languages
     * @param int $serieId Serie Identifier
     * @param array $languageIds Language identifiers
     */
    private function checkSerieAndLanguages($serieId, $languageIds)
    {
        $this->serieContract->getById($serieId);

        foreach ($languageIds as $languageItem) {
            $this->languageContract->getById($languageItem[Utils::ID_FIELD]);
        }
    }
}
