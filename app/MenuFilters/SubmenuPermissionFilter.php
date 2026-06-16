<?php

namespace App\MenuFilters;

use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;

class SubmenuPermissionFilter implements FilterInterface
{
    public function transform($item)
    {
        if (!isset($item['submenu'])) {
            return $item;
        }

        $item['submenu'] = collect($item['submenu'])->map(function ($subitem) {
            // Si el subitem también tiene submenu, lo procesamos recursivamente
            if (isset($subitem['submenu'])) {
                $subitem = $this->transform($subitem);
            }

            // Si tiene permiso o no tiene 'can', lo dejamos pasar
            if (!isset($subitem['can']) || auth()->user()->can($subitem['can'])) {
                return $subitem;
            }

            return null;
        })->filter()->values()->toArray();

        // Si después de filtrar no quedan hijos visibles, ocultamos el padre
        return count($item['submenu']) > 0 ? $item : null;
    }
}
