<?php

namespace Blueday\OrderStatus;

use Laravel\Nova\ResourceTool;

class OrderStatus extends ResourceTool
{
    /**
     * Get the displayable name of the resource tool.
     *
     * @return string
     */
    public function name()
    {
        return 'Order Status';
    }

    /**
     * Get the component name for the resource tool.
     *
     * @return string
     */
    public function component()
    {
        return 'order-status';
    }
}
