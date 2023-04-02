<?php

namespace App\Models;

use App\Actions\GetActiveCity;
use Cviebrock\EloquentSluggable\Sluggable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Segment extends Model
{
    use Sluggable;
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'segments';


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
        'seo_title',
        'seo_description',
        'seo_keywords',
        'seo_content'
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
        return $this->hasMany(Event::class, 'segment_id', 'id');
    }

    public function eventsInCity($city)
    {
        return $this->events()->whereHas('venue', function ($query) use ($city) {
            $query->where('city', $city);
        })->get();
    }

    public function upcomingEvents()
    {
        return $this->events()->where('start_date', '>=', date('Y-m-d'))->orderBy('start_date', 'asc');
    }

    public function filterEventByLocation($location = '')
    {
        if ($location !== 'all-cities' && $location !== '') {
            $events = $this->hasMany(Event::class, 'segment_id', 'id')
                ->where('start_date', '>=', date('Y-m-d'))->orderBy('start_date', 'asc')
                ->whereHas('venue', function ($query) use ($location) {
                    $query->where('city', $location);
                })->get();
        } else {
            $events = $this->upcomingEvents()->get();
        }

        return $this->groupTourEvents($events);
    }

    private function groupTourEvents($events)
    {
        $groups = $events->groupBy('tour_id');
        $tourGroup = [
            'single' => [],
            'tour' => []
        ];
        foreach ($groups as $group) {
            if (count($group) > 1) {
                $tourGroup['tour'][] = $group;
            } else {
                $tourGroup['single'][] = $group[0];
            }
        }

        return $tourGroup;
    }
}
