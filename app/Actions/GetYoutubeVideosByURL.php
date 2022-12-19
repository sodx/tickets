<?php

namespace App\Actions;

use Alaouy\Youtube\Youtube;
use App\Models\Attraction;
use Lorisleiva\Actions\Concerns\AsAction;

class GetYoutubeVideosByURL
{
    use AsAction;

    public string $ID;
    public string $contentType;
    public array $issues;
    private Youtube $youtubeService;

    public function handle($youtubeUrl): string
    {
        $this->ID = $this->getYoutubeChannelIDFromURL($youtubeUrl);
        $this->contentType = $this->defineContentTypeByURL($youtubeUrl);
        $this->youtubeService = new Youtube(env('YOUTUBE_API_KEY'));
        $videos = '';

        if ($this->contentType == 'playlist') {
            $this->ID = $this->getYoutubeVideosFromPlaylist();
        } elseif ($this->contentType == 'user') {
            $this->ID = $this->retrieveChannelIDByAccountName();
        }

        if (empty($this->issues)) {
            $videoList = $this->getYoutubeVideosFromChannel();
        }

        if (empty($this->issues) && !empty($videoList)) {
            $videos = $this->saveVideos($videoList);
        }

        return $videos;
    }


    /**
     * Save videos to attraction model.
     *
     * @param array $videoList
     * @return string
     */
    protected function saveVideos(array $videoList) : string
    {
        $ids = [];
        if (isset($videoList['results']) && $videoList['results'] !== false) {
            foreach ($videoList['results'] as $video) {
                $ids[] = $video->id->videoId;
            }
            return implode(',', $ids);
        }
        return '';
    }


    /**
     * Method to receive most viewed youtube videos by providen playlist url using youtube API.
     *
     * @param int $maxResults
     * @return array|void
     */
    public function getYoutubeVideosFromChannel($maxResults = 4)
    {
        $query = [
            'channelId' => $this->ID,
            'maxResults' => $maxResults,
            'order' => 'viewcount',
            'q' => 'live|concert',
            'type' => 'video',
        ];

        try {
            $videoList = $this->youtubeService->searchAdvanced($query, true);
            return $videoList;
        } catch (\Exception $e) {
            $this->issues[] = $e->getMessage();
        }
    }


    /**
     * Method to receive channel ID by providen channelName.
     *
     * @return \Exception|string
     */
    public function retrieveChannelIDByAccountName()
    {
        try {
            $channel = $this->youtubeService->getChannelByName($this->ID);
            return $channel->id;
        } catch (\Exception $e) {
            $this->issues[] = $e->getMessage();
            return $e;
        }
    }

    /**
     * @param string $youtube_url
     * @return mixed
     */
    protected function getYoutubeChannelIDFromURL(string $youtubeUrl)
    {
        $regex = '/(?:youtube.com\/channel\/|youtube.com\/user\/|youtube.com\/c\/|youtube.com\/)([a-zA-Z0-9_-]+)/';
        preg_match($regex, $youtubeUrl, $matches);
        return $matches[1];
    }


    /**
     * @param string $youtubeUrl
     * @return string
     */
    protected function defineContentTypeByURL(string $youtubeUrl): string
    {
        if (str_contains($youtubeUrl, 'channel')) {
            return 'channel';
        } elseif (str_contains($youtubeUrl, 'playlist')) {
            return 'playlist';
        }
        return 'user';
    }
}
