<?php

namespace App\Menu\Filters;

use Illuminate\Support\Facades\Auth;
use JeroenNoten\LaravelAdminLte\Menu\Filters\FilterInterface;

class AuthFilter implements FilterInterface
{
    /**
     * Transforms a menu item. Removes items that require authentication
     * when the user is not authenticated, or items that are guest-only
     * when the user is authenticated.
     *
     * @param  array  $item  A menu item
     * @return array The transformed menu item
     */
    public function transform($item)
    {
        // Check for 'auth-only' class - hide if not authenticated
        if (isset($item['classes']) && strpos($item['classes'], 'auth-only') !== false) {
            if (!Auth::check()) {
                $item['restricted'] = true;
            }
        }
        
        // Check for 'guest-only' gate - hide if authenticated
        if (isset($item['classes']) && strpos($item['classes'], 'guest-only') !== false) {
            if (Auth::check()) {
                $item['restricted'] = true;
            }
        }

        return $item;
    }
}
