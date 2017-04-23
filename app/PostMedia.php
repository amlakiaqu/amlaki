<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostMedia extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = "post_media";

    /**
     * Indicates if the model should be timestamped.
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * Table Columns:
     *  - id
     *  - media_url
     *  - type ['VIEDO', 'IMAGE']
     *  - post_id
     */

    # Relationships

    /**
     * Get the media post .
     */
    public function post(){return $this->belongsTo('App\Post');}

}
