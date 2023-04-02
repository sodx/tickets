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
        'seo_title',
        'seo_description',
        'seo_keywords',
        'seo_content'
    ];


    /**
     * Get the events for the venue.
     */
    public function events()
    {
        return $this->hasMany(Event::class, 'venue_id', 'venue_id');
    }

    public function upcomingEvents()
    {
        return $this->events()->where('start_date', '>', date('Y-m-d'));
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

    public function googleMap()
    {
        ob_start();
        ?>
        <iframe src="https://www.google.com/maps/embed?pb=!1m14!1m12!1m3!1d2506.2296986012298!2d<?php echo $this->longtitude; ?>!3d<?php echo $this->latitude; ?>!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!5e0!3m2!1sru!2spl!4v1670191905869!5m2!1sru!2spl" width="600" height="350" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        <?php

        return ob_get_clean();
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
