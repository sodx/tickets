<?php

namespace App\Actions\Serpstat;

use GuzzleHttp\Client;
use Lorisleiva\Actions\Concerns\AsAction;

class SerpstatClient
{
    use AsAction;

    private $token;
    private $client;
    private $city;
    private $keyword;
    private $query = [];
    private $SE = 'g_us';
    private $additionalKeywords = [];

    const API_HOST = 'https://api.serpstat.com/v4/?token=';
    const SE = 'g_us';
    const LIMIT = 50;
    const METHOD = 'SerpstatKeywordProcedure.getKeywords';
    const ID = 1;

    private function generateUUID()
    {
        return sprintf(
            '%04x%04x-%04x-%04x-%04x-%04x%04x%04x',
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0x0fff) | 0x4000,
            mt_rand(0, 0x3fff) | 0x8000,
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff),
            mt_rand(0, 0xffff)
        );
    }
    public function handle($keyword, $city = '', $additionalKeywords = [])
    {
        $this->additionalKeywords = $additionalKeywords;
        $this->keyword = $keyword;
        $this->city = $city;
        $this->token = env('SERPSTAT_API_KEY');
        $this->client = $this->apiClient();
        $this->query = $this->prepareQuery();

        $preferredCountry = setting('ticketmaster.preferredCountry') ?? 'US';

        if ($preferredCountry === 'CA') {
            $this->SE = 'g_ca';
        }

        $response = $this->runQuery();
        if (is_array($response)) {
            return [];
        }
        $response = json_decode($response->getBody()->getContents(), true);

        if (isset($response['result']['data'])) {
            return $response['result']['data'];
        }
        return [];
    }


    private function apiClient()
    {
        return new Client([
            'method' => 'POST',
            'timeout'  => 5.0,
            'headers' => [
                'Content-Type' => 'application/json',
            ],
        ]);
    }

    private function prepareQuery()
    {
        return [
            'id' => $this->generateUUID(),
            "method" => self::METHOD,
            'params' => [
                'se' => $this->SE,
                'size' => self::LIMIT,
                'keyword' => $this->keyword,
                'filters' => $this->prepareFilters(),
                "sort" => [
                    "region_queries_count" => "desc",
                    "difficulty" => "asc",
                ],
            ],
        ];
    }

    private function prepareFilters()
    {
        return [
            'keyword_contain_one_of' => $this->keywordContains(),
            'keyword_not_contain_one_of' => array_merge(
                $this->keywordNotContains(),
                $this->allAmericanCitiesNameArray($this->city)
            ),
            'right_spelling' => false,
            'concurrency_from' => 5,
            'difficulty_to' => 69,
        ];
    }

    private function runQuery()
    {
        try {
            $response = $this->client->request('POST', self::API_HOST.$this->token, [
                'json' => $this->query
            ]);
        } catch (\Exception $e) {
            $response = [];
        }

        return $response;
    }

    private function keywordContains()
    {
        $defaultKeywords = ['tour', 'ticket', 'concert', 'gig', 'event'];
        return $defaultKeywords;

       //return array_merge($defaultKeywords, $this->additionalKeywords);
    }

    private function keywordNotContains()
    {
        return [ 'vinyl', 'wear', 'what', 'ticketmaster', 'poster', 'posters', 'outfit', 'shirts', 'outfits',
            '2020', '2021', '2022', 'merch', 'asia', 'album', 'europe', 'toronto', 'lyrics', 'review', 'reviews',  'nettle', 'nettles' ];
    }

    private function allAmericanCitiesNameArray($city = '')
    {
        $cities = [
            'paris', 'london', 'rome', 'madrid', 'barcelona', 'berlin', 'prague', 'vienna', 'amsterdam', 'brussels',
            'budapest', 'warsaw', 'dublin', 'copenhagen', 'stockholm', 'athens', 'reykjavik', 'oslo', 'helsinki',
            'luxembourg', 'lisbon', 'bratislava', 'valletta', 'monaco', 'bern', 'zagreb', 'skopje', 'sarajevo',
            'podgorica', 'tirana', 'bucharest', 'belgrade', 'sofia', 'kiev', 'minsk', 'riga', 'vilnius', 'tallinn',
            'moscow', 'st. petersburg', 'istanbul', 'ankara', 'baku', 'tbilisi', 'yerevan', 'tehran', 'baghdad',
            'doha', 'riyadh', 'jeddah', 'beirut', 'amman', 'jerusalem', 'cairo', 'dubai', 'muscat', 'kabul',
            'New York', 'Los Angeles', 'Chicago', 'Houston', 'Philadelphia', 'Phoenix', 'San Antonio', 'San Diego',
            'Dallas', 'San Jose', 'Austin', 'Jacksonville', 'San Francisco', 'Indianapolis', 'Columbus', 'Fort Worth',
            'Charlotte', 'Detroit', 'El Paso', 'Memphis', 'Boston', 'Seattle', 'Denver', 'Washington', 'Nashville',
            'Baltimore', 'Louisville', 'Milwaukee', 'Portland', 'Las Vegas', 'Oklahoma City', 'Albuquerque', 'Tucson',
            'Fresno', 'Sacramento', 'Long Beach', 'Kansas City', 'Mesa', 'Atlanta', 'Virginia Beach', 'Omaha', 'Colorado Springs',
            'Raleigh', 'Miami', 'Oakland', 'Minneapolis', 'Tulsa', 'Cleveland', 'Wichita', 'Arlington', 'New Orleans',
            'Bakersfield', 'Tampa', 'Honolulu', 'Aurora', 'Anaheim', 'Santa Ana', 'St. Louis', 'Riverside', 'Corpus Christi',
            'Lexington', 'Pittsburgh', 'Anchorage', 'Stockton', 'Cincinnati', 'Saint Paul', 'Toledo', 'Greensboro', 'Newark',
            'Plano', 'Henderson', 'Lincoln', 'Buffalo', 'Jersey City', 'Chula Vista', 'Fort Wayne', 'Orlando', 'St. Petersburg',
            'Chandler', 'Laredo', 'Norfolk', 'Durham', 'Madison', 'Lubbock', 'Irvine', 'Winston–Salem', 'Glendale', 'Garland',
            'Hialeah', 'Reno', 'Chesapeake', 'Gilbert', 'Baton Rouge', 'Irving', 'Scottsdale', 'North Las Vegas', 'Fremont',
            'Boise City', 'Richmond', 'San Bernardino', 'Birmingham', 'Spokane', 'Rochester', 'Des Moines', 'Modesto',
            'Fayetteville', 'Tacoma', 'Oxnard', 'Fontana', 'Columbus', 'Montgomery', 'Moreno Valley', 'Shreveport', 'Aurora',
            'Yonkers', 'Akron', 'Huntington Beach', 'Little Rock', 'Augusta', 'Amarillo', 'Glendale', 'Mobile', 'Grand Rapids',
            'Salt Lake City', 'Tallahassee', 'Huntsville', 'Grand Prairie', 'Knoxville', 'Worcester', 'Newport News', 'Brownsville',
            'Overland Park', 'Santa Clarita', 'Providence', 'Garden Grove', 'Chattanooga', 'Oceanside', 'Jackson', 'Fort Lauderdale',
            'Santa Rosa', 'Rancho Cucamonga', 'Port St. Lucie', 'Tempe', 'Ontario', 'Vancouver', 'Sioux Falls', 'Springfield'
        ];

        if ($city) {
            $key = array_search($city, $cities);
            if ($key !== false) {
                unset($cities[$key]);
            }
        }

        return $cities;
    }
}
