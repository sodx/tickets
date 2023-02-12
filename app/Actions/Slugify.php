<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

class Slugify
{
    use AsAction;

    public $title;

    public $description;

    public function handle( $string )
    {
        $string = strtolower($string);
        $string = preg_replace("/[^a-z0-9_\s-]/", "", $string);
        $string = preg_replace("/[\s-]+/", " ", $string);
        $string = preg_replace("/[\s_]/", "-", $string);
        return $string;
    }
}
