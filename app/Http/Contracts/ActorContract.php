<?php

namespace App\Http\Contracts;

use App\Models\Actor;

interface ActorContract{

    public function getAll($page, $pageSize);

    public function getById($id);

    public function store(array $newActor, array $newPerson);

    public function update($id, array $currentActor, array $currentPerson);

    public function delete($id);

    public function getDataResponse(Actor $actor);

    public function makeDataResponse(array $elementsUpdated);

    public function getActorCountry($countryId);
}