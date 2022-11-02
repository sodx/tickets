<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'venues';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'venue_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'ticketmaster_id',
        'locale',
        'postcode',
        'timezone',
        'city',
        'country',
        'country_code',
        'state',
        'state_code',
        'address',
        'longtitude',
        'latitude',
        'slug',
    ];


    /**
     * Get the events for the venue.
     */
    public function events()
    {
        return $this->hasMany('App\Models\Event');
    }
}
