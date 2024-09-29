<?php

namespace App\Http\Contracts;

use App\Models\Serie;

interface SerieContract{

    public function getAll($page, $pageSize);

    public function getById($id);

    public function store(array $newSerie);

    public function update($id, array $currentSerie);

    public function delete($id);

    public function getDataResponse(Serie $serie);
}