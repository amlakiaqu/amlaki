<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PostMedia extends Model
{
    /**
     * Table Columns:
     *  - id
     *  - media_url
     *  - type ['VIEDO', 'IMAGE']
     *  - post_id
     */

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

    protected $fillable = ["media_url", "type", "post_id"];

    # Relationships

    /**
     * Get the media post .
     */
    public function post(){return $this->belongsTo('App\Post');}

    /**
     * Get the post media url.
     *
     * @param  string  $value
     * @return string
     */
    public function getMediaUrlAttribute($value)
    {
        if(filter_var($value, FILTER_VALIDATE_URL) !== false){
            return $value;
        }else{
            return route('getFile', ["path" => \Crypt::encrypt($value)]);
        }
    }

}
