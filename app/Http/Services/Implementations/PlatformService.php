<?php

namespace App\Http\Services\Implementations;

use App\Exceptions\CantExecuteOperation;
use App\Exceptions\Constants;
use App\Http\Contracts\PlatformContract;
use App\Exceptions\ElementAlreadyExists;
use App\Models\Platform;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Http\Response;
use InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\HttpException;

/**
 * Service class responsible to implement platform model logic
 * 
 */
class PlatformService implements PlatformContract
{

    /**
     * Get all Platforms
     * @param int $page Number page.
     * @return LengthAwarePaginator The platform set saved in database.
     * @throws HttpException If does not exist platform records in the database, $page is invalid argument, occurs an error during the query or occurs a general error.
     */
    public function getAll($page): LengthAwarePaginator
    {

        try {
            $platforms = Platform::paginate($page);

            if ($platforms->isEmpty()) {
                throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
            }
            return $platforms;
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
     * Get platform by id.
     * @param int $id Platform identifier.
     * @return Platform The platform saved in database.
     * @throws HttpException If platform id does not exists, occurs an error during the query or occurs a general error.
     */
    public function getById($id): Platform
    {

        try {
            $platformSaved = Platform::findOrFail($id);

            return $platformSaved;
        } catch (ModelNotFoundException $e) {
            throw new HttpException(Response::HTTP_NOT_FOUND, Constants::TXT_RECORD_NOT_FOUND_CODE);
        } catch (QueryException $e) {
            throw new HttpException(Response::HTTP_FAILED_DEPENDENCY, Constants::TXT_FAILED_DEPENDENCY_CODE);
        } catch (Exception $e) {
            throw new HttpException(Response::HTTP_INTERNAL_SERVER_ERROR, Constants::TXT_INTERNAL_SERVER_ERROR_CODE);
        }
    }

    /**
     * Store platform.
     * @param array $newPlatform Platform to will be save.
     * @return Platform The platform saved in database.
     * @throws ElementAlreadyExists If the platform is already recorded in database
     * @throws CantExecuteOperation If database operation can not be completed.
     */
    public function store($newPlatform): Platform
    {

        $name = $newPlatform["name"];

        try {

            $deletedPlatform = $this->getPlatformDeletedByName($name);

            if (!is_null($deletedPlatform)) {

                $deletedPlatform->restore();

                $deletedPlatform->fill($newPlatform);

                $deletedPlatform->save();

                return $deletedPlatform;
            } else {

                $existingPlatform = $this->validExistingPlatformByName($name);

                if (!is_null($existingPlatform)) {
        
                    throw new ElementAlreadyExists(Constants::TXT_RECORD_ALREADY_SAVED);
                }

                $platform = new Platform($newPlatform);

                $platform->save();

                return $platform;
            }
        } catch (QueryException $e) {

            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Update platforms.
     * @param int $id Platform identifier.
     * @param array $currentPlatform Platform to will be update.
     * @return Platform The platform updated in database.
     * @throws CantExecuteOperation If database operation can not be completed.
     */
    public function update($id, array $currentPlatform) {
        try {

                $platformSaved = $this->getById($id);

                $platformSaved->fill(array_filter($currentPlatform, fn($field) => $field !== null, ARRAY_FILTER_USE_BOTH));

                $platformSaved->save();

                return $platformSaved;
         
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

                $platform = $this->getById($id);
                $platform->delete();
                return true;
            });
        } catch (QueryException $e) {

            throw new CantExecuteOperation(Constants::TXT_CANT_EXECUTE_OPERATION);
        }
    }

    /**
     * Get Data Response Object
     * @param Platform $platform Platform model
     * @return array Platform array information
     */
    public function getDataResponse(Platform $platform): array
    {
        $data = [
            'id' => $platform->id,
            'name' => $platform->name,
            "description" => $platform->description,
            "release_date" => $platform->release_date,
            "logo" => $platform->logo,
            "created_at" => $platform->created_at,
            "updated_at" => $platform->updated_at
        ];

        return $data;
    }

    /**
     * Get Platform by name.
     * 
     * @param String $name The platform name.
     * @return Platform Platform saved
     */
    private function validExistingPlatformByName($name): Platform|null
    {
        return Platform::where(DB::raw('LOWER(name)'), 'LIKE', strtolower($name) . '%')->first();
    }

    /**
     * Get deleted record in database, if not exists does not return nothing.
     * 
     * @param String $name The platform name.
     * @return Platform The stored Platform model.
     */
    private function getPlatformDeletedByName($name): Platform|null
    {

        try {
            return Platform::withTrashed()
                ->where(DB::raw('LOWER(name)'), 'LIKE', strtolower($name) . '%')
                ->whereNotNull('deleted_at')
                ->firstOrFail();
        } catch (ModelNotFoundException $ex) {

            return null;
        }
    }
}
