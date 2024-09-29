<?php

namespace App\Http\Controllers;

use App\Exceptions\Constants;
use App\Http\Contracts\SerieContract;
use App\Http\Controllers\Validators\SerieDataValidator;
use App\Util\Utils;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class SerieController extends Controller
{
    protected $serieService;
    protected $serieDataValidator;
    protected $utils;

    public function __construct(Utils $utils, SerieDataValidator $serieDataValidator, SerieContract $serieService)
    {
        $this->utils = $utils;
        $this->serieDataValidator = $serieDataValidator;
        $this->serieService = $serieService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $page = $request->query('page', 10);

        $series = $this->serieService->getAll($page);

        $items = $series->items();

        $transformedItems = array_map(function ($item) {
            return [
                'id' => $item->id,
                'title' => $item->title,
                'synopsis' => $item->synopsis,
                'release_date' => $item->release_date
            ];
        }, $items);

        $data = [
            Utils::CURRENT_PAGE_PAGINATE => $series->currentPage(),
            Utils::DATA_PAGINATE => $transformedItems,
            Utils::LAST_PAGE_PAGINATE => $series->lastPage(),
            Utils::PER_PAGE_PAGINATE => $series->perPage(),
            Utils::TOTAL_PAGINATE => $series->total()
        ];

        return  $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $newSerie = $this->serieDataValidator->createObjectFromRequest($request);
       
        $serieSaved = $this->serieService->store($newSerie);

        $data = $this->serieService->getDataResponse($serieSaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_RECORD_SAVED, $data);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->utils->isNumericValidArgument($id);

        $serieSaved = $this->serieService->getById($id);

        $data = $this->serieService->getDataResponse($serieSaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $this->utils->isNumericValidArgument($id);

        $currentSerie = $this->serieDataValidator->createObjectFromRequest($request);

        $serieUpdated = $this->serieService->update($id, $currentSerie);

        $data = $this->serieService->getDataResponse($serieUpdated);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_RECORD_UPDATED, $data);
    }

    /**
     * Remove the specified resource from storage..
     */
    public function destroy($id)
    {
        $this->utils->isNumericValidArgument($id);

        $serieDeleted = $this->serieService->delete($id);

        if ($serieDeleted) {

            return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, Constants::TXT_RECORD_DELETED);
        }
    }
}
