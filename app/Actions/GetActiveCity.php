<?php

namespace App\Actions;

use Lorisleiva\Actions\Concerns\AsAction;

class GetActiveCity
{
    use AsAction;

    public $location;

    public $type;

    public function handle()
    {

        return [
            'user_location' => request()->cookie('user_location') ?? 'All Cities',
            'user_location_type' => request()->cookie('user_location_type') ?? 'city',
        ];
    }
}
