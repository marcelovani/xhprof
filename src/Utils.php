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

    /**
     * Helper for report url.
     *
     * @return string
     */
    public function getReportUrl()
    {
        $uri = $this->ParseEndpointUri();
        $url = $this->buildQueryString($uri);

        return '/?' . $url;
    }

    public function getFilter($filterName)
    {
        if (isset($_GET[$filterName]))
        {
            if ($_GET[$filterName] == "None")
            {
                $serverFilter = null;
                setcookie($filterName, null, 0);
            }else
            {
                setcookie($filterName, $_GET[$filterName], (time() + 60 * 60));
                $serverFilter = $_GET[$filterName];
            }
        }elseif(isset($_COOKIE[$filterName]))
        {
            $serverFilter = $_COOKIE[$filterName];
        }else
        {
            $serverFilter = null;
        }
        return $serverFilter;
    }

    /**
     * Helper to get the current path.
     * @return mixed
     */
    public function getRequestPath()
    {
        $parsed_url = parse_url($_SERVER['REQUEST_URI']);

        return $parsed_url['path'];
    }

    /**
     * Helper to rebuild url
     *
     * @param $parsed_url
     * @param $parsed_qs
     * @return string
     */
    public function buildUrl($parsed_qs)
    {
        $utils = new Utils();
        $base_uri = $utils->parseUri();

        $qs = $this->buildQueryString($parsed_qs);
        $qs = str_replace('&', '%26', $qs);
        $url = $base_uri['path'] . '?' . $base_uri['api']['path'] . '%3F' . $qs;

        return $url;
    }

    /**
     * Builds a query string from uri parts.
     *
     * @param array $parts
     *   The query string arguments
     * @return string
     *   The query string
     */
    public function buildQueryString($parts) {
        $qs = '';
        foreach ($parts as $k => $v) {
            $qs .= sprintf('%s=%s&', $k, $v);
        }
        $qs = trim($qs, '&');

        return $qs;
    }

    /**
     * Helper to parse the endpoint uri.
     *
     * @return array
     */
    public function ParseEndpointUri()
    {
        $parsed_uri = $this->parseUri();
        $args = explode('%26', $parsed_uri['api']['query']);

        // Build an array with arguments.
        $result = [];
        foreach ($args as $param) {
            $kv = explode('=', $param);
            if (isset($kv[1])) {
                $result[$kv[0]] = $kv[1];
            }
        }

        return $result;
    }

    /**
     * Splits the parts of the base url and the API url.
     *
     * @return array
     */
    public function parseUri() {
        $parsed_uri = parse_url($_SERVER['REQUEST_URI']);
        $qs = $parsed_uri['query'];
        $endpoint_args = explode('%3F', $qs);

        return array_merge($parsed_uri, [
            'api' => [
                'path' => $endpoint_args[0],
                'query' => $endpoint_args[1],
            ]
        ]);
    }

}