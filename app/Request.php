<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
    /**
     * Table Columns:
     *  - id
     *  - user_id
     *  - category_id
     *  - created_at
     *  - updated_at
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
    protected $table = "request";

    protected $fillable = ["user_id", "category_id", "created_at", "updated_at"];

    protected $guarded = ["id"];

    public $timestamps = true;


    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the request owner .
     */
    public function user()
    {
        return $this->belongsTo('App\User');
    }

    /**
     * Get the request category .
     */
    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    /**
     * Get the Request properties
     */
    public function properties(){return $this->belongsToMany('App\Property', 'request_property', 'request_id', 'property_id')->withPivot('value');}

}
