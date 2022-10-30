<?php

namespace App\Http\Controllers;

use App\Actions\GenerateTicketMasterQuery;
use Illuminate\Support\Facades\Http;

class TicketMasterController extends Controller
{
    public function index()
    {

        $output = $this->getEvents();

        return view('test', $output);
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

    /**
     * Get query string for request to TicketMaster API.
     *
     * @return string
     */
    public function getQueryString(): string
    {
        return GenerateTicketMasterQuery::run();
    }
}
