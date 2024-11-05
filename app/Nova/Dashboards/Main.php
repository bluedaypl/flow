<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\OrderCount;
use App\Nova\Metrics\OrderStatus;
use Laravel\Nova\Cards\Help;
use Laravel\Nova\Dashboards\Main as Dashboard;

class Main extends Dashboard
{
    public function name()
    {
        return 'Pulpit';
    }


    /**
     * Get the cards for the dashboard.
     *
     * @return array
     */
    public function cards()
    {
        return [
            new OrderCount,
            new OrderStatus
        ];
    }
}
