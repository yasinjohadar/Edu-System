<?php

namespace App\Support;

class Permissions
{
    /**
     * @return array<int, string>
     */
    public static function all(): array
    {
        $names = [];

        foreach (config('permissions.groups', []) as $group) {
            foreach ($group['permissions'] ?? [] as $name => $label) {
                $names[] = $name;
            }
        }

        return array_values(array_unique($names));
    }

    /**
     * @return array<string, string>
     */
    public static function grouped(): array
    {
        $grouped = [];

        foreach (config('permissions.groups', []) as $groupKey => $group) {
            $grouped[$group['label'] ?? $groupKey] = $group['permissions'] ?? [];
        }

        return $grouped;
    }

    /**
     * @return array<int, string>
     */
    public static function forRole(string $role): array
    {
        return config("permissions.roles.{$role}", []);
    }

    /**
     * @return array<string, string>
     */
    public static function labels(): array
    {
        $labels = [];

        foreach (config('permissions.groups', []) as $group) {
            foreach ($group['permissions'] ?? [] as $name => $label) {
                $labels[$name] = $label;
            }
        }

        return $labels;
    }

    /**
     * @param  iterable<string, mixed>|null  $existingNames  Permission names that exist in DB
     * @return array<int, array{key: string, label: string, permissions: array<int, array{name: string, label: string}>}>
     */
    public static function groupedForPicker(?iterable $existingNames = null): array
    {
        $existing = null;

        if ($existingNames !== null) {
            $existing = [];
            foreach ($existingNames as $name) {
                $existing[$name] = true;
            }
        }

        $groups = [];
        $known = [];

        foreach (config('permissions.groups', []) as $key => $group) {
            $permissions = [];

            foreach ($group['permissions'] ?? [] as $name => $label) {
                if ($existing !== null && ! isset($existing[$name])) {
                    continue;
                }

                $permissions[] = ['name' => $name, 'label' => $label];
                $known[$name] = true;
            }

            if ($permissions === []) {
                continue;
            }

            $groups[] = [
                'key' => $key,
                'label' => $group['label'] ?? $key,
                'permissions' => $permissions,
            ];
        }

        if ($existing !== null) {
            $orphans = [];

            foreach (array_keys($existing) as $name) {
                if (isset($known[$name])) {
                    continue;
                }

                $orphans[] = ['name' => $name, 'label' => $name];
            }

            if ($orphans !== []) {
                usort($orphans, fn (array $a, array $b) => strcmp($a['name'], $b['name']));

                $groups[] = [
                    'key' => 'other',
                    'label' => 'صلاحيات أخرى',
                    'permissions' => $orphans,
                ];
            }
        }

        return $groups;
    }
}
