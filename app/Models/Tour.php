<?php

namespace App\Models;

use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tour extends Model
{
    use Sluggable;
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'tours';


    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = 'id';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'slug',
    ];

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

    public function events()
    {
        return $this->hasMany(Event::class, 'tour_id', 'id');
    }

    public function upcomingEvents()
    {
        return $this->events()->where('start_date', '>=', date('Y-m-d'))->orderBy('start_date', 'asc');
    }

    public function filterEventByLocation($location = '')
    {
        if ($location !== 'All Cities' && $location !== '') {
            return $this->hasMany(Event::class, 'segment_id', 'id')
                ->where('start_date', '>=', date('Y-m-d'))->orderBy('start_date', 'asc')
                ->whereHas('venue', function ($query) use ($location) {
                    $query->where('city', $location);
                })->get();
        } else {
            return $this->upcomingEvents();
        }
    }
}
