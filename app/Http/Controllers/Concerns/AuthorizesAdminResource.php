<?php

namespace App\Http\Controllers\Concerns;

trait AuthorizesAdminResource
{
  /**
   * @param  array<string, array<int, string>>  $extra  action => method names
   * @param  array<int, string>  $listMethods
   */
    protected function authorizeAdminResource(
        string $prefix,
        bool $withShow = true,
        array $extra = [],
        array $listMethods = ['index', 'show'],
    ): void {
        $listPerms = [
            "{$prefix}-list",
            "{$prefix}-create",
            "{$prefix}-edit",
            "{$prefix}-delete",
        ];

        if ($withShow) {
            $listPerms[] = "{$prefix}-show";
        }

        $this->middleware('permission:'.implode('|', $listPerms), ['only' => $withShow ? $listMethods : ['index']]);
        $this->middleware("permission:{$prefix}-create", ['only' => ['create', 'store']]);

        $editMethods = ['edit', 'update'];
        if (isset($extra['edit'])) {
            $editMethods = array_merge($editMethods, $extra['edit']);
        }
        $this->middleware("permission:{$prefix}-edit", ['only' => $editMethods]);

        $this->middleware("permission:{$prefix}-delete", ['only' => ['destroy']]);

        if ($withShow) {
            $showMethods = ['show'];
            if (isset($extra['show'])) {
                $showMethods = array_merge($showMethods, $extra['show']);
            }
            $this->middleware("permission:{$prefix}-show", ['only' => $showMethods]);
        }

        foreach ($extra as $permission => $methods) {
            if (in_array($permission, ['edit', 'show'], true)) {
                continue;
            }
            $this->middleware("permission:{$permission}", ['only' => $methods]);
        }
    }
}
