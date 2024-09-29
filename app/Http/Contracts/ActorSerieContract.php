<?php

namespace App\Http\Contracts;

interface ActorSerieContract{

    public function getAll($page, $pageSize);

    public function getBySerieId($id);

    public function getByActorId($id);

    public function store($serieId, array $actorIds);

    public function update($serieId, array $actorIds);

    public function delete($serieId, array $actorIds);

    public function getDataResponse(array $actorSeries);

    public function getActorDataResponse(array $actorSeries);

    public function getSerieDataResponse(array $serieActors);
}