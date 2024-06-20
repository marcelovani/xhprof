<?php
/**
 * @file Request params.
 */

namespace Xhprof\Request;

use Xhprof\Controller\Callgraph;

/**
 * @class Params.
 */
class Params
{
    private $valid_params = array( // run id param
        // The API url.
        'url' => array(XHPROF_URL_PARAM, ''),
        // The run id.
        'run' => array(XHPROF_STRING_PARAM, ''),
        // source/namespace/type of run
        'source' => array(XHPROF_STRING_PARAM, 'xhprof'),
        // the focus function, if it is set, only directly
        // parents/children functions of it will be shown.
        'func' => array(XHPROF_STRING_PARAM, ''),
        // image type, can be 'jpg', 'gif', 'ps', 'png'
        'type' => array(XHPROF_STRING_PARAM, 'png'),
        // only functions whose exclusive time over the total time
        // is larger than this threshold will be shown.
        // default is 0.01.
        'threshold' => array(XHPROF_FLOAT_PARAM, 0.01),
        // Show internal PHP functions
        'show_internal' => array(XHPROF_BOOL_PARAM, 'false'),
        // Turn on extra features to allow debugging.
        'debug' => array(XHPROF_BOOL_PARAM, 'false'),
        // Show links.
        'links' => array(XHPROF_BOOL_PARAM, 'true'),
        // whether to show critical_path
        'critical' => array(XHPROF_BOOL_PARAM, 'true'),
        // first run in diff mode.
        'run1' => array(XHPROF_STRING_PARAM, ''),
        // second run in diff mode.
        'run2' => array(XHPROF_STRING_PARAM, '')
    );

    private $params = [
        'type' => 'svg',
        'show_internal' => 0,
        'threshold' => .01,
    ];

    /**
     * Getter for params.
     *
     * @param $param
     * @return int|void
     */
    public function get($param) {
        if (isset($this->params[$param])) {
            return $this->params[$param];
        }
    }

    /**
     * Getter for all params
     *
     * @return array
     *   The list of params.
     */
    public function getAll() {
        return $this->params;
    }

    /**
     * Constructor
     *
     * @param array $valid_params
     *  List of valid params. This will override default params.
     * @return array
     *  The list of values.
     */
    public function __construct($valid_params = [])
    {
        if (!empty($valid_params)) {
            $this->valid_params = $valid_params;
        }
        $this->init($this->valid_params);

        // if invalid value specified for threshold, then use the default
//        if ($this->params['threshold'] < 0 || $this->params['threshold'] > 1) {
//            $this->params['threshold'] = .01;
//        }
//
//        if (empty($this->params['show_internal'])) {
//            $this->params['show_internal'] = 0;
//        }

        // if invalid value specified for type, use the default
        $callgraph = new Callgraph();
        if (!array_key_exists($this->params['type'], $callgraph->getLegalImageTypes())) {
            $this->params['type'] = $this->params['type'][1]; // default image type.
        }

        return $this;
    }

    /**
     * Helper to provide the function to handle the param based on param type.
     *
     * @param int $type
     *   The param type.
     * @return string
     *   The function name.
     */
    private function getParamFunction($type)
    {
        switch ($type) {
            case XHPROF_STRING_PARAM:
                $function = 'xhprof_get_string_param';
                break;

            case XHPROF_UINT_PARAM:
                $function = 'xhprof_get_uint_param';
                break;

            case XHPROF_FLOAT_PARAM:
                $function = 'xhprof_get_float_param';
                break;

            case XHPROF_BOOL_PARAM:
                $function = 'xhprof_get_bool_param';
                break;

            case XHPROF_URL_PARAM:
                $function = 'xhprof_get_url_param';
                break;

            default:
                xhprof_error("Invalid param type passed to getParamFunction(): " . $type);
                exit();
        }

        return $function;
    }

    /**
     * Internal helper function used by various
     * xhprof_get_param* flavors for various
     * types of parameters.
     *
     * @param string   name of the URL query string param
     *
     * @author Kannan
     */
    private function getParamHelper($param)
    {
        $val = null;
        if (isset($_GET[$param]))
            $val = $_GET[$param];
        else if (isset($_POST[$param])) {
            $val = $_POST[$param];
        }
        return $val;
    }

    /**
     * Initialize params from URL query string. The function
     * creates globals variables for each of the params
     * and if the URL query string doesn't specify a particular
     * param initializes them with the corresponding default
     * value specified in the input.
     *
     * @params array $params An array whose keys are the names
     *                       of URL params who value needs to
     *                       be retrieved from the URL query
     *                       string. PHP globals are created
     *                       with these names. The value is
     *                       itself an array with 2-elems (the
     *                       param type, and its default value).
     *                       If a param is not specified in the
     *                       query string the default value is
     *                       used.
     * @author Kannan
     */
    public function init($params)
    {
        // Make sure the 'url' param is the last item in the list.
        // The 'url' param contains encoded urls for the APIS and will override default params.
        if (isset($params['url'])) {
            $param_url = $params['url'];
            unset($params['url']);
            $params['url'] = $param_url;
        }

        // Create variables specified in $params keys, init defaults.
        foreach ($params as $name => $v) {
            $type = $v[0];
            $default = $v[1];

            $val = $this->getParamHelper($name);

            // Get the function name to be used to get values.
            $function = $this->getParamFunction($type);

            // Call function.
            $value = call_user_func($function, $val, $default);
//var_dump($name . ' ' . $value);
            $this->params[$name] = $value;

            // Parse arguments from encoded url.
            if ($name == 'url') {
                $parts = parse_url($value);
                if (!empty($parts['query'])) {
                    parse_str($parts['query'], $items);
                    if (!empty($items)) {
                        foreach ($items as $item => $item_value) {
                            // Check if the argument is valid.
                            if (isset($params[$item])) {
                                $type = $params[$item][0];

                                // Get the function name to be used to get values.
                                $function = $this->getParamFunction($type);

                                // Call function.
                                $value = call_user_func($function, $item_value);

                                $this->params[$item] = $value;
                            }
                        }
                    }
                }
            }
        }
    }
}
