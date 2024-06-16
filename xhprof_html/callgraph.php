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

/**
 *
 * A callgraph generator for XHProf.
 *
 * * This file is part of the UI/reporting component,
 *   used for viewing results of XHProf runs from a
 *   browser.
 *
 * Modification History:
 *  02/15/2008 - cjiang  - The first version of callgraph visualizer
 *                         based on Graphviz's DOT tool.
 *
 * @author Changhao Jiang (cjiang@facebook.com)
 */

require_once dirname(dirname(__FILE__)) . '/xhprof_lib/defaults.php';
require_once XHPROF_CONFIG;
require_once XHPROF_LIB_ROOT . '/params.php';

if (false !== $controlIPs && !in_array($_SERVER['REMOTE_ADDR'], $controlIPs))
{
  die("You do not have permission to view this page.");
}

include_once XHPROF_LIB_ROOT . '/display/xhprof.php';

$xhprof_runs_impl = new XHProfRuns_Default();

if (!empty($run)) {
  // single run call graph image generation
  xhprof_render_image($xhprof_runs_impl, $run, $type,
                      $threshold, $func, $source, $critical);
} else {
  // diff report call graph image generation
  xhprof_render_diff_image($xhprof_runs_impl, $run1, $run2,
                           $type, $threshold, $source);
}
