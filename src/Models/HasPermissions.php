<?php

namespace Encore\Admin\Models;

use Illuminate\Routing\Route;

trait HasPermissions
{
    /**
     * Check if user is administrator.
     *
     * @return bool
     */
    public function isAdministrator(): bool
    {
        return $this->id === 1;
    }

    /**
     * If User can see menu item.
     *
     * @param $menu
     * @return bool
     */
    public function canMenu($menu)
    {
        return true;
    }

    /**
     * If user can access route.
     *
     * @param Route $route
     * @return bool
     */
    public function canRoute(Route $route)
    {
        return true;
    }
}
