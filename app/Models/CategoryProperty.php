<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class CategoryProperty extends Model
{
    use CrudTrait;
    /**
     * Table Columns:
     *  - id
     *  - property_id
     *  - category_id
     *  - required
     *
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
    protected $table = "category_property";
    protected $primaryKey = 'id'; // Optional or for custom primary key column
    protected $guarded = ['id', 'category_id', 'property_id'];
    protected $fillable = ['required'];

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the category
     */
    public function category(){return $this->belongsTo('App\Category');}

    /**
     * Get the property
     */
    public function property(){return $this->belongsTo('App\Property');}

}
