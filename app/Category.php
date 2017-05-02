<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class Category extends Model
{
    use CrudTrait;
    /**
     * Table Columns:
     *  - id
     *  - name
     *  - code
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
    protected $table = "category";

    protected $fillable = ['name', 'code'];

    protected $guarded = ['id'];

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
     * Get the Category properties
     */
    public function properties(){return $this->belongsToMany('App\Property', 'category_property', 'category_id', 'property_id')->withPivot('required');}

    /**
     * Get the category posts
     */
    public function posts(){return $this->hasMany('App\Post');}

}
