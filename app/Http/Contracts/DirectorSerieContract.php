<?php

namespace App\Http\Contracts;

interface DirectorSerieContract{

    public function getAll($page, $pageSize);

    public function getBySerieId($id);

    public function getByDirectorId($id);

    public function store($serieId, array $directorIds);

    public function update($serieId, array $directorIds);

    public function delete($serieId, array $directorIds);

    public function getDataResponse(array $directorSeries);

    public function getDirectorDataResponse(array $directorSeries);

    public function getSerieDataResponse(array $serieDirectors);
}