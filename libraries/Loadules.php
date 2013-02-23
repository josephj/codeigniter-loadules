<?php
if ( ! defined("BASEPATH"))
{
    exit("No direct script access allowed");
}
/**
 * Read the static config to generate inline YUI config.
 *
 *    $this->load->library("static_loader");
 *    $this->static_module->set("common/_masthead", "home/_notification");
 *    $data["loader_html"] = $this->static_module->load();
 *
 * @class Loadules
 */
class Loadules
{
    public $js_modules;
    public $css_files;
    private $_config;

    public function __construct()
    {
        $this->config =& load_class("Config");
        $this->CI =& get_instance();
    }

    private function _splitUrl($base, $files = array(), $separator = ",", $max_length = 1024)
    {
        $results = array();
        $url = $base . implode(",", $files);

        // Checks if url exceeds max length.
        if (strlen($url) <= $max_length)
        {
            $results[] = $url;
            return $results;
        }

        // Adds files one by one to check if it exceeds max length.
        $extra = array();
        $len = count($files);
        for ($i = 0; $i < $len; $i++)
        {
            $items[] = $css_files[$i];
            $url = $base . implode($separator, $items);

            // Oops! It exceeds max length!!
            if (strlen($url) > $max_url_length)
            {
                // Remove the last file from $items array.
                $file = array_pop($items);

                // We can make sure current url doesn't exceed.
                // Adds it to $results array.
                $results[] = $base . implode($separator, $items);

                // Creates the $items array for next iteration.
                $items = [];
                if ($file) {
                    $items[] = $file;
                }
            }
        }

        // Adds the rest files to $result array.
        if (count($items))
        {
            $url = $base + implode($separator, $items);
            $results[] = $url;
        }

        return $results;
    }

    /**
     * Get the loader HTML.
     *
     *     echo $this->static_loader->load();
     *
     * @return {String} The loader HTML code.
     */
    public function load()
    {
        $css_files = $this->css_files;
        $js_modules = $this->js_modules;
        $config = $this->_config;
        $base = $config["base"];
        $max_length = (isset($config["max_url_length"])) ? $config["max_url_length"] : 1024;
        $html = array();

        // Splits CSS URLs according to max_length.
        $urls = $this->_splitUrl($base, $css_files, $config["separator"], $max_length);
        if (
            array_key_exists("seed", $config) &&
            array_key_exists("css", $config["seed"])
        )
        {
            $seed_url = $config["seed"]["css"];
            $urls = array_merge((array)$seed_url, $urls);
        }
        foreach ($urls as $href)
        {
            $html[] = "<link type=\"text/css\" rel=\"stylesheet\" href=\"$href\">";
        }

        // Generates script tags.
        if (
            array_key_exists("seed", $config) &&
            array_key_exists("js", $config["seed"])
        )
        {
            $html[] = sprintf('<script src="%s"></script>', $config["seed"]["js"]);
        }
        if (count($js_modules))
        {
            $html[] = sprintf(
                '<script>YUI(%s).use("module-manager", "%s", function (Y) {%s});</script>',
                json_encode($config["yui"]["config"]),
                implode('","', $js_modules),
                $config["yui"]["callback"]
            );
        }
        return implode("\n", $html);
    }

    /**
     * Sets modules you want use.
     *
     *    $this->static_module->set("common/_masthead", "home/_notification");
     *
     * @method set
     * @param $modules {Array} The used module list.
     * @public
     */
    public function set($modules)
    {
        if (gettype(func_get_arg(0)) === "string")
        {
            $modules = func_get_args();
        }

        // Loads configuration file - config/loadules.php.
        $this->config->load("loadules", TRUE);
        $config = $this->config->item("loadules");
        $this->_config = $config;
        $css_files = array();
        $js_modules = array();

        foreach ($modules as $module)
        {
            // Finds all dependent CSS files.
            if ( ! array_key_exists($module, $config["metadata"]["css"]))
            {
                continue;
            }
            $files = $config["metadata"]["css"][$module];
            $css_files = array_merge($css_files, (array)$files);

            // Checks if JavaScript module exists.
            if (isset($config["metadata"]["js"][$module]))
            {
                $js_modules[] = $module;
            }
        }
        $css_files = array_unique($css_files);
        $this->css_files  = $css_files;
        $this->js_modules = $js_modules;
    }
}
/* End of file Loadules.php */
?>
