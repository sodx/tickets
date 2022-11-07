<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

class GenerateSeoMeta
{
    use AsAction;

    public $title;

    public $description;

    public $keywords;

    public function handle($event)
    {
        $this->title = $this->generateTitle($event);
        $this->description = $this->generateDescription($event);
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
        return $event['name'];
    }


    public function generateDescription($event): string
    {
        return $event['name'];
    }

}
