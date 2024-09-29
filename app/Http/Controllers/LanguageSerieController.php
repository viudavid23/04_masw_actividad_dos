<?php

namespace App\Http\Controllers;

use App\Exceptions\Constants;
use App\Http\Contracts\LanguageSerieContract;
use App\Http\Controllers\Validators\LanguageSerieDataValidator;
use App\Util\Utils;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LanguageSerieController extends Controller
{
    protected $languageSerieService;
    protected $languageSerieDataValidator;
    protected $utils;

    public function __construct(Utils $utils, LanguageSerieDataValidator $languageSerieDataValidator, LanguageSerieContract $languageSerieService)
    {
        $this->utils = $utils;
        $this->languageSerieDataValidator = $languageSerieDataValidator;
        $this->languageSerieService = $languageSerieService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $page = $request->query(Utils::NUMBER_PAGE, Utils::DEFAULT_NUMBER_PAGE);

        $pageSize = $request->query(Utils::PAGE_SIZE,  Utils::DEFAULT_PAGE_SIZE);

        $series = $this->languageSerieService->getAll($page, $pageSize);

        $items = $series->items();

        $transformedItems = array_map(function ($item) {
            return [
                'serie_id' => $item->serie_id,
                'language_id' => $item->language_id,
            ];
        }, $items);

        $data = [
            Utils::CURRENT_PAGE_PAGINATE => $series->currentPage(),
            Utils::DATA_PAGINATE => $this->languageSerieService->getDataResponse($transformedItems),
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

        $newLanguageSerie = $this->languageSerieDataValidator->createObjectFromRequest($request);

        $languageerieSaved = $this->languageSerieService->store($newLanguageSerie['serie_id'], $newLanguageSerie['languages']);

        $data = $this->languageSerieService->getLanguageDataResponse($languageerieSaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_RECORD_SAVED, $data);
    }

    /**
     * Display the specified resource.
     */
    public function showBySerie($id)
    {
        $this->utils->isNumericValidArgument($id);

        $languageSeriesSaved = $this->languageSerieService->getBySerieId($id);

        $data = $this->languageSerieService->getLanguageDataResponse($languageSeriesSaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Display the specified resource.
     */
    public function showByLanguage($id)
    {
        $this->utils->isNumericValidArgument($id);

        $seriesLanguageSaved = $this->languageSerieService->getByLanguageId($id);

        $data = $this->languageSerieService->getSerieDataResponse($seriesLanguageSaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $this->utils->isNumericValidArgument($id);

        $currentLanguageSerie = $this->languageSerieDataValidator->createObjectFromRequest($request);

        $languageSeriesUpdated = $this->languageSerieService->update($id, $currentLanguageSerie['languages']);

        $data = $this->languageSerieService->getLanguageDataResponse($languageSeriesUpdated);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_RECORD_UPDATED, $data);
    }

    /**
     * Remove the specified resource from storage..
     */
    public function destroy($id, Request $request)
    {
        $this->utils->isNumericValidArgument($id);

        $currentLanguages = $this->languageSerieDataValidator->createObjectFromRequest($request);

        $languageSeriesDeleted = $this->languageSerieService->delete($id, $currentLanguages['languages']);

        if ($languageSeriesDeleted) {

            return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, Constants::TXT_RECORD_DELETED);
        }
    }
}
