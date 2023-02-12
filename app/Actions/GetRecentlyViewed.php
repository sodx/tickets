<?php

namespace App\Actions;

use App\Http\Controllers\EventController;
use App\Models\Event;
use Lorisleiva\Actions\Concerns\AsAction;

class GetRecentlyViewed
{
    use AsAction;

    public function handle()
    {
        $recentlyViewed = request()->cookie('recently_viewed');
        $recentlyViewed = json_decode($recentlyViewed, true);
        $recentlyViewed = $recentlyViewed ? Event::whereIn('event_id', $recentlyViewed)->get() : [];
        if ($recentlyViewed) {
            $recentlyViewed = $recentlyViewed->filter(function ($event) {
                return $event->start_date >= date('Y-m-d');
            });
        }
        return $recentlyViewed;
    }
}
