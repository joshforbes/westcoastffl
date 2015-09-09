<?php

// Checks if the provided route name matches the current route
// used to apply classes to navigation menu
// use when you only want to match a single route
function isActiveRoute($route, $output = "active")
{
    if (Route::currentRouteName() == $route)
    {
        return $output;
    }

    return '';
}