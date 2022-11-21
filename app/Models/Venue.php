<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Venue extends Model
{
    use HasFactory;
    use Sluggable;


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
        'image',
    ];


    /**
     * Get the events for the venue.
     */
    public function events()
    {
        return $this->hasMany('App\Models\Event');
    }

    public function slugifyString($string)
    {
        return str_replace(' ', '-', strtolower($string));
    }

    public function unslugifyString($string)
    {
        return str_replace('-', ' ', ucfirst($string));
    }

    public function slugifyCity()
    {
        return $this->slugifyString($this->city);
    }

    /**
     * @var bool $timestamps
     */
    public $timestamps = true;

    public function sluggable(): array
    {
        return [
            'slug' => [
                'source' => 'name'
            ]
        ];
    }
}
