<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class RequestProperty extends Model
{
    use CrudTrait;

     /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    /**
     * Table Columns:
     *  - id
     *  - property_id
     *  - request_id
     *  - value
     *
     */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "request_property";
    protected $primaryKey = 'id'; // Optional or for custom primary key column
    protected $guarded = ['id', 'request_id', 'property_id'];
    protected $fillable = ['value'];

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | FUNCTIONS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the request
     */
    public function request(){return $this->belongsTo('App\Request');}

    /**
     * Get the property
     */
    public function property(){return $this->belongsTo('App\Property');}

    /*
    |--------------------------------------------------------------------------
    | SCOPES
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | ACCESORS
    |--------------------------------------------------------------------------
    */

    /*
    |--------------------------------------------------------------------------
    | MUTATORS
    |--------------------------------------------------------------------------
    */
}
