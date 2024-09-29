<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class PlatformSerie extends Pivot
{
    use HasFactory;
    use SoftDeletes;

    /**
     * La tabla asociada al modelo
     * 
     * @var string
     */
    protected $table = "platform_series";
    protected $fillable = [
        'serie_id',
        'platform_id'
    ];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    // Relationship to Platform
    public function platform()
    {
        return $this->belongsTo(Platform::class, 'platform_id', 'id');
    }

    // Relationship to Serie
    public function serie()
    {
        return $this->belongsTo(Serie::class, 'serie_id', 'id');
    }
}
