<?php

if (!function_exists('setup')) {

    /**
     * description
     *
     * @param
     * @return
     */
    function setup($key, $default = null)
    {
        return \App\Models\Setting::getByKey($key, $default);
    }
}
