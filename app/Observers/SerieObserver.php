<?php

namespace App\Observers;

use App\Models\Serie;

class SerieObserver
{
    // Serie delete logic
    public function deleting(Serie $serie)
    {
        // Eliminar registros de las tablas intermedias
        $serie->platforms()->detach();
        $serie->actors()->detach();
        $serie->directors()->detach();
        $serie->languages()->detach();
    }

    // Serie restore logic
    public function restoring(Serie $serie)
    {
        // Restaurar registros de las tablas intermedias
        $serie->platforms()->withTrashed()->restore();
        $serie->actors()->withTrashed()->restore();
        $serie->directors()->withTrashed()->restore();
        $serie->languages()->withTrashed()->restore();
    }
}
