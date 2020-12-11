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
        return $this->roles()->get()->pluck('permissions')->flatten()->merge($this->permissions);
    }

    public function canMenu($menu)
    {
        if (config('admin.check_route_permission') && config('admin.check_menus')) {
            if ($this->isAdministrator() || !$menu['uri'] || url()->isValidUrl($menu['uri'])) {
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

    public function canAccess($route)
    {
        if ($this->isAdministrator()) {
            return true;
        }

        $allPermissions = $this->allPermissions();

        $request = get_request($route);
//        dd($request);
        foreach ($allPermissions as $permissions) {
            if ($permissions === '*' || $permissions === $request || in_array($request, explode('&&', $permissions))) {
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
    public function can($ability, $arguments = [])
    {
        if (empty($ability)) {
            return true;
        }

        if ($this->isAdministrator()) {
            return true;
        }

        if ($this->permissions->contains($ability)) {
            return true;
        }

        return $this->roles->pluck('permissions')->flatten()->contains($ability);
    }

    /**
     * Check if user has no permission.
     *
     * @param $permission
     *
     * @return bool
     */
    public function cannot($permission)
    {
        return !$this->can($permission);
    }

    /**
     * Check if user is administrator.
     *
     * @return mixed
     */
    public function isAdministrator()
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
    public function isRole($role)
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
    public function inRoles(array $roles = [])
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
    public function visible(array $roles = [])
    {
        if (empty($roles)) {
            return true;
        }

        $roles = array_column($roles, 'slug');

        return $this->inRoles($roles) || $this->isAdministrator();
    }

    /**
     * Detach models from the relationship.
     *
     * @return void
     */
    protected static function bootHasPermissions()
    {
        static::deleting(function ($model) {
            if (!method_exists(self::class, 'trashed')) {
                $model->roles()->detach();
            }
        });
    }
}
