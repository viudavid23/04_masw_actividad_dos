<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Person extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * La tabla asociada al modelo
     * 
     * @var string
     */
    protected $table = "people";

    /**
     * Llave primaria asociada con la tabla
     * 
     * @var string
     */
    protected $primaryKey = "id";

    protected $fillable = [
        'id',
        'document_number',
        'first_name',
        'last_name',
        'birthdate',
        'country_id'
    ];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    /**
     * Función encargada de definir la relación de llave foránea con la tabla country
     * Argumentos: Modelo relacionado, columna que actua como llave foránea y columna de referencia en el modelo
     */
    public function country()
    {
        return $this->belongsTo(Country::class, 'country_id', 'id');
    }

    /**
     * Función encargada de definir la relación de llave foránea con la tabla actor
     * Argumentos: Modelo relacionado, columna que actua como llave foránea y columna de clave primaria
     */
    public function actor()
    {
        return $this->hasOne(Actor::class, 'people_id', 'id');
    }

    /**
     * Función encargada de definir la relación de llave foránea con la tabla director
     * Argumentos: Modelo relacionado, columna que actua como llave foránea y columna de clave primaria
     */
    public function director()
    {
        return $this->hasOne(Director::class, 'people_id', 'id');
    }
}
