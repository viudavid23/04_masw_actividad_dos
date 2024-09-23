<?php

namespace App\Http\Contracts;

use App\Models\Language;

interface LanguageContract{

    public function getAll($page);

    public function getById($id);

    public function store(array $language);

    public function update($id, array $language);

    public function delete($id);

    public function getDataResponse(Language $language);
}