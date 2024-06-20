<?php
/**
 * @file Utils
 */

namespace Xhprof;

/**
 * Utils class.
 */
class Utils
{
    /**
     * The goal of this function is to accept the URL for a resource, and return a "simplified" version
     * thereof. Similar URLs should become identical. Consider:
     * http://example.org/stories.php?id=2323
     * http://example.org/stories.php?id=2324
     * Under most setups these two URLs, while unique, will have an identical execution path, thus it's
     * worthwhile to consider them as identical. The script will store both the original URL and the
     * Simplified URL for display and comparison purposes. A good simplified URL would be:
     * http://example.org/stories.php?id=
     *
     * @param string $url The URL to be simplified
     * @return string The simplified URL
     */
    public function urlSimilartor($url)
    {
        //This is an example
        $url = preg_replace("!\d{4}!", "", $url);

        // For domain-specific configuration, you can use Apache setEnv xhprof_urlSimilartor_include [some_php_file]
        if ($similartorinclude = getenv('xhprof_urlSimilartor_include')) {
            require_once($similartorinclude);
        }

        $url = preg_replace("![?&]_profile=\d!", "", $url);
        return $url;
    }
}