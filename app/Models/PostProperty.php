<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Backpack\CRUD\CrudTrait;

class PostProperty extends Model
{
    use CrudTrait;
    /**
     * Table Columns:
     *  - id
     *  - property_id
     *  - post_id
     *  - value
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
    protected $table = "post_property";
    protected $primaryKey = 'id'; // Optional or for custom primary key column
    protected $guarded = ['id'];
    protected $fillable = ['value'];

    public $timestamps = false;

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the post
     */
    public function post(){return $this->belongsTo('App\Post');}

    /**
     * Get the property
     */
    public function property(){return $this->belongsTo('App\Property');}

}
