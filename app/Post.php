<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    /**
     * Table Columns:
     *  - id
     *  - title
     *  - image
     *  - verified_by
     *  - category_id
     *  - user_id
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
    protected $table = "post";
    protected $primaryKey = 'id'; // Optional or for custom primary key column
    protected $guarded = ["id"];
    protected $fillable = ['title'];
    protected $dates = ["created_at", "updated_at"];

    public $timestamps = true;

    /**
     * The attributes that should be casted to native types.
     *
     * @var array
     */
    protected $casts = ['created_at' => 'date',  'updated_at' => 'date'];

    /*
    |--------------------------------------------------------------------------
    | RELATIONS
    |--------------------------------------------------------------------------
    */

    /**
     * Get the post owner
     */
    public function user(){return $this->belongsTo('App\User');}

    /**
     * Get the post verifier
     */
    public function verifier(){return $this->belongsTo('App\User', 'verified_by');}

    /**
     * Get the post category
     */
    public function category(){return $this->belongsTo('App\Category');}

    /**
     * Get the Post properties
     */
    public function properties(){return $this->belongsToMany('App\Property', 'post_property', 'post_id', 'property_id')->withPivot('value');}

    /**
     * Get the post medias
     */
    public function medias(){return $this->hasMany('App\PostMedia');}

}
