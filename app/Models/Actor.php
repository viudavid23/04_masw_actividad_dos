<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Actor extends Model
{
    use HasFactory;
    use SoftDeletes;

    /**
     * La tabla asociada al modelo
     * 
     * @var string
     */
    protected $table = "actors";

    /**
     * Llave primaria asociada con la tabla
     * 
     * @var string
     */
    protected $primaryKey = "id";

    protected $fillable = [
        'id',
        'stage_name',
        'biography',
        'awards',
        'height',
        'people_id'
    ];

    public $timestamps = true;

    protected $dates = ['deleted_at'];

    /**
     * Función encargada de definir la relación de llave foránea con la tabla people
     * Argumentos: Modelo relacionado, columna que actua como llave foránea y columna de clave primaria
     */
    public function person(){

        return $this->belongsTo(Person::class, 'people_id', 'id');
    }
}
