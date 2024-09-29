<?php

namespace App\Http\Contracts;

interface LanguageSerieContract{

    public function getAll($page);

    public function getBySerieId($id);

    public function getByLanguageId($id);

    public function store($serieId, array $languages);

    public function update($serieId, array $languages);

    public function delete($serieId, array $languages);

    public function getDataResponse(array $languageSeries);

    public function getLanguageDataResponse(array $languageSeries);

    public function getSerieDataResponse(array $serieLanguage);
}