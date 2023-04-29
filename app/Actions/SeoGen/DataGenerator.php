<?php

namespace App\Actions\SeoGen;

use App\Actions\ChatGPT\ChatGPTClient;
use App\Actions\Serpstat\SerpstatClient;
use GuzzleHttp\Client;
use Lorisleiva\Actions\Concerns\AsAction;

abstract class DataGenerator
{
    use AsAction;
    public $initialKeywords = [];
    public $flattenedKeywords = [];

    const LIMIT_FROM_SERPSTAT = 50;
    const KEY_LIMIT_FROM_CHATGPT = 5;


    abstract public function handle($attendee = '', $city = '', $venue = '', $date = '');

    protected function generate($attendee = '', $city = '', $venue = '', $date = '', $segment = '', $type = 'event')
    {
        $this->getKeywords($attendee, $city, $venue, $segment);
        if (empty($this->flattenedKeywords)) {
            $this->flattenedKeywords = $this->defaultKeywords($attendee, $city, $venue, $segment);
        }
        $keywords = implode(',', $this->flattenedKeywords);

        $chatGPT = new ChatGPTClient();
        $cleanedKeywords = $chatGPT->cleanKeywordsChat($keywords, $city, $type);
        if (str_contains($cleanedKeywords[0], 'null')) {
            $cleanedKeywords = $this->defaultKeywords($attendee, $city, $venue, $segment);
        }
        $cleanedKeywords = $this->prepareKeywordsForGeneration($cleanedKeywords);

        $text = $chatGPT->generateText($cleanedKeywords, $city, $venue, $date, $type, $segment);
        $text = json_decode($text, true);
        return [
            'keywords' => $cleanedKeywords,
            'data' => $text
        ];
    }

    protected function getKeywords($attendee = '', $city = '', $venue = '', $segment = '')
    {
        $initialKeywords = $this->prepareInitialKeywords($attendee, $city, $venue, $segment);
        $this->getKeywordsFromSerpstat($attendee, $city, $initialKeywords, $segment, $venue);
        if (empty($this->flattenedKeywords)) {
            $this->flattenedKeywords = $initialKeywords;
        }
    }
    protected function flattenKeywords($keywords)
    {
        $flattened = [];
        foreach ($keywords as $keyword) {
            $flattened[] = $keyword['keyword'];
        }
        return $flattened;
    }
    protected function getKeywordsFromSerpstat($keyword, $city = '', $additionalKeywords = [], $segment = '', $venue = '')
    {
        $this->initialKeywords = SerpstatClient::run($keyword, $city, $additionalKeywords);
        $this->flattenedKeywords = $this->flattenKeywords($this->initialKeywords);
        $this->flattenedKeywords = array_slice($this->flattenedKeywords, 0, self::LIMIT_FROM_SERPSTAT);
    }

    protected function prepareKeywordsForGeneration($keywords)
    {
        $keywords = array_slice($keywords, 0, self::KEY_LIMIT_FROM_CHATGPT);
        return implode(',', $keywords);
    }

    protected function prepareInitialKeywords($attendee, $city, $venue, $segment = '')
    {
        return [
            $attendee,
            $attendee . ' in ' . $city,
            $attendee . ' in ' . $venue
        ];
    }

    protected function defaultKeywords($attendee, $city, $venue, $segment = '')
    {
        return [
            $attendee . 'Buy Tickets, ',
            $attendee . ' Buy tickets in ' . $city . ', ',
            $attendee . ' in ' . $venue . ', ',
            $attendee . ' in ' . $city . ', ',
            'Buy tickets for ' . $attendee . ', '.
            'Buy tickets for ' . $attendee . ' in ' . $city . ', ',
            'Tickets ' . $attendee . ', ',
            'Concert ' . $attendee . ', ',
        ];
    }
}
