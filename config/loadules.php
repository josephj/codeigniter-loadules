<?php
/**
 * This config defines default setting for loading JavaScript and CSS files.
 */

if ( ! defined("BASEPATH"))
{
    exit("No direct script access allowed");
}

$config = array(

    /**
     * CSS/JS seed URLs.
     */
    "seed" => array(
        "css" => STATIC_PRURL . "combo/?g=css",
        "js"  => STATIC_PRURL . "combo/?g=js",
    ),

    /**
     * Combo base URL.
     */
    "base" => STATIC_PRURL . "combo/?f=",

    /**
     * Combo separator.
     */
    "separator" => ",",

    /**
     * YUI default configuration.
     */
    "yui" => array(
        "config" => array(
            "lang" => "en-US",
        ),
        "callback" => "(new Y.ModuleManager()).startAll();",
    ),

    /**
     * Loads CSS and JavaScript metadata.
     */
    "metadata" => include APPPATH . "config/metadata.php",
);

?>
