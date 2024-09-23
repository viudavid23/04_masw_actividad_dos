<?php

namespace App\Http\Services\Implementations;

use App\Exceptions\CantExecuteOperation;
use App\Exceptions\Constants;
use App\Http\Contracts\LanguageContract;
use App\Exceptions\ElementAlreadyExists;
use App\Models\Language;
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
 * Service class responsible to implement Language model logic
 * 
 */
class LanguageService implements LanguageContract
{

    /**
     * Get all Languages
     * @param int $page Number page.
     * @return LengthAwarePaginator The Language set saved in database.
     * @throws HttpException If does not exist Language records in the database, $page is invalid argument, occurs an error during the query or occurs a general error.
     */
    public function getAll($page): LengthAwarePaginator
    {

        try {
            $languages = Language::paginate($page);

            if ($languages->isEmpty()) {
                throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
            }
            return $languages;
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
     * Get Language by id.
     * @param int $id Language identifier.
     * @return Language The Language saved in database.
     * @throws HttpException If Language id does not exists, occurs an error during the query or occurs a general error.
     */
    public function getById($id): Language
    {

        try {
            $languageSaved = Language::findOrFail($id);

            return $languageSaved;
        } catch (ModelNotFoundException $e) {
            throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
        } catch (QueryException $e) {
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Store Language.
     * @param array $newLanguage Language to will be save.
     * @return Language The Language saved in database.
     * @throws ElementAlreadyExists If the Language is already recorded in database
     * @throws CantExecuteOperation If database operation can not be completed.
     */
    public function store($newLanguage): Language
    {

        $isoCode = $newLanguage["iso_code"];

        try {

            $deletedLanguage = $this->getLanguageDeletedByName($isoCode);

            if (!is_null($deletedLanguage)) {

                $deletedLanguage->restore();

                $deletedLanguage->fill($newLanguage);

                $deletedLanguage->save();

                return $deletedLanguage;
            } else {

                $existingLanguage = $this->validExistingLanguageByIsoCode($isoCode);

                if (!is_null($existingLanguage)) {

                    throw new ElementAlreadyExists(Constants::TXT_RECORD_ALREADY_SAVED);
                }

                $language = new Language($newLanguage);

                $language->save();

                return $language;
            }
        } catch (QueryException $e) {

            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Update Languages.
     * @param int $id Language identifier.
     * @param array $currentLanguage Language to will be update.
     * @return Language The Language updated in database.
     * @throws CantExecuteOperation If database operation can not be completed.
     */
    public function update($id, array $currentLanguage)
    {

        $this->validateDefaultLanguage($id);

        try {

            $languageSaved = $this->getById($id);

            $existingLanguage = $this->validExistingLanguageByIdAndIsoCode($id, $currentLanguage['iso_code']);

            if (!is_null($existingLanguage)) {

                throw new ElementAlreadyExists(Constants::TXT_RECORD_ALREADY_SAVED);
            }

            $languageSaved->fill(array_filter($currentLanguage, fn($field) => $field !== null));

            $languageSaved->save();

            return $languageSaved;
        } catch (QueryException $e) {

            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete($id)
    {

        $this->validateDefaultLanguage($id);

        try {

            return DB::transaction(function () use ($id) {

                $language = $this->getById($id);
                $language->delete();
                return true;
            });
        } catch (QueryException $e) {

            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Get Data Response Object
     * @param Language $Language Language model
     * @return array Language array information
     */
    public function getDataResponse(Language $language): array
    {
        $data = [
            'id' => $language->id,
            'name' => $language->name,
            "iso_code" => $language->iso_code,
            "created_at" => $language->created_at,
            "updated_at" => $language->updated_at
        ];

        return $data;
    }

    /**
     * Get Language by iso code.
     * 
     * @param String $isoCode The ISO CODE Language.
     * @return Language Language saved
     */
    private function validExistingLanguageByIsoCode($isoCode): Language|null
    {
        return Language::where(DB::raw('LOWER(iso_code)'), 'LIKE', strtolower($isoCode) . '%')->first();
    }

    private function validExistingLanguageByIdAndIsoCode($id, $isoCode): Language|null
    {
        return Language::where(DB::raw('LOWER(iso_code)'), 'LIKE', strtolower($isoCode) . '%')
            ->where('id', '<>', $id)
            ->first();
    }


    private function validateDefaultLanguage($id)
    {
        if ($id == 1) {
            Log::warning("El id {$id} no puede ser eliminado porque es el idioma por defecto");
            throw new CantExecuteOperation(Constants::TXT_DEFAULT_LANGUAGE);
        }
    }

    /**
     * Get deleted record in database, if not exists does not return nothing.
     * 
     * @param String $name The Language name.
     * @return Language The stored Language model.
     */
    private function getLanguageDeletedByName($isoCode): Language|null
    {

        try {
            return Language::withTrashed()
                ->where(DB::raw('LOWER(iso_code)'), 'LIKE', strtolower($isoCode) . '%')
                ->whereNotNull('deleted_at')
                ->firstOrFail();
        } catch (ModelNotFoundException $ex) {

            return null;
        }
    }
}
