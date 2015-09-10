<?php

namespace App\Services;

use Illuminate\Support\Facades\Log;

class GetProjectedPoints
{
    public function run()
    {
        Log::info('ran');
        file_put_contents(public_path() . "/projections.csv", fopen("http://www.fantasysharks.com/apps/bert/forecasts/projections.php?csv=1&Sort=&Segment=532&Position=99&scoring=2&League=-1&uid=4&uid2=&printable=", 'r'));
    }
}
