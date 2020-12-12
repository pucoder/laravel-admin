<?php

namespace Encore\Admin\Auth\Database;

use Illuminate\Support\Collection;

trait HasPermissions
{
    /**
     * Get all permissions of user.
     *
     * @return mixed
     */
    public function allPermissions()
    {
        return $this->roles()->pluck('permissions')->flatten()->merge($this->permissions);
    }

    /**
     * @param $menu
     * @return bool
     */
    public function canMenu($menu)
    {
        if (config('admin.check_permissions') && config('admin.check_menus')) {
            if ($this->isAdministrator() || isset($menu['children']) || url()->isValidUrl($menu['uri'])) {
                return true;
            }

            foreach ($this->allPermissions() as $permissions) {
                if ($permissions === '*' || in_array('GET=>' . $menu['uri'], explode('&&', $permissions))) {
                    return true;
                }
            }

            return false;
        }

        return true;
    }

    /**
     * @param $route
     * @return bool
     */
    public function canAccess($route)
    {
        if ($this->isAdministrator()) {
            return true;
        }

        $request = get_request($route);

        foreach ($this->allPermissions() as $permissions) {
            if ($permissions === '*' || in_array($request, explode('&&', $permissions))) {
                return true;
            }
        }

        return false;
    }

    /**
     * Check if user has permission.
     *
     * @param $ability
     * @param array $arguments
     *
     * @return bool
     */
    public function can($ability, $arguments = []): bool
    {
        if (empty($ability)) {
            return true;
        }

        if ($this->isAdministrator()) {
            return true;
        }

        if ($this->permissions->pluck('slug')->contains($ability)) {
            return true;
        }

        return $this->roles->pluck('permissions')->flatten()->pluck('slug')->contains($ability);
    }

    /**
     * Check if user has no permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function cannot(string $permission): bool
    {
        return !$this->can($permission);
    }

    /**
     * Check if user is administrator.
     *
     * @return mixed
     */
    public function isAdministrator(): bool
    {
        return $this->isRole('administrator');
    }

    /**
     * Check if user is $role.
     *
     * @param string $role
     *
     * @return mixed
     */
    public function isRole(string $role): bool
    {
        return $this->roles->pluck('slug')->contains($role);
    }

    /**
     * Check if user in $roles.
     *
     * @param array $roles
     *
     * @return mixed
     */
    public function inRoles(array $roles = []): bool
    {
        return $this->roles->pluck('slug')->intersect($roles)->isNotEmpty();
    }

    /**
     * If visible for roles.
     *
     * @param $roles
     *
     * @return bool
     */
    public function visible(array $roles = []): bool
    {
        if (empty($roles)) {
            return true;
        }

        $roles = array_column($roles, 'slug');

        return $this->inRoles($roles) || $this->isAdministrator();
    }
}
