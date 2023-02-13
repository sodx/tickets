<?php

namespace App\Actions;

use App\Models\Event;
use Illuminate\Support\Facades\Http;
use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Class for parsing TicketMaster API output and saving it into Events model.
 *
 * @implements \Lorisleiva\Actions\Action<array<string, string>>
 */
class ParseTicketMasterQueryOutput
{
    use AsAction;

    private int $savedEvents = 0;
    private int $updatedEvents = 0;
    private int $eventsForSave = 20;
    private int $processedEvents = 0;

    public function handle(array $output)
    {
        $this->setEventsForSave(
            setting('ticketmaster.size')
            ?? env('TICKETMASTER_QUERY_SIZE')
            ?? 20
        );
        $this->iterateThroughOutput($output);
    }

    /**
     * Iterate through output from TicketMaster API and save events into DB.
     *
     * @param array $output
     * @return void
     */
    private function iterateThroughOutput(array $output): void
    {
        $events = $output['_embedded']['events'] ?? [];
        if (empty($events)) {
            return;
        }
        foreach ($events as $event) {
            $this->processEvent($event);
        }
        if (!$this->isEnoughEventsSaved()) {
            $this->iterateThroughOutput($this->getMoreEvents($output));
        }
    }



    /**
     * Get more events from TicketMaster API.
     * Using pagination to get more events.
     */
    private function getMoreEvents(array $output): array
    {
        $url = $output['_links']['next']['href'];
        $response = Http::get('https://app.ticketmaster.com' . $url . '&apikey=' . env('TICKETMASTER_API_KEY'));
        return json_decode($response, true);
    }

    /**
     * Check is Event exists in DB.
     *
     * @param string $ticketmasterID
     * @return bool
     */
    public function isEventExists(string $ticketmasterID): bool
    {
        return Event::where('ticketmaster_id', $ticketmasterID)->exists();
    }

    /**
     * Method to check if we already save enough events.
     *
     * @return boolean
     */
    public function isEnoughEventsSaved(): bool
    {
        return $this->savedEvents >= $this->eventsForSave;
    }

    /**
     * Get the date of last event update.
     *
     * @param string $ticketMasterID
     * @return string
     */
    public function getLastEventUpdateDate(string $ticketMasterID): string
    {
        return Event::where('ticketmaster_id', $ticketMasterID)->first()->updated_at;
    }

    /**
     * Check if event was updated during last 24 hours.
     *
     * @param string $ticketMasterID
     * @return boolean
     */
    public function isEventUpdatedDuringLast24Hours(string $ticketMasterID): bool
    {
        $lastUpdateDate = $this->getLastEventUpdateDate($ticketMasterID);
        $lastUpdateDate = strtotime($lastUpdateDate);
        $now = strtotime(date('Y-m-d H:i:s'));
        $diff = $now - $lastUpdateDate;
        return $diff < 86400;
    }

    /**
     * @param array $event
     * @return void
     */
    public function processEvent(array $event): void
    {
        if (!$this->isEventExists($event['id'])
            || ($this->isEventExists($event['id']) && !$this->isEventUpdatedDuringLast24Hours($event['id']))) {
            $this->saveEvent($event);
            $this->setSavedEvents($this->savedEvents + 1);
        }
        $this->setProcessedEvents($this->processedEvents + 1);
    }

    /**
     * @param array $event
     * @return void
     */
    public function updateEvent(array $event): void
    {
        $eventModel = Event::where('ticketmaster_id', $event['id'])->first();
        $eventModel->update($event);
    }

    /**
     * Save event to database.
     *
     * @param array $event
     * @return void
     */
    public function saveEvent(array $event)
    {
        SaveEvent::run($event);
    }

    /**
     * @param int $updatedEvents
     * @return void
     */
    private function setUpdatedEvents(int $updatedEvents): void
    {
        $this->updatedEvents = $updatedEvents;
    }

    /**
     * Save venue to database.
     *
     * @param array $venue
     * @return void
     */
    public function saveVenue(array $venue)
    {
        SaveVenue::run($venue);
    }

    /**
     * @param int $savedEvents
     * @return void
     */
    private function setSavedEvents(int $savedEvents): void
    {
        $this->savedEvents = $savedEvents;
    }

    /**
     * @param int $eventsForSave
     * @return void
     */
    private function setEventsForSave(int $eventsForSave): void
    {
        $this->eventsForSave = $eventsForSave;
    }

    /**
     * @param int $processedEvents
     * @return void
     */
    private function setProcessedEvents(int $processedEvents): void
    {
        $this->processedEvents = $processedEvents;
    }
}
