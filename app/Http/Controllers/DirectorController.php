<?php

namespace App\Http\Controllers;

use App\Exceptions\Constants;
use App\Http\Contracts\DirectorContract;
use App\Http\Controllers\Validators\DirectorDataValidator;
use App\Http\Controllers\Validators\PersonDataValidator;
use App\Util\Utils;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DirectorController extends Controller
{
    protected $directorService;
    protected $directorDataValidator;
    protected $personDataValidator;
    protected $utils;

    public function __construct(Utils $utils, DirectorDataValidator $directorDataValidator, PersonDataValidator $personDataValidator, DirectorContract $directorService)
    {
        $this->utils = $utils;
        $this->directorDataValidator = $directorDataValidator;
        $this->personDataValidator = $personDataValidator;
        $this->directorService = $directorService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $page = $request->query(Utils::NUMBER_PAGE, 15);

        $directors = $this->directorService->getAll($page);

        $items = $directors->items();

        $transformedItems = array_map(function ($item) {
            return [
                'id' => $item->id,
                'first_name' => $item->person->first_name,
                'last_name' => $item->person->last_name,
                'birthdate' => $item->person->birthdate,
                'country' => $this->directorService->getDirectorCountry($item->person->country_id),
                'beginning_career' => $item->beginning_career,
                'active_years' => $item->active_years,
                'biography' => $item->biography,
                'awards' => $item->awards
            ];
        }, $items);

        $data = [
            Utils::CURRENT_PAGE_PAGINATE => $directors->currentPage(),
            Utils::DATA_PAGINATE => $transformedItems,
            Utils::LAST_PAGE_PAGINATE => $directors->lastPage(),
            Utils::PER_PAGE_PAGINATE => $directors->perPage(),
            Utils::TOTAL_PAGINATE => $directors->total()
        ];

        return  $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $newDirector = $this->directorDataValidator->createObjectFromRequest($request);

        $newPerson = $this->personDataValidator->createObjectFromRequest($request);

        $personSaved = $this->directorService->store($newDirector, $newPerson);

        $data = $this->directorService->getDataResponse($personSaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_RECORD_SAVED, $data);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->utils->isNumericValidArgument($id);

        $directorSaved = $this->directorService->getById($id);

        $data = $this->directorService->getDataResponse($directorSaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $this->utils->isNumericValidArgument($id);

        $currentDirector = $this->directorDataValidator->createObjectFromRequest($request);

        $currentPerson = $this->personDataValidator->createObjectFromRequest($request);

        $elementsUpdated = $this->directorService->update($id, $currentDirector, $currentPerson);

        $data = $this->directorService->makeDataResponse($elementsUpdated);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_RECORD_UPDATED, $data);
    }

    /**
     * Remove the specified resource from storage..
     */
    public function destroy($id)
    {
        $this->utils->isNumericValidArgument($id);

        $directorDeleted = $this->directorService->delete($id);

        if ($directorDeleted) {

            return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, Constants::TXT_RECORD_DELETED);
        }
    }
}
