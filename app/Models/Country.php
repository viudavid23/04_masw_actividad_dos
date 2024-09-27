<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Country extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * La tabla asociada al modelo
     * 
     * @var string
     */
    protected $table = "countries";

    /**
     * Llave primaria asociada con la tabla
     * 
     * @var string
     */
    protected $primaryKey = "id";

    protected $fillable = [
        'id',
        'name',
        'demonym'
    ];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

     /**
     * Función encargada de definir la relación de llave foránea con la tabla people
     * Argumentos: Modelo relacionado, columna que actua como llave foránea y columna de clave primaria
     */
    public function person(){

        return $this->hasOne(Person::class, 'country_id', 'id');
    }
}
