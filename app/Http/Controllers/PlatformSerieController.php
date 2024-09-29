<?php

namespace App\Http\Controllers;

use App\Exceptions\Constants;
use App\Http\Contracts\PlatformSerieContract;
use App\Http\Controllers\Validators\PlatformSerieDataValidator;
use App\Util\Utils;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PlatformSerieController extends Controller
{
    protected $platformSerieService;
    protected $platformSerieDataValidator;
    protected $utils;

    public function __construct(Utils $utils, PlatformSerieDataValidator $platformSerieDataValidator, PlatformSerieContract $platformSerieService)
    {
        $this->utils = $utils;
        $this->platformSerieDataValidator = $platformSerieDataValidator;
        $this->platformSerieService = $platformSerieService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $page = $request->query(Utils::NUMBER_PAGE, 10);

        $series = $this->platformSerieService->getAll($page);

        $items = $series->items();

        $transformedItems = array_map(function ($item) {
            return [
                'serie_id' => $item->serie_id,
                'platform_id' => $item->platform_id,
            ];
        }, $items);

        $data = [
            'current_page' => $series->currentPage(),
            'data' => $this->platformSerieService->getDataResponse($transformedItems),
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

        $newPlatformSerie = $this->platformSerieDataValidator->createObjectFromRequest($request);

        $platformerieSaved = $this->platformSerieService->store($newPlatformSerie['serie_id'], $newPlatformSerie['platform_ids']);

        $data = $this->platformSerieService->getPlatformDataResponse($platformerieSaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_RECORD_SAVED, $data);
    }

    /**
     * Display the specified resource.
     */
    public function showBySerie($id)
    {
        $this->utils->isNumericValidArgument($id);

        $platformSeriesSaved = $this->platformSerieService->getBySerieId($id);

        $data = $this->platformSerieService->getPlatformDataResponse($platformSeriesSaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Display the specified resource.
     */
    public function showByPlatform($id)
    {
        $this->utils->isNumericValidArgument($id);

        $seriesPlatformSaved = $this->platformSerieService->getByPlatformId($id);

        $data = $this->platformSerieService->getSerieDataResponse($seriesPlatformSaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $this->utils->isNumericValidArgument($id);

        $currentPlatformSerie = $this->platformSerieDataValidator->createObjectFromRequest($request);

        $platformSeriesUpdated = $this->platformSerieService->update($id, $currentPlatformSerie['platform_ids']);

        $data = $this->platformSerieService->getPlatformDataResponse($platformSeriesUpdated);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_RECORD_UPDATED, $data);
    }

    /**
     * Remove the specified resource from storage..
     */
    public function destroy($id, Request $request)
    {
        $this->utils->isNumericValidArgument($id);

        $currentPlatforms = $this->platformSerieDataValidator->createObjectFromRequest($request);

        $platformSeriesDeleted = $this->platformSerieService->delete($id, $currentPlatforms['platform_ids']);

        if ($platformSeriesDeleted) {

            return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, Constants::TXT_RECORD_DELETED);
        }
    }
}
