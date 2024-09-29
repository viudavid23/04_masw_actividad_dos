<?php

namespace App\Http\Controllers;

use App\Exceptions\Constants;
use App\Http\Contracts\DirectorSerieContract;
use App\Http\Controllers\Validators\DirectorSerieDataValidator;
use App\Util\Utils;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class DirectorSerieController extends Controller
{
    protected $directorSerieService;
    protected $directorSerieDataValidator;
    protected $utils;

    public function __construct(Utils $utils, DirectorSerieDataValidator $directorSerieDataValidator, DirectorSerieContract $directorSerieService)
    {
        $this->utils = $utils;
        $this->directorSerieDataValidator = $directorSerieDataValidator;
        $this->directorSerieService = $directorSerieService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $page = $request->query(Utils::NUMBER_PAGE, 10);

        $series = $this->directorSerieService->getAll($page);

        $items = $series->items();

        $transformedItems = array_map(function ($item) {
            return [
                'serie_id' => $item->serie_id,
                'director_id' => $item->director_id,
            ];
        }, $items);

        $data = [
            Utils::CURRENT_PAGE_PAGINATE => $series->currentPage(),
            Utils::DATA_PAGINATE => $this->directorSerieService->getDataResponse($transformedItems),
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

        $newDirectorSerie = $this->directorSerieDataValidator->createObjectFromRequest($request);

        $directorerieSaved = $this->directorSerieService->store($newDirectorSerie['serie_id'], $newDirectorSerie['director_ids']);

        $data = $this->directorSerieService->getDirectorDataResponse($directorerieSaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_RECORD_SAVED, $data);
    }

    /**
     * Display the specified resource.
     */
    public function showBySerie($id)
    {
        $this->utils->isNumericValidArgument($id);

        $directorSeriesSaved = $this->directorSerieService->getBySerieId($id);

        $data = $this->directorSerieService->getDirectorDataResponse($directorSeriesSaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Display the specified resource.
     */
    public function showByDirector($id)
    {
        $this->utils->isNumericValidArgument($id);

        $seriesDirectorSaved = $this->directorSerieService->getByDirectorId($id);

        $data = $this->directorSerieService->getSerieDataResponse($seriesDirectorSaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $this->utils->isNumericValidArgument($id);

        $currentDirectorSerie = $this->directorSerieDataValidator->createObjectFromRequest($request);

        $directorSeriesUpdated = $this->directorSerieService->update($id, $currentDirectorSerie['director_ids']);

        $data = $this->directorSerieService->getDirectorDataResponse($directorSeriesUpdated);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_RECORD_UPDATED, $data);
    }

    /**
     * Remove the specified resource from storage..
     */
    public function destroy($id, Request $request)
    {
        $this->utils->isNumericValidArgument($id);

        $currentDirectors = $this->directorSerieDataValidator->createObjectFromRequest($request);

        $directorSeriesDeleted = $this->directorSerieService->delete($id, $currentDirectors['director_ids']);

        if ($directorSeriesDeleted) {

            return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, Constants::TXT_RECORD_DELETED);
        }
    }
}
