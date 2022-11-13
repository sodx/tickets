<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

abstract class SaveDataFromTM
{
    use AsAction;

    public function handle($data): object
    {
        return $this->upsertData($data);
    }

    public function upsertData(array $data): object
    {
        return (object)$data;
    }

    protected function shouldUpdateItem(array $data, object $model): bool
    {
        return ! $this->isItemExists($data['id'], $model)
            || (
                $this->isItemExists($data['id'], $model)
                && $this->isItemUpdatedDuringLast24Hours($data['id'], $model)
            );
    }

    /**
     * Check is Venue exists in DB.
     *
     * @param string $ticketmasterID
     * @return bool
     */
    protected function isItemExists(string $ticketmasterID, object $item): bool
    {
        return $item::where('ticketmaster_id', $ticketmasterID)->exists();
    }

    /**
     * Get the date of last venue update.
     *
     * @param string $ticketMasterID
     * @return string
     */
    protected function getLastItemUpdateDate(string $ticketMasterID, object $item): string
    {
        return $item::where('ticketmaster_id', $ticketMasterID)->first()->updated_at;
    }

    /**
     * Check if event was updated during last 24 hours.
     *
     * @param string $ticketMasterID
     * @return boolean
     */
    protected function isItemUpdatedDuringLast24Hours(string $ticketMasterID, object $item): bool
    {
        $lastUpdateDate = $this->getLastItemUpdateDate($ticketMasterID, $item);
        $lastUpdateDate = strtotime($lastUpdateDate);
        $now = strtotime(date('Y-m-d H:i:s'));
        $diff = $now - $lastUpdateDate;
        return $diff < 86400;
    }

    /**
     * Get the biggest image from array of images.
     *
     * @param array $images
     * @return string | null
     */
    protected function getBiggestImage(array $images): string | null
    {
        if (empty($images)) {
            return null;
        }
        $biggestImage = $images[0];
        foreach ($images as $image) {
            if ($image['width'] > $biggestImage['width']) {
                $biggestImage = $image;
            }
        }
        return $biggestImage['url'];
    }

    /**
     * Get the smallest image from array of images.
     *
     * @param array $images
     * @return string | null
     */
    protected function getSmallestImage(array $images): string | null
    {
        if (empty($images)) {
            return null;
        }
        $smallestImage = $images[0];
        foreach ($images as $image) {
            if ($image['width'] < $smallestImage['width']) {
                $smallestImage = $image;
            }
        }
        return $smallestImage['url'];
    }


    protected function generateTitle($event): string
    {
        return $event['name'];
    }


    protected function generateDescription($event): string
    {
        return $event['name'];
    }
}
