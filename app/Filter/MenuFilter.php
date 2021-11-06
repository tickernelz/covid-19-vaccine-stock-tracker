<?php

namespace App\Filter;

use Auth;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;

class MenuFilter implements FilterInterface
{
    public function transform($item)
    {
        $user = Auth::user();
        if (isset($item['can']) && ! $user->hasAnyPermission($item['can'])) {
            $item['restricted'] = true;
        }

        return $item;
    }
}
