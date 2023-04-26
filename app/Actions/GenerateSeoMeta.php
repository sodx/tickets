<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

class GenerateSeoMeta
{
    use AsAction;

    public $title;

    public $category;

    public $description;

    public $info;

    public $keywords;

    private $event;

    public function handle($event)
    {
        $this->event = $event;
        $this->category = $this->getEventSegment($event);
        //$this->keywords = $this->generateKeywords($event);
        $this->title = $this->generateTitle($event);
        $this->description = $this->generateDescription();
        $this->info = $this->generateInfo();

        return ['title' => $this->title, 'description' => $this->description, 'info' => $this->info, 'keywords' => $this->keywords];
    }

    private function getTitleSamplesArray()
    {
        return [
            "single_event" => [
                "music" => [
                    "{name} in {city} Concert on {start_date} 🎫 Buy Tickets!",
                    "{name} in {city}: Get Tickets for {start_date} Concert at {venue->name}",
                    "Unmissable Concert Alert: {name} in {city} on {start_date} 🎫 Buy Tickets Now",
                    "{name} in {city} Concert on {start_date} 🎫 Secure Seat Today",
                    "{name} in {city}, {state} on {start_date} 🎫 Buy Tickets",
                    "{start_date} Concert in {city}, {state}: {name} 🎫 Buy Tickets Now",
                    "{city}, {state} Concertgoers: {name} Live on {start_date} 🎫 Get Tickets",
                    "{name} Live in {city}, {state} on {start_date} 🎫 Tickets Online",
                    "{name}: A Concert on {start_date} in {city} 🎫 Buy Tickets",
                    "{city} Event: {name} on {start_date} 🎫 Buy Tickets"
                ],
                "sports" => [
                    "Buy Tickets to See the {name} Live in {city}, {state}",
                    "Buy Tickets for {name}, {city}",
                    "Buy Tickets for the {name} Game in {city}, {state}",
                    "Get Your Tickets for the Game: {name} in {city}, {state}",
                    "Experience with {name} in {city}, {state}",
                    "Buy Tickets for the {name} Game in {city}, {state}",
                    "Be there live: {name} in {city}, {state}",
                    "{name} Game at {venue->name} in {city}, Buy Tickets",
                    "Buy Tickets for {name} in {city}, {state}",
                    "{name} live, Buy your tickets now!"
                ],
                "other" => [
                    "Buy Tickets for {name} in {city} 🎫 {start_date}",
                    "The Event of {name} in {city}, {state} 🎫 Get Tickets Now!",
                    "Don't Miss {city} {name} 🎫 {start_date}",
                    "The {name} in {city}, {state} 🎫 Buy Tickets Now",
                    "{city} {name} 🎫 {start_date}",
                    "Join the {name} in {city}, {state} 🎫 Get Your Tickets",
                    "The {name} Live in {city}, {state} 🎫 {start_date}",
                    "{city} {name} 🎫 Get Your Tickets Now",
                    "The {name} Event in {city}, {state} 🎫 {start_date}",
                    "Join the {name} in {city}, {state} 🎫 {start_date}"
                ],
            ],
        ];
    }

    private function getSeoDescriptionSamplesArray()
    {
        return [
            "single_event" => [
                "music" => [
                    "Don't miss out on the concert {name} in {city}, {state} on {start_date}. Buy your tickets and enjoy for live on stage.",
                    "Get ready for night of music with {name} at the {venue->name} in {city}, {state} on {start_date}. Buy your tickets now and be a part of this event.",
                    "Experience the magic of {name} live in {city}, {state} on {start_date}. Don't wait, buy your tickets today!",
                    "Join the crowd and be a part of {name} in {city}, {state} on {start_date}. Buy your tickets now and witness the legendary live on stage!",
                    "Don't miss the chance to see live in {city}, {state} on {start_date}. {name} will be performing at the {venue->name}. Don't wait, buy your tickets today!",
                    "Get your tickets now for {name} in {city}, {state} on {start_date}. Be prepared to be amazed by high-energy performance live on stage",
                    "Buy your tickets now for the concert in {city}, {state} on {start_date}. {name} will be performing live at the {venue->name}!",
                    "{name} live on stage in {city}, {state} on {start_date}. Buy your tickets now and be a part of this once-in-a-lifetime concert event",
                    "Ultimate concert experience with {name} in {city}, {state} on {start_date}. Don't wait, buy your tickets today!",
                    "{name} live in {city}, {state} on {start_date}. Buy your tickets now and be a part of this unforgettable concert event"
                ],
                "sports" => [
                    "Don't miss the excitement at the {name} event in {city}, {state} on {start_date}!",
                    "{start_date} - Get ready for an unforgettable evening of sports at {name} in {city}, {state}!",
                    "Join the excitement of {name} in {city}, {state} on {start_date}!",
                    "{name} brings the thrill of the game to {city}, {state} on {start_date}!",
                    "Experience the passion of {name} in {city}, {state} on {start_date}!",
                    "{start_date} - {name} in {city}, {state} - an event not to be missed!",
                    "Unforgettable evening of high-stakes sports at {name} in {city}, {state} on {start_date}!",
                    "Get ready for an intense match at {name} in {city}, {state} on {start_date}!",
                    "{start_date} - {name} in {city}, {state} - the excitement of the game!",
                    "Join the passion and excitement of {name} in {city}, {state} on {start_date}!"
                ],
                "other" => [
                    "Join us for the {name} event in {city}, {state} on {start_date}! Don't miss out, buy tickets now.",
                    "{start_date} - Experience the thrill of {name} in {city}, {state}. Buy your tickets today!",
                    "Get ready for an unforgettable evening at the {name} event in {city}, {state} on {start_date}. Buy now!",
                    "Don't miss the opportunity to join us at the {name} event in {city}, {state} on {start_date}. Buy tickets now!",
                    "Join the excitement of {name} in {city}, {state} on {start_date}. Get your tickets now!",
                    "{start_date} - Be a part of the thrill of {name} in {city}, {state}. Buy tickets today!",
                    "{name} in {city}, {state} on {start_date} - don't miss out, buy your tickets now!",
                    "An unforgettable evening awaits you at {name} in {city}, {state} on {start_date}. Buy tickets now!",
                    "Join the fun and excitement of {name} in {city}, {state} on {start_date}. Get your tickets today!",
                    "Get ready for the {name} event in {city}, {state} on {start_date}. Buy your tickets now and experience the thrill!"
                ],
            ]
        ];
    }

    private function getDescriptionSamplesArray()
    {
        return [
            "single_event" => [
                "music" => [
                    "Don't miss out on the ultimate concert experience with {name} in {city}, {state} on {start_date}. Buy your tickets now and enjoy the high-energy live on stage. Don't wait, secure your seat today!",
                    "Get ready for an unforgettable night of music with {name} at the {venue->name} in {city}, {state} on {start_date}. Buy your tickets now and be a part of this once-in-a-lifetime concert event. Don't miss out, buy your tickets today!",
                    "Experience the magic of {name} live in {city}, {state} on {start_date}. Buy your tickets now and enjoy an evening of iconic music and high-energy performance. Don't wait, buy your tickets today!",
                    "Join the crowd and be a part of the ultimate concert experience with {name} in {city}, {state} on {start_date}. Buy your tickets now and witness the legendary talent of {name} live on stage. Don't miss out, buy your tickets today!",
                    "Don't miss the chance to see {name} live on stage in {city}, {state} on {start_date}. Will be performing at the {venue->name} and you can buy your tickets now to experience their iconic music live. Don't wait, buy your tickets today!",
                    "Get your tickets now for the ultimate concert experience with {name} in {city}, {state} on {start_date}. Don't miss out, buy your tickets today!",
                    "Buy your tickets now for the concert event of the year in {city}, {state} on {start_date}. {name} will be performing live at the {venue->name} and you don't want to miss out on this unforgettable night of music. Don't wait, buy your tickets today!",
                    "Join the crowd and witness the legendary {name} live on stage in {city}, {state} on {start_date}. Buy your tickets now and be a part of this once-in-a-lifetime concert event. Don't miss out, buy your tickets today!",
                    "Don't miss out on the ultimate concert experience with {name} in {city}, {state} on {start_date}. Buy your tickets now and enjoy an evening of iconic music and high-energy performance live on stage. Don't wait, buy your tickets today!",
                    "Experience the magic of {name} live in {city}, {state} on {start_date}. Buy your tickets now and be a part of this unforgettable concert event. Don't miss this opportunity to see them live, buy your tickets today!"
                ],
                "sports" => [
                    "Get ready for a night of non-stop action as the {name} at the {venue->name}. Don't miss your chance to see these battle. Get your tickets now!",
                    "Experience the thrill of game as the {name} at the {venue->name}. With fast-paced action and high energy, this game is sure to be a hit. Get your tickets now!",
                    "Join the crowd at the {venue->name} as the {name} face off in an intense game. With a packed crowd and an exciting atmosphere, this is an event you won't want to miss. Get your tickets now!",
                    "Be a part of the action as the {name} at the {venue->name}. With high-stakes and high-energy, this game is sure to be a thrilling experience. Get your tickets now!",
                    "Don't miss the chance to see the {name} go head-to-head on the ice at the {venue->name}. With fast-paced action and a packed crowd, this is an event you won't want to miss. Get your tickets now!",
                    "See the {name} battle it out on the ice in a thrilling hockey game at the {venue->name}. With a great atmosphere and exciting action, this is an event you won't want to miss. Get your tickets now!",
                    "Join the crowd and get ready for a night of intense hockey action as the {name} at the {venue->name}. With high-stakes and high-energy, this game is sure to be a hit. Get your tickets now!",
                    "Experience the thrill of live hockey as the Atlanta Gladiators face off against the Savannah Ghost Pirates at the {venue->name}. With fast-paced action and high energy, this game is sure to be a hit. Get your tickets now!",
                    "Be a part of the action as the {name} at the {venue->name}. With a packed crowd and an exciting atmosphere, this is an event you won't want to miss. Get your tickets now!",
                    "Join the crowd at the {venue->name} and see the {name} face off in an intense game. With high-stakes and high-energy, this game is sure to be a thrilling experience. Get your tickets now!"
                ],
                "other" => [
                    "Join us for an action {name} are performed live in {city}, {state} at An East {city} Tribute on {start_date}.",
                    "Don't miss the chance to see The {name} come to life at {city} on {start_date} in {city}, {state}.",
                    "Get ready for an evening of unforgettable performances as {city} {name} takes place in {city}, {state} on {start_date}.",
                    "Join the celebration of the iconic music of {name} at An East {city} Tribute on {start_date} in {city}, {state}.",
                    "Experience the magic of {name} live in {city}, {state} at {city} Tribute on {start_date}.",
                    "{city} brings the timeless action of {name} to {city}, {state} on {start_date}.",
                    "Get ready for a night for performances as {city} {name} takes the stage in {city}, {state} on {start_date}.",
                    "{city} {name} promises to be an unforgettable evening from {name} are performed live in {city}, {state} on {start_date}.",
                    "Join the celebration of {name} at {city} on {start_date} in {city}, {state}. Get ready for a unforgettable performances."
                ]
            ],
        ];
    }


    public function getTitle()
    {
        return $this->title;
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function generateTitle($event): string
    {
        $titles = $this->getTitleSamplesArray();
        $title = $this->getRandomTitle($titles);
        return $this->replaceVariables($title, $this->event);
    }


    public function generateDescription(): string
    {
        $descriptions = $this->getSeoDescriptionSamplesArray();
        $description = $this->getRandomTitle($descriptions);
        return $this->replaceVariables($description, $this->event);
    }

    public function generateInfo(): string
    {
        $descriptions = $this->getDescriptionSamplesArray();
        $description = $this->getRandomTitle($descriptions);
        return $this->replaceVariables($description, $this->event);
    }

    //function to parse variable name from curly braces in string
    private function parseVariableName($string)
    {
        $matches = [];
        preg_match_all('/\{([^\}]+)\}/', $string, $matches);
        return $matches[1];
    }

    //function to get random title from array
    private function getRandomTitle($titles)
    {
        return $titles['single_event'][$this->category][array_rand($titles['single_event'][$this->category])];
    }



    //function to get current event segment
    private function getEventSegment($event)
    {
        if(!isset($event['classifications'][0]['segment']['name'])) {
            return 'other';
        }
        $segment = $event['classifications'][0]['segment']['name'];
        if ($segment === 'Sports' || $segment === 'Music') {
            return strtolower($segment);
        } else {
            return 'other';
        }
    }

    //function to replace variables in string with values from event array
    private function replaceVariables($string, $event)
    {
        $variables = $this->parseVariableName($string);

        foreach ($variables as $variable) {
            if ($variable === 'start_date') {
                $eventVar = $event['dates']['start']['localDate'];
            } elseif ($variable === 'venue->name') {
                $eventVar = $event['_embedded']['venues'][0]['name'];
            } elseif ($variable === 'state') {
                $eventVar = $event['_embedded']['venues'][0]['state']['stateCode'];
            } elseif ($variable === 'city') {
                $eventVar = $event['_embedded']['venues'][0]['city']['name'];
            } else {
                $eventVar = $event[$variable];
            }

            $string = str_replace('{' . $variable . '}', $eventVar, $string);
        }
        return $string;
    }
}
