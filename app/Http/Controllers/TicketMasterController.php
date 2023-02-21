<?php

namespace App\Http\Controllers;

use App\Actions\GenerateTicketMasterQuery;
use App\Actions\ParseTicketMasterQueryOutput;
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
}
