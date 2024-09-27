<?php

namespace App\Http\Contracts;

use App\Models\Person;

interface PersonContract{

    public function getAll($page);

    public function getById($id);

    public function store(array $person);

    public function update($id, array $person);

    public function delete($id);

    public function getDataResponse(Person $person);
}