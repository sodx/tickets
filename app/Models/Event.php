<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
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
        'start_time' => 'time',
        'end_time' => 'time',
        'status' => 'boolean',
    ];


    /**
     * Get the venue that owns the event.
     */
    public function venue()
    {
        return $this->belongsTo('App\Models\Venue');
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

    /**
     * @var bool $timestamps
     */
    public $timestamps = true;
}
