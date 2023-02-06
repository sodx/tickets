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
        'medium_image',
        'video_ids',
        'slug',
    ];


    /**
     * Get attractions for the event.
     */
    public function events()
    {
        return $this->belongsToMany(Event::class, 'event_attractions', 'attraction_id', 'event_id');
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

    public function getYoutubeIframesFromVideoIds()
    {
        $videoIds = explode(',', $this->video_ids);
        $iframes = [];
        foreach ($videoIds as $videoId) {
            $iframes[] = '<iframe width="560" height="315" src="https://www.youtube.com/embed/' . $videoId . '" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';
        }
        return $iframes;
    }

    public function haveVideos()
    {
        return $this->video_ids !== '';
    }

    public function getYoutubeIds()
    {
        return explode(',', $this->video_ids);
    }

    public function upcomingEvents()
    {
        return $this->events()->where('start_date', '>=', date('Y-m-d'))->orderBy('start_date', 'asc');
    }
}
