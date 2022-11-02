<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

/**
 * @implements \Lorisleiva\Actions\Action<array<string, string>>
 */
class GenerateTicketMasterQuery
{
    use AsAction;

    public function handle()
    {
        return http_build_query($this->filteredQueryMapping());
    }

    /**
     * Get filtered query mapping for request to TicketMaster API.
     * @return array <string, string>
     */
    public function filteredQueryMapping(): array
    {
        $data = $this->getQueryDataFromDB();
        return array_filter($data, fn ($value) => $value !== '');
    }


    /**
     * Get query mapping for request to TicketMaster API.
     * @return array <string, string>
     */
    public function getQueryDataFromDB(): array
    {
        return [
            'apikey' => env('TICKETMASTER_API_KEY'),
            'keyword' => setting('ticketmaster.keyword'),
            'attractionId' => setting('ticketmaster.attractionId'),
            'postalCode' => setting('ticketmaster.postalCode'),
            'latlong' => setting('ticketmaster.latlong'),
            'source' => setting('ticketmaster.source'),
            'locale' => setting('ticketmaster.locale'),
            'startDateTime' => $this->getFormattedDate(setting('ticketmaster.startDateTime')),
            'endDateTime' => $this->getFormattedDate(setting('ticketmaster.endDateTime')),
            'size' => setting('ticketmaster.size') ?? env('TICKETMASTER_QUERY_SIZE') ?? 20,
            'page' => setting('ticketmaster.page') ?? 0,
            'sort' => setting('ticketmaster.sort'),
            'onsaleStartDateTime' => $this->getFormattedDate(setting('ticketmaster.onsaleStartDateTime'))
                ?? $this->getDefaultOnSaleStartDateTime(),
            'onsaleEndDateTime' => $this->getFormattedDate(setting('ticketmaster.onsaleEndDateTime'))
                ?? $this->getDefaultOnSaleEndDateTime(),
            'city' => setting('ticketmaster.city'),
            'countryCode' => setting('ticketmaster.countryCode') ?? 'US',
            'stateCode' => setting('ticketmaster.stateCode'),
            'classificationName' => setting('ticketmaster.classificationName') ?? 'music',
            'segmentName' => setting('ticketmaster.segmentName') ?? 'music',
            'genreName' => setting('ticketmaster.genreName'),
            'preferredCountry' => setting('ticketmaster.preferredCountry') ?? 'US',
        ];
    }

    /**
     * Get any date string and convert their into 'Y-m-d\TH:i:s\Z' format.
     * If no date string is provided, then return null.
     * @param string | null $date
     * @return string | null
     */
    public function getFormattedDate(string $date = null): string | null
    {
        return $date ? date('Y-m-d\TH:i:s\Z', strtotime($date)) : null;
    }

    public function getDefaultOnSaleStartDateTime(): string
    {
        return date('Y-m-d\TH:i:s\Z', strtotime('-7 days'));
    }

    public function getDefaultOnSaleEndDateTime(): string
    {
        return date('Y-m-d\TH:i:s\Z', strtotime('+7 days'));
    }
}
