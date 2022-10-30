<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

/**
 * Class for parsing TicketMaster API output and saving it into Events model.
 *
 * @implements \Lorisleiva\Actions\Action<array<string, string>>
 */
class ParseTicketMasterQueryOutput
{
    use AsAction;

    public function handle(array $output)
    {
        $events = $output['_embedded']['events'];

        foreach ($events as $event) {
            $this->saveEvent($event);
        }
    }

    public function saveEvent(array $event)
    {
        $eventModel = new Event();
        $eventModel->name = $event['name'];
        $eventModel->url = $event['url'];
        $eventModel->save();
    }
}
