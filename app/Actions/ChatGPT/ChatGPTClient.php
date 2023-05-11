<?php

namespace App\Actions\ChatGPT;

use Lorisleiva\Actions\Concerns\AsAction;
use Orhanerday\OpenAi\OpenAi;

class ChatGPTClient
{
    use AsAction;

    private $client;

    public function __construct()
    {
        $open_ai_key = env('CHATGPT_API_KEY');
        $this->client = new OpenAi($open_ai_key);
    }

    public function handle()
    {
        $open_ai_key = env('CHATGPT_API_KEY');
        $this->client = new OpenAi($open_ai_key);

        return [];
    }

    public function cleanKeywordsChat($keywords, $city, $type = 'event')
    {
        $complete = $this->client->chat([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    "role" => "system",
                    "content" => "You are a SEO specialist."
                ],
                [
                    "role" => "user",
                    "content" => "Check this list of keywords keep keywords related to the concerts, and events you are promoting.
                        And remove keywords that contains cities or states or any unrelated information.
                        Keywords: justin bieber tickets, justin bieber ticket, justin bieber concert in nyc,
                        justin bieber buy ticket, justin bieber concert in new york, justin bieber in austin,
                        justin bieber live in austin, cheap tickets for justin bieber, justin bieber on tumblr,
                        justin bieber tickets arizona, justin bieber concerts near me, justin bieber in Paris, justin bieber in London"
                ],
                [
                    "role" => "system",
                    "content" => "
                        justin bieber tickets,
                        justin bieber ticket,
                        justin bieber buy ticket,
                        cheap tickets for justin bieber,
                        justin bieber concerts near me"
                ],
                [
                    "role" => "user",
                    "content" => $this->contentForCleaningKeywords($keywords, $city, $type)

                ],
            ],
            'temperature' => 1.0,
            'max_tokens' => 2000,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
        ]);
        return $this->parseGPTOutput($complete);
    }

    public function parseGPTOutput($output)
    {
        $output = json_decode($output);
        if (!isset($output->choices[0]->message->content)) {
            return [];
        }
        $output = $output->choices[0]->message->content;
        $output = explode(",", $output);
        return array_map('trim', $output);
    }

    public function generateText($keywords, $city = '', $venue = '', $date = '', $type = 'event', $segment = '')
    {
        $complete = $this->client->chat([
            'model' => 'gpt-3.5-turbo',
            'messages' => [
                [
                    "role" => "system",
                    "content" => "You are a SEO specialist."
                ],
                [
                    "role" => "user",
                    "content" => "What is SEO?"
                ],
                [
                    "role" => "system",
                    "content" => "SEO is a process of optimizing your website to get organic, or un-paid,
                    traffic from the search engine results page."
                ],
                [
                    "role" => "user",
                    "content" => $this->contentForEventTextGeneration($keywords, $city, $venue, $date, $type, $segment)
                ],
            ],
            'temperature' => 1.0,
            'max_tokens' => 3000,
            'frequency_penalty' => 0,
            'presence_penalty' => 0,
        ]);
        $output = json_decode($complete);
        if (!isset($output->choices[0]->message->content)) {
            return '';
        }

        return $output->choices[0]->message->content;
    }

    public function contentForCleaningKeywords($keywords, $city, $type = 'event')
    {
        switch ($type){
            case 'city':
                $cleanedKeywords = $this->contentForCityCleaningKeyword($keywords, $city);
                break;
            case 'segment':
                $cleanedKeywords = $this->contentForSegmentCleaningKeyword($keywords, $city);
                break;
            case 'venue':
                $cleanedKeywords = $this->contentForVenueCleaningKeyword($keywords, $city);
                break;
            case 'attendee':
                $cleanedKeywords = $this->contentForAttendeeCleaningKeyword($keywords, $city);
                break;
            default:
                $cleanedKeywords = $this->contentForEventsCleaningKeyword($keywords, $city);
                break;
        }

        return $cleanedKeywords;
    }

    public function contentForVenueCleaningKeyword($keywords, $city)
    {
        return "Optimize this list of keywords.
                Keep only keywords which user should use when he want to search for events in specific venue.
                Strictly remove keywords that are related to merchandise, merch, clothes.
                also remove any keywords related to notes, chords, tabs, lyrics, etc.
                or something related to online event watching like dvd or youtube.
                Keywords: ". $keywords . ".
                If you can find keywords output should strictly be in this format: key1, key2, key3 ...
                If you can not find any keywords - strictly output 'null'.
                There shouldn't be any additional information in the output.";
    }

    public function contentForAttendeeCleaningKeyword($keywords, $city)
    {
        return "Optimize this list of keywords.
                Keep only keywords which user should use when he want to search for events with specific artist, band or sport team.
                Strictly remove keywords that are related to merchandise, merch, clothes.
                also remove any keywords related to notes, chords, tabs, lyrics, etc.
                or something related to online event watching like dvd or youtube.
                Keywords: ". $keywords . ".
                If you can find keywords output should strictly be in this format: key1, key2, key3 ...
                If you can not find any keywords - strictly output 'null'.
                There shouldn't be any additional information in the output.";
    }

    public function contentForSegmentCleaningKeyword($keywords, $city)
    {
        return "Optimize this list of keywords.
                Keep only keywords which user should use when he want to search for events in segment in keywords.
                Strictly remove keywords that are related to merchandise, merch, clothes.
                strictly remove keywords that relates to specific venue eg ticket to disneyland.
                Strictly remove keywords that relates to specific event eg disney on ice.
                strictly remove keywords that relates to specific artist or sport team eg Tickets for Deftones.
                also remove any keywords related to notes, chords, tabs, lyrics, etc.
                or something related to online event watching like dvd or youtube.
                Keywords: ". $keywords . ".
                If you can find keywords output should strictly be in this format: key1, key2, key3 ...
                If you can not find any keywords - strictly output 'null'.
                There shouldn't be any additional information in the output.";
    }

    public function contentForCityCleaningKeyword($keywords, $city)
    {
        return "Optimize this list of keywords.
                Keep only keywords which user should use when he want to search for events in specific city.
                Strictly remove keywords that are related to merchandise, merch, clothes.
                strictly remove keywords that relates to specific venue.
                also remove any keywords related to notes, chords, tabs, lyrics, etc.
                or something related to online event watching like dvd or youtube.
                Keywords: ". $keywords . ".
                If you can find keywords output should strictly be in this format: key1, key2, key3 ...
                If you can not find any keywords - strictly output 'null'.
                There shouldn't be any additional information in the output.";
    }

    public function contentForEventsCleaningKeyword($keywords, $city)
    {
        return "Optimize this list of keywords.
                Keep only keywords which user should use when he want to search for specific event.
                Stricktly remove keywords that contains cities or states or countries, except ". $city . "
                Strictly remove keywords that are related to merchandise, merch, clothes.
                strictly remove keywords that relates to specific venue.
                strictly remove keywords that contains song names, album names, or logos, or any other information that is not related to the event.
                also remove any keywords related to notes, chords, tabs, lyrics, etc.
                or something related to online event watching like dvd or youtube.
                Leave keywords that are related to the event or tours, like tickets, concert, live, etc.
                Keywords: ". $keywords . ".
                If you can find keywords output should strictly be in this format: key1, key2, key3 ...
                If you can not find any keywords - strictly output 'null'.
                There shouldn't be any additional information in the output.";
    }
    public function contentForEventTextGeneration($keywords, $city = '', $venue = '', $date = '', $type = 'event', $segment = '')
    {
        if($type === 'event'){
            $content = "Generate an article which promotes ". $segment ." event. Using this keywords: ". $keywords .".";
        } elseif($type === 'venue') {
            $content = "Generate an article which promotes specific venue in ". $city .". Using this keywords: ". $keywords .".";
        } elseif($type === 'attendee') {
            $content = "Generate an article which promotes specific " . $segment . " attraction. Using this keywords: ". $keywords .".";
        } elseif($type === 'segment') {
            $content = "Generate an article which promotes our website" . $segment . " events in ". $city .". Using this keywords: ". $keywords .".";
        } elseif($type === 'city') {
            $content = "Generate an article which promotes our website events in ". $city .". Using this keywords: ". $keywords .".";
        } else {
            $content = "Generate an article which promotes event. Using this keywords: ". $keywords .".";
        }
        $content .= " Use only provided information. If you do not have information for some fields - do not generate any text about it.";
        $content .= " For example if you do not have information about attendees - do not generate any text about event attendees.";
        $city !== '' ? $content .= " Event city strictly would be ". $city . "." : '';
        $venue !== '' ? $content .= " Event venue would be ". $venue . "." : '';
        $date !== '' ? $content .= " Event date would be ". $date . "." : '';
        $content .= " Article should contain at least 150 words.";
        $content .= " Also generate a SEO friendly title for the article. Title should contain the keyword";
        $content .= " Title should be less than 60 characters.";
        $content .= " Also generate a SEO friendly meta description for the article.";
        $content .= " Meta description should contain the keyword";
        $content .= " Meta description should be less than 160 characters.";
        $content .= " Output should strictly be in JSON format: title: <title>, content: <content>, meta_description: <meta_description>";
        return $content;
    }

}
