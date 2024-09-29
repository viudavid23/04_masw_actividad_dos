<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class LanguageSerie extends Pivot
{
    use HasFactory;
    use SoftDeletes;

    /**
     * La tabla asociada al modelo
     * 
     * @var string
     */
    protected $table = "language_series";
    protected $fillable = [
        'language_id',
        'serie_id',
        'audio',
        'subtitle'
    ];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    // Relationship to Language
    public function language()
    {
        return $this->belongsTo(Language::class, 'language_id', 'id');
    }

    // Relationship to Serie
    public function serie()
    {
        return $this->belongsTo(Serie::class, 'serie_id', 'id');
    }

}
