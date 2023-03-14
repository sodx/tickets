<?php

namespace App\Http\Controllers;

use App\Actions\GenerateTicketMasterQuery;
use App\Actions\ParseTicketMasterQueryOutput;
use App\Models\Event;
use Illuminate\Support\Facades\Http;

class TicketMasterController extends Controller
{
    /**
     * @var string $queryString
     */
    private $queryString;

    public function index()
    {
        $outputItem = $this->getEvents();
        $items = parseTicketMasterQueryOutput::run($outputItem, $this->queryString);
        return view('performs', [
            'saved' => $items['saved'],
            'updated' => $items['updated'],
            'processed' => $items['processed'],
        ]);
    }

    /**
     * Get events from TicketMaster API with guzzle client.
     *
     * @return array
     */
    public function getEvents(): array
    {
        $response = Http::get(
            'https://app.ticketmaster.com/discovery/v2/events.json?' . $this->getQueryString()
        );

        return json_decode($response->body(), true);
    }


    public function getEventInfo($id)
    {
        $response = Http::get(
            'https://app.ticketmaster.com/discovery/v2/events/' . $id . '.json?apikey=' . env('TICKETMASTER_API_KEY')
        );
        return json_decode($response->body(), true);
    }

    /**
     * Get query string for request to TicketMaster API.
     *
     * @return string
     */
    public function getQueryString(): string
    {
        $this->queryString = GenerateTicketMasterQuery::run();
        return $this->queryString;
    }

    public function removeOldInactiveEvents()
    {
        $events = Event::where('status', '!=', 'onsale')->get();
        $events->each(function ($event) {
            if ($event->start_date < now()->subDays(30)) {
                $event->delete();
            }
        });
    }

    public function checkEventsStatus()
    {
        $events = Event::where('status', 'onsale')->get();
        $events->each(function ($event) {
            if ($event->start_date < now()) {
                $event->status = 'inactive';
                return $event->save();
            }
            $parsedEvent = $this->getEventInfo($event->ticketmaster_id);
            if (!isset($parsedEvent['dates']['status']['code'])) {
                return $event->save();
            }
            $parsedStatus = $parsedEvent['dates']['status']['code'];
            if ($parsedStatus !== $event->status) {
                $event->status = $parsedStatus;
                return $event->save();
            }

            if ($event->price_min !== null
                && $parsedStatus === 'onsale'
                && $parsedEvent['priceRanges'][0]['min'] == '0.0') {
                $event->status = 'soldout';
                return $event->save();
            }
        });

        $this->removeOldInactiveEvents();

        \Artisan::call('route:clear');
        \Artisan::call('cache:clear');
    }

    /**
     * Change status for events that are inactive.
     *
     * @return void
     */
    public function changeStatusForInactiveEvents()
    {
        $events = Event::where('status', 'onsale')->get();
        foreach ($events as $event) {
            if ($event->date < now()) {
                $event->status = 'inactive';
                $event->save();
            }
        }
    }
}
