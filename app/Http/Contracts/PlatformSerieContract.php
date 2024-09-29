<?php

namespace App\Http\Contracts;

interface PlatformSerieContract{

    public function getAll($page);

    public function getBySerieId($id);

    public function getByPlatformId($id);

    public function store($serieId, array $platformIds);

    public function update($serieId, array $platformIds);

    public function delete($serieId, array $platformIds);

    public function getDataResponse(array $platformSeries);

    public function getPlatformDataResponse(array $platformSeries);

    public function getSerieDataResponse(array $seriePlatforms);
}