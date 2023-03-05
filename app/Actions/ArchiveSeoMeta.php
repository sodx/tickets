<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

class ArchiveSeoMeta
{
    use AsAction;

    public $title;

    public $description;

    public $h1;

    public function handle($location = '', $type = '', $date = '', $dateTo = '', $genre = '', $segment = '')
    {
        $currentRouteName = $this->getCurrentRouteName();
        $this->generateTitle($currentRouteName, $location, $type, $date, $dateTo, $genre, $segment);
        $this->generateDescription($currentRouteName, $location, $type, $date, $dateTo, $genre, $segment);
        $this->generateH1($currentRouteName, $location, $type, $date, $dateTo, $genre, $segment);

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


    private function generateTitle($currentRouteName, $location = '', $type = '', $date = '', $dateTo = '', $genre = '', $segment = '')
    {
        switch ($currentRouteName) {
            case 'segment':
                $this->title = 'Buy ' . ucfirst($segment) . ' Tickets 🎫' .
                    ($genre !== '' ? ' - ' . $genre : '') .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($dateTo !== '' ? 'Ends On ' . $dateTo : '') .
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
            case 'genre':
                $this->title = 'Buy '. ucfirst($genre) . ' Tickets 🎫' .
                    ($segment !== '' ? ' - ' . $segment : '') .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($dateTo !== '' ? 'Ends On ' . $dateTo : '') .
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
            default:
                $this->title = 'Buy Tickets ' .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($genre !== '' ? ' - ' . $genre : '') .
                    ($segment !== '' ? ' - ' . $segment : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($dateTo !== '' ? 'Ends On ' . $dateTo : '') .
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
        }
    }

    private function generateDescription($currentRouteName, $location = '', $type = '', $date = '', $dateTo = '', $genre = '', $segment = '')
    {
        switch ($currentRouteName) {
            case 'segment':
                $this->description = 'Get ' . $segment . ' Events ' .
                    ($genre !== '' ? ' - ' . $genre : '') .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($dateTo !== '' ? 'Ends On ' . $dateTo : '') .
                    (setting('site.title') ? ' | ' . setting('site.title') : ' | Liveconcerts');
                break;
            case 'genre':
                $this->description = 'Get ' . $genre . ' Events ' .
                    ($segment !== '' ? ' - ' . $segment : '') .
                    ($location !== '' ? 'in ' . $location : '') .
                    ($date !== '' ? 'Starts From ' . $date : '') .
                    ($dateTo !== '' ? 'Ends On ' . $dateTo : '') .
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
