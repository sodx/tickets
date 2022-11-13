<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Attraction extends Model
{
    use HasFactory;
    use Sluggable;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'attractions';

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'attraction_id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'ticketmaster_id',
        'locale',
        'type',
        'youtube_link',
        'twitter',
        'itunes',
        'lastfm',
        'wiki',
        'facebook',
        'homepage',
        'instagram',
        'thumbnail',
        'poster',
        'slug',
    ];

    /**
     * Get the events for the venue.
     */
    public function events()
    {
        return $this->hasMany('App\Models\Event');
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
