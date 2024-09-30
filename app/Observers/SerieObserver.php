<?php

namespace App\Observers;

use App\Models\Serie;

class SerieObserver
{
    // Serie delete logic
    public function deleting(Serie $serie)
    {
        // Eliminar registros de las tablas intermedias
        if ($serie->platforms()->exists()) {
            $serie->platforms()->detach();
        }
        if ($serie->actors()->exists()) {
            $serie->actors()->detach();
        }
        if ($serie->directors()->exists()) {
            $serie->directors()->detach();
        }
        if ($serie->languages()->exists()) {
            $serie->languages()->detach();
        }
    }

    // Serie restore logic
    public function restoring(Serie $serie)
    {
        // Restaurar registros de las tablas intermedias
        $serie->platforms()->withTrashed()->get()->each(function ($platform) {
            $platform->pivot->restore();
        });

        $serie->actors()->withTrashed()->get()->each(function ($actor) {
            $actor->pivot->restore();
        });

        $serie->directors()->withTrashed()->get()->each(function ($director) {
            $director->pivot->restore();
        });

        $serie->languages()->withTrashed()->get()->each(function ($language) {
            $language->pivot->restore();
        });
    }
}
