<?php

namespace Encore\Admin\Auth\Database;

trait HasPermissions
{
    /**
     * Get all permissions of user.
     *
     * @return mixed
     */
    public function allPermissions()
    {
        return $this->rolePermissions()->flatten()->merge($this->permissions);
    }

    /**
     * @param $menu
     * @param $allPermissions
     * @return bool
     */
    public function canMenu($menu, $allPermissions): bool
    {
        if (config('admin.check_menus') === true) {
            if ($this->isAdministrator() || isset($menu['children']) || url()->isValidUrl($menu['uri'])) {
                return true;
            }

            foreach ($allPermissions as $permissions) {
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
    public function canAccess($route): bool
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

        return $this->rolePermissions()->flatten()->pluck('slug')->contains($ability);
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
        return $this->slugIsRole('administrator');
    }

    /**
     * Get user role id
     *
     * @return mixed
     */
    public function roleIds()
    {
        return $this->roles->pluck('id');
    }

    /**
     * Get user role slug
     *
     * @return mixed
     */
    public function roleSlugs()
    {
        return $this->roles->pluck('slug');
    }

    /**
     * Get user role slug
     *
     * @return mixed
     */
    public function rolePermissions()
    {
        return $this->roles->pluck('permissions');
    }

    /**
     * Check if user is $role.
     *
     * @param string $role_id
     * @return mixed
     */
    public function idIsRole(string $role_id): bool
    {
        return $this->roleIds()->contains($role_id);
    }

    /**
     * Check if user is $role.
     *
     * @param string $role_slug
     * @return mixed
     */
    public function slugIsRole(string $role_slug): bool
    {
        return $this->roleSlugs()->contains($role_slug);
    }

    /**
     * Check whether the user's role id is in the role.
     *
     * @param array $role_ids
     * @return mixed
     */
    public function idInRoles(array $role_ids = []): bool
    {
        return $this->roleIds()->intersect($role_ids)->isNotEmpty();
    }

    /**
     * Check whether the user's role slug is in the role.
     *
     * @param array $role_slugs
     * @return mixed
     */
    public function slugInRoles(array $role_slugs = []): bool
    {
        return $this->roleSlugs()->intersect($role_slugs)->isNotEmpty();
    }

    /**
     * If visible for roles.
     *
     * @param array $role_slugs
     * @return bool
     */
    public function visible(array $role_slugs = []): bool
    {
        if (empty($role_slugs)) {
            return true;
        }

        $roles = array_column($role_slugs, 'slug');

        return $this->slugInRoles($roles) || $this->isAdministrator();
    }
}
