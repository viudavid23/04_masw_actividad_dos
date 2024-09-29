<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class DirectorSerie extends Pivot
{
    use HasFactory;
    use SoftDeletes;

    /**
     * La tabla asociada al modelo
     * 
     * @var string
     */
    protected $table = "director_series";
    protected $fillable = [
        'director_id',
        'serie_id'
    ];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    // Relationship to Director
    public function director()
    {
        return $this->belongsTo(Director::class, 'director_id', 'id');
    }

    // Relationship to Serie
    public function serie()
    {
        return $this->belongsTo(Serie::class, 'serie_id', 'id');
    }

}
