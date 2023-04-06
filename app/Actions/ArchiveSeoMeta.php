<?php

namespace App\Actions;

use App\Models\City;
use App\Models\CitySegment;
use App\Models\Genre;
use App\Models\Segment;
use Lorisleiva\Actions\Concerns\AsAction;

class ArchiveSeoMeta
{
    use AsAction;

    public $title;

    public $description;

    public $h1;

    public $seoText;

    public function handle($location = '', $type = '', $date = '', $dateTo = '', $genre = '', $segment = '')
    {
        $currentRouteName = $this->getCurrentRouteName();

        $this->generateTitle($currentRouteName, $location, $type, $date, $dateTo, $genre, $segment);
        $this->generateDescription($currentRouteName, $location, $type, $date, $dateTo, $genre, $segment);
        $this->generateH1($currentRouteName, $location, $type, $date, $dateTo, $genre, $segment);
        $this->generateSeoText($currentRouteName, $location, $type, $date, $dateTo, $genre, $segment);

        return [
            'title' => $this->title,
            'description' => $this->description,
            'h1' => $this->h1,
            'seoText' => $this->seoText,
        ];
    }

    private function getCitySegmentModel($city, $segment)
    {
        return CitySegment::where('city_name', $city)->where('segment_name', $segment)->first();
    }

    private function getSegmentModel($segment)
    {
        return Segment::where('name', $segment)->first();
    }

    private function getGenreModel($genre)
    {
        return Genre::where('name', $genre)->first();
    }

    private function getCityModel($city)
    {
        return City::where('name', $city)->first();
    }


    //function to get current route
    private function getCurrentRouteName()
    {
        return \Request::route()->getName();
    }

    private function generateH1($currentRouteName, $location = '', $type = '', $date = '', $dateTo = '', $genre = '', $segment = '')
    {
        switch ($currentRouteName) {
            case 'segment':
                $this->h1 = ucfirst($segment) . ' Events ' .
                    ($genre !== '' ? ' - ' . $genre : '') .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($dateTo !== '' ? 'Ends On ' . $dateTo : '');
                break;
            case 'genre':
                $this->h1 = ucfirst($genre) . ' Events ' .
                    ($segment !== '' ? ' - ' . $segment : '') .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($dateTo !== '' ? 'Ends On ' . $dateTo : '');
                break;
            case 'events':
                $this->h1 = 'Events in ' . $location .
                    ($genre !== '' ? ' - ' . $genre : '') .
                    ($segment !== '' ? ' - ' . $segment : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($dateTo !== '' ? 'Ends On ' . $dateTo : '');
                break;
            case 'home':
                $this->h1 = 'Tickets for Events and Concerts';
                break;
            default:
                $this->h1 = 'Events ' .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($genre !== '' ? ' - ' . $genre : '') .
                    ($segment !== '' ? ' - ' . $segment : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($dateTo !== '' ? 'Ends On ' . $dateTo : '');
                break;
        }
    }

    private function generateSeoText($currentRouteName, $location = '', $type = '', $date = '', $dateTo = '', $genre = '', $segment = '')
    {
        switch ($currentRouteName) {
            case 'segment':
                if($location !== 'All Cities') {
                    $citySegmentModel = $this->getCitySegmentModel($location, $segment);
                    if ($citySegmentModel) {
                        $this->seoText = $citySegmentModel->seo_content;
                        return;
                    }
                } else {
                    $segmentModel = $this->getSegmentModel($segment);
                    if ($segmentModel) {
                        $this->seoText = $segmentModel->seo_content;
                        return;
                    }
                }
                $this->seoText = '';
                break;
            case 'genre':
                if($location !== 'All Cities') {
                    $citySegmentModel = $this->getCitySegmentModel($location, $genre);
                    if ($citySegmentModel) {
                        $this->seoText = $citySegmentModel->seo_content;
                        return;
                    }
                } else {
                    $genreModel = $this->getGenreModel($genre);
                    if ($genreModel) {
                        $this->seoText = $genreModel->seo_content;
                        return;
                    }
                }
                $this->seoText = '';
                break;
            case 'events':
                $cityModel = $this->getCityModel($location);
                if ($cityModel) {
                    $this->seoText = $cityModel->seo_content;
                    return;
                }
                $this->seoText = '';
                break;
            case 'home':
                $this->seoText = '';
                break;
            default:
                $this->seoText = '';
                break;
        }

    }


    private function generateTitle($currentRouteName, $location = '', $type = '', $date = '', $dateTo = '', $genre = '', $segment = '')
    {
        switch ($currentRouteName) {
            case 'segment':
                if($location !== 'All Cities') {
                    $citySegmentModel = $this->getCitySegmentModel($location, $segment);
                    if ($citySegmentModel) {
                        $this->title = $citySegmentModel->seo_title . (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');;
                        return;
                    }
                } else {
                    $segmentModel = $this->getSegmentModel($segment);
                    if ($segmentModel) {
                        $this->title = $segmentModel->seo_title . (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');;
                        return;
                    }
                }
                $this->title = 'Buy ' . ucfirst($segment) . ' Tickets 🎫' .
                    ($genre !== '' ? ' - ' . $genre : '') .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($dateTo !== '' ? 'Ends On ' . $dateTo : '') .
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
            case 'genre':
                if($location !== 'All Cities') {
                    $citySegmentModel = $this->getCitySegmentModel($location, $genre);
                    if ($citySegmentModel && $citySegmentModel->seo_title) {
                        $this->title = $citySegmentModel->seo_title . (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');;
                        return;
                    }
                } else {
                    $genreModel = $this->getGenreModel($genre);
                    if ($genreModel && $genreModel->seo_title) {
                        $this->title = $genreModel->seo_title . (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                        return;
                    }
                }
                $this->title = ucfirst($genre) . ' Events and Concerts 🎫 Buy Tickets ' .
                    ($segment !== '' ? ' - ' . $segment : '') .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($dateTo !== '' ? 'Ends On ' . $dateTo : '') .
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
            case 'events':
                $cityModel = $this->getCityModel($location);
                if ($cityModel) {
                    $this->title = $cityModel->seo_title . (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                    return;
                }
                $this->title = 'Buy Tickets for Concerts and Events In ' . $location .
                    ($genre !== '' ? ' - ' . $genre : '') .
                    ($segment !== '' ? ' - ' . $segment : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($dateTo !== '' ? 'Ends On ' . $dateTo : '') .
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
            case 'home':
                $this->title = 'All Concerts and Sport Events Tickets '.
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
            default:
                $this->title = 'Concerts and Events ' .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($genre !== '' ? ' - ' . $genre : '') .
                    ($segment !== '' ? ' - ' . $segment : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($dateTo !== '' ? 'Ends On ' . $dateTo : '') .
                    ' Get Tickets ' .
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
        }
        if($this->title === '') {
            $this->title = $segment . ' Events and Concerts, Buy Tickets ' . (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');;
        }
    }

    private function generateDescription($currentRouteName, $location = '', $type = '', $date = '', $dateTo = '', $genre = '', $segment = '')
    {
        switch ($currentRouteName) {
            case 'segment':
                if($location !== 'All Cities') {
                    $citySegmentModel = $this->getCitySegmentModel($location, $segment);
                    if ($citySegmentModel) {
                        $this->description = $citySegmentModel->seo_description;
                        return;
                    }
                } else {
                    $segmentModel = $this->getSegmentModel($segment);
                    if ($segmentModel) {
                        $this->description = $segmentModel->seo_description;
                        return;
                    }
                }
                $this->description = 'Get ' . $segment . ' Events ' .
                    ($genre !== '' ? ' - ' . $genre : '') .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($dateTo !== '' ? 'Ends On ' . $dateTo : '') .
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
            case 'genre':
                if($location !== 'All Cities') {
                    $citySegmentModel = $this->getCitySegmentModel($location, $genre);
                    if ($citySegmentModel) {
                        $this->description = $citySegmentModel->seo_description;
                        return;
                    }
                } else {
                    $segmentModel = $this->getGenreModel($genre);
                    if ($segmentModel) {
                        $this->description = $segmentModel->seo_description;
                        return;
                    }
                }
                $this->description = 'Get ' . $genre . ' Events ' .
                    ($segment !== '' ? ' - ' . $segment : '') .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($dateTo !== '' ? 'Ends On ' . $dateTo : '') .
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
            case 'events':
                $cityModel = $this->getCityModel($location);
                if ($cityModel) {
                    $this->description = $cityModel->seo_description;
                    return;
                }
                $this->description = 'Get Your Tickets to ' . $location . ' Upcoming Events ' .
                    ($genre !== '' ? ' - ' . $genre : '') .
                    ($segment !== '' ? ' - ' . $segment : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($dateTo !== '' ? 'Ends On ' . $dateTo : '') .
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
            case 'home':
                $this->description = 'Get the best cheap tickets for concerts and events in all US'.
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
            default:
                $this->description = 'Get ' . $location . ' Events ' .
                    ($genre !== '' ? ' - ' . $genre : '') .
                    ($segment !== '' ? ' - ' . $segment : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($dateTo !== '' ? 'Ends On ' . $dateTo : '') .
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
        }
    }
}
