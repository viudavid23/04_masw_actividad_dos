<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;
use Illuminate\Database\Eloquent\SoftDeletes;

class ActorSerie extends Pivot
{
    use HasFactory;
    use SoftDeletes;

    /**
     * La tabla asociada al modelo
     * 
     * @var string
     */
    protected $table = "actor_series";
    protected $fillable = [
        'actor_id',
        'serie_id'
    ];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    // Relationship to Actor
    public function actor()
    {
        return $this->belongsTo(Actor::class, 'actor_id', 'id');
    }

    // Relationship to Serie
    public function serie()
    {
        return $this->belongsTo(Serie::class, 'serie_id', 'id');
    }
}
