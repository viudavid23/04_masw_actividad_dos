<?php

namespace App\Http\Controllers;

use App\Exceptions\Constants;
use App\Http\Contracts\PlatformContract;
use App\Http\Controllers\Validators\PlatformDataValidator;
use App\Util\Utils;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class PlatformController extends Controller
{

    protected $platformService;
    protected $platformDataValidator;
    protected $utils;

    public function __construct(Utils $utils, PlatformDataValidator $platformDataValidator, PlatformContract $platformService)
    {
        $this->utils = $utils;
        $this->platformDataValidator = $platformDataValidator;
        $this->platformService = $platformService;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $page = $request->query(Utils::NUMBER_PAGE, Utils::DEFAULT_NUMBER_PAGE);

        $pageSize = $request->query(Utils::PAGE_SIZE,  Utils::DEFAULT_PAGE_SIZE);

        $platforms = $this->platformService->getAll($page, $pageSize);

        $items = $platforms->items();

        $transformedItems = array_map(function ($item) {
            return [
                'id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'release_date' => $item->release_date,
                'logo' => $item->logo
            ];
        }, $items);

        $data = [
            Utils::CURRENT_PAGE_PAGINATE => $platforms->currentPage(),
            Utils::DATA_PAGINATE => $transformedItems,
            Utils::LAST_PAGE_PAGINATE => $platforms->lastPage(),
            Utils::PER_PAGE_PAGINATE => $platforms->perPage(),
            Utils::TOTAL_PAGINATE => $platforms->total()
        ];

        return  $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $newPlatform = $this->platformDataValidator->createObjectPlatformRequest($request);
       
        $data = $this->platformService->store($newPlatform);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_RECORD_SAVED, $data);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $this->utils->isNumericValidArgument($id);

        $platformSaved = $this->platformService->getById($id);

        $data = $this->platformService->getDataResponse($platformSaved);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update($id, Request $request)
    {
        $this->utils->isNumericValidArgument($id);

        $currentPlatform = $this->platformDataValidator->createObjectPlatformRequest($request);

        $data = $this->platformService->update($id, $currentPlatform);

        return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_RECORD_UPDATED, $data);
    }

    /**
     * Remove the specified resource from storage..
     */
    public function destroy($id)
    {
        $this->utils->isNumericValidArgument($id);

        $platformDeleted = $this->platformService->delete($id);

        if ($platformDeleted) {

            return $this->utils->createResponse(Response::HTTP_OK, Constants::TXT_SUCCESS_CODE, Constants::TXT_RECORD_DELETED);
        }
    }
}
