<?php

namespace App\Nova\Dashboards;

use App\Nova\Metrics\DoneOrder;
use App\Nova\Metrics\OrderCount;
use App\Nova\Metrics\OrderStatus;
use App\Nova\Metrics\UserActivity;
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
            DoneOrder::make()->width('1/3'),
            OrderCount::make()->width('1/3'),
            OrderStatus::make()->width('1/3'),
            UserActivity::make()->width('full'),
        ];
    }
}
