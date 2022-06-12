<?php

if (! function_exists('app')) {
    function app(string $abstract)
    {
        return \Illuminate\Container\Container::getInstance()->make($abstract);
    }
}
