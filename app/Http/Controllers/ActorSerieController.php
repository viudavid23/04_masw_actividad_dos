<?php

namespace App\Http\Controllers;

use App\Exceptions\Constants;
use App\Http\Contracts\ActorSerieContract;
use App\Http\Controllers\Validators\ActorSerieDataValidator;
use App\Util\Utils;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ActorSerieController extends Controller
{
    protected $actorSerieService;
    protected $actorSerieDataValidator;
    protected $utils;

    public function __construct(Utils $utils, ActorSerieDataValidator $actorSerieDataValidator, ActorSerieContract $actorSerieService)
    {
        $this->utils = $utils;
        $this->actorSerieDataValidator = $actorSerieDataValidator;
        $this->actorSerieService = $actorSerieService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $page = $request->query(Utils::NUMBER_PAGE, 10);

        $series = $this->actorSerieService->getAll($page);

        $items = $series->items();

        $transformedItems = array_map(function ($item) {
            return [
                'serie_id' => $item->serie_id,
                'actor_id' => $item->actor_id,
            ];
        }, $items);

        $data = [
            'current_page' => $series->currentPage(),
            'data' => $this->actorSerieService->getDataResponse($transformedItems),
            'last_page' => $series->lastPage(),
            'per_page' => $series->perPage(),
            'total' => $series->total()
        ];

        return  $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {

        $newActorSerie = $this->actorSerieDataValidator->createObjectFromRequest($request);

        $actorerieSaved = $this->actorSerieService->store($newActorSerie['serie_id'], $newActorSerie['actor_ids']);

        $data = $this->actorSerieService->getActorDataResponse($actorerieSaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_RECORD_SAVED, $data);
    }

    /**
     * Display the specified resource.
     */
    public function showBySerie($id)
    {
        $this->utils->isNumericValidArgument($id);

        $actorSeriesSaved = $this->actorSerieService->getBySerieId($id);

        $data = $this->actorSerieService->getActorDataResponse($actorSeriesSaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Display the specified resource.
     */
    public function showByActor($id)
    {
        $this->utils->isNumericValidArgument($id);

        $seriesActorSaved = $this->actorSerieService->getByActorId($id);

        $data = $this->actorSerieService->getSerieDataResponse($seriesActorSaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $this->utils->isNumericValidArgument($id);

        $currentActorSerie = $this->actorSerieDataValidator->createObjectFromRequest($request);

        $actorSeriesUpdated = $this->actorSerieService->update($id, $currentActorSerie['actor_ids']);

        $data = $this->actorSerieService->getActorDataResponse($actorSeriesUpdated);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_RECORD_UPDATED, $data);
    }

    /**
     * Remove the specified resource from storage..
     */
    public function destroy($id, Request $request)
    {
        $this->utils->isNumericValidArgument($id);

        $currentActors = $this->actorSerieDataValidator->createObjectFromRequest($request);

        $actorSeriesDeleted = $this->actorSerieService->delete($id, $currentActors['actor_ids']);

        if ($actorSeriesDeleted) {

            return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, Constants::TXT_RECORD_DELETED);
        }
    }
}
