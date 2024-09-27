<?php

namespace App\Http\Contracts;

use App\Models\Director;

interface DirectorContract{

    public function getAll($page);

    public function getById($id);

    public function store(array $newDirector, array $newPerson);

    public function update($id, array $currentDirector, array $currentPerson);

    public function delete($id);

    public function getDataResponse(Director $actor);

    public function makeDataResponse(array $elementsUpdated);

    public function getDirectorCountry($countryId);
}