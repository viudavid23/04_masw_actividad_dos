<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Serie extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * La tabla asociada al modelo
     * 
     * @var string
     */
    protected $table = "series";

    /**
     * Llave primaria asociada con la tabla
     * 
     * @var string
     */
    protected $primaryKey = "id";

    protected $fillable = [
        'id',
        'title',
        'synopsis',
        'release_date'
    ];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    // Many to Many realation with Platform
    public function platforms()
    {
        return $this->belongsToMany(Platform::class, 'platform_series', 'serie_id', 'platform_id','id','id')
                    ->using(PlatformSerie::class)
                    ->withPivot('deleted_at')
                    ->withTimestamps();
    }

    // Many to Many realation with Actor
    public function actors()
    {
        return $this->belongsToMany(Actor::class, 'actor_series', 'serie_id', 'actor_id','id','id')
                    ->using(ActorSerie::class)
                    ->withPivot('deleted_at')
                    ->withTimestamps();
    }
}
