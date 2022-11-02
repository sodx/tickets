<?php

namespace App\Actions;

use App\Models\Event;
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

    private function setUpdatedEvents(int $updatedEvents): void
    {
        $this->updatedEvents = $updatedEvents;
    }

    private function setSavedEvents(int $savedEvents): void
    {
        $this->savedEvents = $savedEvents;
    }

    private function setEventsForSave(int $eventsForSave): void
    {
        $this->eventsForSave = $eventsForSave;
    }

    private function setProcessedEvents(int $processedEvents): void
    {
        $this->processedEvents = $processedEvents;
    }

    /**
     * Iterate through output from TicketMaster API and save events into DB.
     *
     * @param array $output
     * @return void
     */
    private function iterateThroughOutput(array $output): void
    {
        $events = $output['_embedded']['events'];
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
        $page = $output['page']['number'] + 1;
        $size = $output['page']['size'];
        $url = $output['page']['href'];
        $url = str_replace('page=0', "page=$page", $url);
        $url = str_replace('size=20', "size=$size", $url);
        $output = json_decode(file_get_contents($url), true);
        return $output;
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
     * @param int $eventID
     * @return string
     */
    public function getLastEventUpdateDate(int $eventID): string
    {
        return Event::where('id', $eventID)->first()->updated_at;
    }

    /**
     * Check if event was updated during last 24 hours.
     *
     * @param string $lastUpdateDate
     * @return boolean
     */
    public function isEventUpdatedDuringLast24Hours(int $eventID): bool
    {
        $lastUpdateDate = $this->getLastEventUpdateDate($eventID);
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
        if ($this->isEventExists($event['ticketmaster_id'])
            || ! $this->isEventUpdatedDuringLast24Hours($event['id'])) {
            $this->updateEvent($event);
            $this->setUpdatedEvents($this->updatedEvents + 1);
        } else {
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
        $eventModel = Event::where('ticketmaster_id', $event['ticketmaster_id'])->first();
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
        $event = Event::updateOrCreate(
            ['ticketmaster_id' => $event['id']],
            [
                'name' => $event['name'],
            ]
        );
        //ray($event);
    }
}
