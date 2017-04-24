<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    /**
     *
     * Table Columns:
     *  - id
     *  - title
     *  - code
     *  - value_type
     *  - extra_settings
     */

    /*
    |--------------------------------------------------------------------------
    | GLOBAL VARIABLES
    |--------------------------------------------------------------------------
    */

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "property";

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the Property categories
     */
    public function categories(){return $this->belongsToMany('App\Category', 'category_property', 'property_id', 'category_id')->withPivot('required');}

}
