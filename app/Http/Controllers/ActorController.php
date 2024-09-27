<?php

namespace App\Http\Controllers;

use App\Exceptions\Constants;
use App\Http\Contracts\ActorContract;
use App\Http\Controllers\Validators\ActorDataValidator;
use App\Http\Controllers\Validators\PersonDataValidator;
use App\Util\Utils;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ActorController extends Controller
{
    protected $actorService;
    protected $actorDataValidator;
    protected $personDataValidator;
    protected $utils;

    public function __construct(Utils $utils, ActorDataValidator $actorDataValidator, PersonDataValidator $personDataValidator, ActorContract $actorService)
    {
        $this->utils = $utils;
        $this->actorDataValidator = $actorDataValidator;
        $this->personDataValidator = $personDataValidator;
        $this->actorService = $actorService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $page = $request->query(Utils::NUMBER_PAGE, 15);

        $actors = $this->actorService->getAll($page);

        $items = $actors->items();

        $transformedItems = array_map(function ($item) {
            return [
                'id' => $item->id,
                'first_name' => $item->person->first_name,
                'last_name' => $item->person->last_name,
                'birthdate' => $item->person->birthdate,
                'country_id' => $item->person->country_id,
                'stage_name' => $item->stage_name,
                'biography' => $item->biography,
                'awards' => $item->awards,
                'height' => $item->height
            ];
        }, $items);

        $data = [
            Utils::CURRENT_PAGE_PAGINATE => $actors->currentPage(),
            Utils::DATA_PAGINATE => $transformedItems,
            Utils::LAST_PAGE_PAGINATE => $actors->lastPage(),
            Utils::PER_PAGE_PAGINATE => $actors->perPage(),
            Utils::TOTAL_PAGINATE => $actors->total()
        ];

        return  $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $newActor = $this->actorDataValidator->createObjectFromRequest($request);

        $newPerson = $this->personDataValidator->createObjectFromRequest($request);

        $data = $this->actorService->store($newActor, $newPerson);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_RECORD_SAVED, $data);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->utils->isNumericValidArgument($id);

        $actorSaved = $this->actorService->getById($id);

        $data = $this->actorService->getDataResponse($actorSaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $this->utils->isNumericValidArgument($id);

        $currentActor = $this->actorDataValidator->createObjectFromRequest($request);

        $currentPerson = $this->personDataValidator->createObjectFromRequest($request);

        $elementsUpdated = $this->actorService->update($id, $currentActor, $currentPerson);

        $data = $this->actorService->makeDataResponse($elementsUpdated);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_RECORD_UPDATED, $data);
    }

    /**
     * Remove the specified resource from storage..
     */
    public function destroy($id)
    {
        $this->utils->isNumericValidArgument($id);

        $actorDeleted = $this->actorService->delete($id);

        if ($actorDeleted) {

            return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, Constants::TXT_RECORD_DELETED);
        }
    }
}
