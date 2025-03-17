<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Curso extends Model
{
    use CrudTrait;
    use HasFactory;

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    protected $table = 'cursos';
     protected $primaryKey = 'id';
     public $timestamps = false;
    protected $guarded = ['id'];
    // protected $fillable = [];
    // protected $hidden = [];

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */
    public function getCursoCompletoAttribute()
    {
        return $this->titulo . ' ($' . $this->precio . ', $' . $this->precionosocio . ')'; 
    }

    public function getPrecioCompletoAttribute($value)
    {
        // Formatea el valor con puntos (.) como separador de miles
        return number_format($value, 0, '.', '.');
    }
    public function getPrecioNoSocioCompletoAttribute($value)
    {
        // Formatea el valor con puntos (.) como separador de miles
        return number_format($value, 0, '.', '.');
    }
    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    public function personas(){
        return $this->belongsToMany(Persona::class);

    }
    public function personas_externas(){
        return $this->belongsToMany(PersonaExterna::class,'curso_personas_externas');

    }
    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESSORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
