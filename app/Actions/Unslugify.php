<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

class Unslugify
{
    use AsAction;

    public $title;

    public $description;

    public function handle( $string )
    {
        $string = str_replace('-', ' ', $string);
        $string = ucwords($string);
        return $string;
    }
}
