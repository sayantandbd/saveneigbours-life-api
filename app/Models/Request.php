<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
class Request extends Model
{
    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'user_id','lat','lng','request_type','body','address'
    ];

    public function scopeIsWithinMaxDistance($query, $coordinates, $radius = 5) {
        
        $haversine = "(6371 * acos(cos(radians(" . $coordinates['lat'] . ")) 
                        * cos(radians(`lat`)) 
                        * cos(radians(`lng`) 
                        - radians(" . $coordinates['lng'] . ")) 
                        + sin(radians(" . $coordinates['lat'] . ")) 
                        * sin(radians(`lat`))))";
    
        return $query->select('id', 'lat', 'lng','request_type','body','created_at')
                     ->selectRaw("{$haversine} AS distance")
                     ->whereRaw("{$haversine} < ?", [$radius]);
    }

    public function user()
    {
        return $this->belongsTo('App\Models\User');
    }
}

