<?php
//  Copyright (c) 2009 Facebook
//
//  Licensed under the Apache License, Version 2.0 (the "License");
//  you may not use this file except in compliance with the License.
//  You may obtain a copy of the License at
//
//      http://www.apache.org/licenses/LICENSE-2.0
//
//  Unless required by applicable law or agreed to in writing, software
//  distributed under the License is distributed on an "AS IS" BASIS,
//  WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
//  See the License for the specific language governing permissions and
//  limitations under the License.
//

//
// This file contains various XHProf library (utility) functions.
// Do not add any display specific code here.
//

function xhprof_error($message)
{
    error_log($message);
}

/**
 * Type definitions for URL params
 */
define('XHPROF_STRING_PARAM', 1);
define('XHPROF_UINT_PARAM', 2);
define('XHPROF_FLOAT_PARAM', 3);
define('XHPROF_BOOL_PARAM', 4);
define('XHPROF_URL_PARAM', 5);


/**
 * Extracts value for string param $param from query
 * string. If param is not specified, return the
 * $default value.
 *
 */
function xhprof_get_url_param($val, $default = '')
{
    if (empty($val))
        return $default;

    return filter_var($val, FILTER_SANITIZE_URL);
}

/**
 * Extracts value for string param $param from query
 * string. If param is not specified, return the
 * $default value.
 *
 * @author Kannan
 */
function xhprof_get_string_param($val, $default = '')
{
    if (empty($val))
        return $default;

    return htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
}

/**
 * Extracts value for unsigned integer param $param from
 * query string. If param is not specified, return the
 * $default value.
 *
 * If value is not a valid unsigned integer, logs error
 * and returns null.
 *
 * @author Kannan
 */
function xhprof_get_uint_param($val, $default = 0)
{

    if (empty($val))
        $val = $default;

    // trim leading/trailing whitespace
    $val = trim($val);

    // if it only contains digits, then ok.
    if (ctype_digit($val)) {
        return filter_var($val, FILTER_SANITIZE_NUMBER_INT);
    }

    xhprof_error("$val must be an unsigned integer.");

    return null;
}

/**
 * Extracts value for a float param $param from
 * query string. If param is not specified, return
 * the $default value.
 *
 * If value is not a valid unsigned integer, logs error
 * and returns null.
 *
 * @author Kannan
 */
function xhprof_get_float_param($val, $default = 0)
{

    if (empty($val))
        $val = $default;

    // trim leading/trailing whitespace
    $val = trim($val);

    // TBD: confirm the value is indeed a float.
    if (true) // for now.
        return (float)$val;

    xhprof_error("$val must be a float.");

    return null;
}

/**
 * Extracts value for a boolean param $param from
 * query string. If param is not specified, return
 * the $default value.
 *
 * If value is not a valid unsigned integer, logs error
 * and returns null.
 *
 * @author Kannan
 */
function xhprof_get_bool_param($val, $default = false)
{

    if (empty($val))
        $val = $default;

    // trim leading/trailing whitespace
    $val = trim($val);

    switch (strtolower($val)) {
        case '0':
        case '1':
            $val = (bool)$val;
            break;

        case 'true':
        case 'on':
        case 'yes':
            $val = true;
            break;

        case 'false':
        case 'off':
        case 'no':
            $val = false;
            break;
        default:
            xhprof_error("$val must be a valid boolean string.");
            return null;
    }

    return (bool)$val;
}

