<?php

namespace App\Http\Contracts;

use App\Models\Platform;

interface PlatformContract{

    public function getAll($page, $pageSize);

    public function getById($id);

    public function store(array $platform);

    public function update($id, array $platform);

    public function delete($id);

    public function getDataResponse(Platform $platform);
}