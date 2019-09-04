<?php

namespace App\Observers;

use App\Models\Report;

class ReportObserve
{
    public function created(Report $combo)
    {
        $combo->afterCreated();
    }
}
