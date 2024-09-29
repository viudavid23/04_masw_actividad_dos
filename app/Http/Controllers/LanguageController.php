<?php

namespace App\Http\Controllers;

use App\Exceptions\Constants;
use App\Http\Contracts\LanguageContract;
use App\Http\Controllers\Validators\LanguageDataValidator;
use App\Util\Utils;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class LanguageController extends Controller
{
    protected $languageService;
    protected $languageDataValidator;
    protected $utils;

    public function __construct(Utils $utils, LanguageDataValidator $languageDataValidator, LanguageContract $languageService)
    {
        $this->utils = $utils;
        $this->languageDataValidator = $languageDataValidator;
        $this->languageService = $languageService;
    }

     /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $page = $request->query('page', 10);

        $languages = $this->languageService->getAll($page);

        $items = $languages->items();

        $transformedItems = array_map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'iso_code' => $item->iso_code
            ];
        }, $items);

        $data = [
            Utils::CURRENT_PAGE_PAGINATE => $languages->currentPage(),
            Utils::DATA_PAGINATE => $transformedItems,
            Utils::LAST_PAGE_PAGINATE => $languages->lastPage(),
            Utils::PER_PAGE_PAGINATE => $languages->perPage(),
            Utils::TOTAL_PAGINATE => $languages->total()
        ];

        return  $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $newLanguage = $this->languageDataValidator->createObjectFromRequest($request);
       
        $languagesaved = $this->languageService->store($newLanguage);

        $data = $this->languageService->getDataResponse($languagesaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_RECORD_SAVED, $data);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->utils->isNumericValidArgument($id);

        $languagesaved = $this->languageService->getById($id);

        $data = $this->languageService->getDataResponse($languagesaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $this->utils->isNumericValidArgument($id);

        $currentLanguage = $this->languageDataValidator->createObjectFromRequest($request);

        $data = $this->languageService->update($id, $currentLanguage);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_RECORD_UPDATED, $data);
    }

    /**
     * Remove the specified resource from storage..
     */
    public function destroy($id)
    {
        $this->utils->isNumericValidArgument($id);

        $languageDeleted = $this->languageService->delete($id);

        if ($languageDeleted) {

            return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, Constants::TXT_RECORD_DELETED);
        }
    }
}
