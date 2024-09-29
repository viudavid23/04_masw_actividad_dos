<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Platform extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * La tabla asociada al modelo
     * 
     * @var string
     */
    protected $table = "platforms";

    /**
     * Llave primaria asociada con la tabla
     * 
     * @var string
     */
    protected $primaryKey = "id";

    protected $fillable = [
        'id',
        'name',
        'description',
        'release_date',
        'logo'
    ];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    // Many to Many realation with series
    public function series()
    {
        return $this->belongsToMany(Serie::class, 'platform_series', 'platform_id', 'serie_id','id','id')
                    ->using(PlatformSerie::class)
                    ->withPivot('deleted_at')
                    ->withTimestamps();
    }

}
