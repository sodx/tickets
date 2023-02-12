<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

class ArchiveSeoMeta
{
    use AsAction;

    public $title;

    public $description;

    public $h1;

    public function handle($location = '', $type = '', $date = '', $date_to = '', $genre = '', $segment = '')
    {
        $currentRouteName = $this->getCurrentRouteName();
        $this->generateTitle($currentRouteName, $location, $type, $date, $date_to, $genre, $segment);
        $this->generateDescription($currentRouteName, $location, $type, $date, $date_to, $genre, $segment);
        $this->generateH1($currentRouteName, $location, $type, $date, $date_to, $genre, $segment);

        return [
            'title' => $this->title,
            'description' => $this->description,
            'h1' => $this->h1,
        ];
    }

    //function to get current route
    private function getCurrentRouteName()
    {
        return \Request::route()->getName();
    }

    private function generateH1($currentRouteName, $location = '', $type = '', $date = '', $date_to = '', $genre = '', $segment = '')
    {
        switch ($currentRouteName) {
            case 'segment':
                $this->h1 = ucfirst($segment) . ' Events ' .
                    ($genre !== '' ? ' - ' . $genre : '') .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($date_to !== '' ? 'Ends On ' . $date_to : '');
                break;
            case 'genre':
                $this->h1 = ucfirst($genre) . ' Events ' .
                    ($segment !== '' ? ' - ' . $segment : '') .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($date_to !== '' ? 'Ends On ' . $date_to : '');
                break;
            default:
                $this->h1 = 'Events ' .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($genre !== '' ? ' - ' . $genre : '') .
                    ($segment !== '' ? ' - ' . $segment : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($date_to !== '' ? 'Ends On ' . $date_to : '');
                break;
        }
    }

    private function generateTitle($currentRouteName, $location = '', $type = '', $date = '', $date_to = '', $genre = '', $segment = '')
    {
        switch ($currentRouteName) {
            case 'segment':
                $this->title = ucfirst($segment) . ' Events ' .
                    ($genre !== '' ? ' - ' . $genre : '') .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($date_to !== '' ? 'Ends On ' . $date_to : '') .
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
            case 'genre':
                $this->title = ucfirst($genre) . ' Events ' .
                    ($segment !== '' ? ' - ' . $segment : '') .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($date_to !== '' ? 'Ends On ' . $date_to : '') .
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
            default:
                $this->title = 'Events ' .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($genre !== '' ? ' - ' . $genre : '') .
                    ($segment !== '' ? ' - ' . $segment : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($date_to !== '' ? 'Ends On ' . $date_to : '') .
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
        }
    }

    private function generateDescription($currentRouteName, $location = '', $type = '', $date = '', $date_to = '', $genre = '', $segment = '')
    {
        switch ($currentRouteName) {
            case 'segment':
                $this->description = 'Find ' . $segment . ' Events ' .
                    ($genre !== '' ? ' - ' . $genre : '') .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($date_to !== '' ? 'Ends On ' . $date_to : '') .
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
            case 'genre':
                $this->description = 'Find ' . $genre . ' Events ' .
                    ($segment !== '' ? ' - ' . $segment : '') .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($date_to !== '' ? 'Ends On ' . $date_to : '') .
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
            default:
                $this->description = 'Find ' . $location . ' Events ' .
                    ($genre !== '' ? ' - ' . $genre : '') .
                    ($segment !== '' ? ' - ' . $segment : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($date_to !== '' ? 'Ends On ' . $date_to : '') .
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
        }
    }
}
