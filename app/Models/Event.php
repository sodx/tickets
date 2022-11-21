<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use Sluggable;
    use HasFactory;


    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'events';


    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'event_id';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'end_time',
        'start_time',
        'ticketmaster_id',
        'seatmap',
        'info',
        'pleaseNote',
        'thumbnail',
        'poster',
        'images',
        'price_min',
        'price_max',
        'price_currency',
        'url',
        'status',
        'slug',
        'venue_id',
        'segment_id',
        'genre_id',
        'subgenre_id',
        'meta_title',
        'meta_keywords',
        'meta_description',
        'clicks',
        'views',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'created_at',
        'updated_at',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'start_time' => 'datetime:H:i',
        'end_time' => 'datetime:H:i',
        'status' => 'boolean',
    ];


    /**
     * Get attractions for the event.
     */
    public function attractions()
    {
        return $this->belongsToMany(Attraction::class, 'event_attractions', 'event_id', 'attraction_id');
    }

    /**
     * Get the venue that owns the event.
     */
    public function venue()
    {
        return $this->belongsTo('App\Models\Venue', 'venue_id', 'venue_id');
    }


    /**
     * Get the segment that owns the event.
     */
    public function segment()
    {
        return $this->belongsTo('App\Models\Segment');
    }


    /**
     * Get the genre that owns the event.
     */
    public function genre()
    {
        return $this->belongsTo('App\Models\Genre');
    }


    /**
     * Get the subgenre that owns the event.
     */
    public function subgenre()
    {
        return $this->belongsTo('App\Models\Subgenre');
    }

    public function getFormattedDateAttribute()
    {
        return $this->start_date->format('Y-m-d');
    }

    public function getFormattedDateTimeAttribute()
    {
        return $this->start_date->format('M d') . ' ' . $this->start_time->format('H:i');
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
