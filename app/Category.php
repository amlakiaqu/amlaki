<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "category";

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Table Columns:
     *  - id
     *  - name
     *  - code
     */

    # Relationships

    /**
     * Get the Category properties
     */
    public function properties(){return $this->belongsToMany('App\Property', 'category_property', 'category_id', 'property_id')->withPivot('required');}
}
