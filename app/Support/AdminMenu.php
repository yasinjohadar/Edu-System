<?php

namespace App\Support;

use Illuminate\Support\Facades\Auth;

class AdminMenu
{
    public static function canSeeItem(array $item): bool
    {
        $user = Auth::user();

        if (! $user) {
            return false;
        }

        if (! empty($item['children'])) {
            return count(self::filterItems($item['children'])) > 0;
        }

        if (! empty($item['permission'])) {
            return $user->can($item['permission']);
        }

        return ! empty($item['route']);
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     * @return array<int, array<string, mixed>>
     */
    public static function filterItems(array $items): array
    {
        $visible = [];

        foreach ($items as $item) {
            if (! self::canSeeItem($item)) {
                continue;
            }

            if (! empty($item['children'])) {
                $item['children'] = self::filterItems($item['children']);
            }

            $visible[] = $item;
        }

        return $visible;
    }

    /**
     * @param  array<int, array<string, mixed>>  $children
     */
    public static function hasActiveChild(array $children): bool
    {
        foreach ($children as $child) {
            if (! empty($child['children']) && self::hasActiveChild($child['children'])) {
                return true;
            }

            if (! empty($child['route']) && self::isActive($child['route'])) {
                return true;
            }
        }

        return false;
    }

    public static function isActive(string $routeName): bool
    {
        $candidates = [$routeName];

        if (str_starts_with($routeName, 'admin.')) {
            $candidates[] = substr($routeName, 6);
        } else {
            $candidates[] = 'admin.'.$routeName;
        }

        foreach (array_unique($candidates) as $name) {
            if (request()->routeIs($name)) {
                return true;
            }

            if (str_ends_with($name, '.index')) {
                $prefix = substr($name, 0, -strlen('.index'));

                if (request()->routeIs($prefix.'.*')) {
                    return true;
                }
            }
        }

        return false;
    }
}
