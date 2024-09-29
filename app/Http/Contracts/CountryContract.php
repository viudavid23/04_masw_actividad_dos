<?php

namespace App\Http\Contracts;

use App\Models\Country;

interface CountryContract{

    public function getAll($page, $pageSize);

    public function getById($id);

    public function getDataResponse(Country $actor);
}