XHProf UI
=========

This is a graphical front end designed to store and present the profiling information provided by the Facebook created XHProf profiling tool.

- Debug features
- Add screenshots to this readme
- Mention algo to filter out in the first place, and avoid working with lots of data
TODO
- Work on todos
- Make graph colorful based on calls to functions.
- Add colors to lines too
- When filtering by child, show connected parents for current function
- When filtering by parent, show connected children for current function. This will allow to continue clicking and moving backwards if needed.
- Why these two filters result in same graph>
  - http://127.0.0.1:8000/graphviz/?url=/api/db/%3Frun=5824ff778a7c8%26links=1%26show_internal=%26func=template_preprocess_page
  - http://127.0.0.1:8000/graphviz/?url=/api/db/%3Frun=5824ff778a7c8%26links=1%26show_internal=%26func=menu_tree_page_data
  - 

Related Tools
-------------

* [XHGui](https://github.com/perftools/xhgui) - Uses MongoDB as a backend (rather than MySQL)


Project Includes
----------------

* It includes a header.php document you can use with PHP's 
  auto\_prepend\_file directive. It sets up profiling by initilizing a few variables, and settting register_shutdown_function with the footer. Once started profiles are done 
  when requested (?\_profile=1), or randomly. Profiled pages display a link to 
  their profile results at the bottom of the page (this can be disabled on a 
  blacklist based for specific documents. e.g. pages generating XML, images, 
  etc.).
* For tips on including header.php on an nginx + php-fpm install take a look at: http://www.justincarmony.com/blog/2012/04/23/php-fpm-nginx-php_value-and-multiple-values/
* The GUI is a bit prettier (Thanks to Graham Slater)
* It uses a MySQL backend, the database schema is stored in xhprof\_runs.php 
* There's a frontend to view different runs, compare runs to the same url, etc.

Key Features
-------------

* Listing 25, 50 most recent runs
* Display most expensive (cpu), longest running, or highest memory usage runs 
  for the day
* It introduces the concept of "Similar" URLs. Consider:
  * http://news.example.com/?story=23
  * http://news.example.com/?story=25
  While the URLs are different, the PHP code execution path is likely identical,
  by tweaking the method in xhprof\_runs.php you can help the frontend be aware
  that these urls are identical.
* Highcharts is used to graph stats over requests for an 
  easy heads up display.

Requirements
------------

Besides a simple PHP running on your favourite web server you will also need following packages:

* php5-xhprof
* php5-mysql
* graphviz (uses `dot` to generate callgraphs)

Installation
-------------

* Install your favourite mix of PHP and web server
* Install MySQL server
* Clone the project to some folder
* Map the sub folder `xhprof_html` to be accessible over HTTP
* Move `xhprof_lib/config.sample.php` to `xhprof_lib/config.php`
* Edit `xhprof_lib/config.php`
 * Update the SQL server configuration
 * Update the URL of the service (should point to `xhprof_html` over HTTP)
 * Update the `dot_binary` configuration - otherwise no call graphs!
 * Update the `controlIPs` variable to enable access.
  * For a development machine you can set this to `false` to disable IP checks.
* Import the DB schema (it is just 1 table)
 * See the SQL at [xhprof_runs.php](https://github.com/toomasr/xhprof/blob/master/xhprof_lib/utils/xhprof_runs.php#L109)
* Add a PHP configuration to enable the profiling
 * If using Apache you can edit your virtual host configuration
 * Add `php_admin_value auto_prepend_file "/path/to/xhprof/external/header.php"`
* Visit http://your-server/xhprof/xhprof_html/ and be amazed!
 * To get profiler information showing up there visit your page with a `GET` variable `_profile=1`.
 * For example `http://localhost/?_profile=1`

We Are Working On
-----------------

* The aggregation functionality is ignored completely
* The code is... a mess. Deadlines do that to you, we're working on it
* The default table schema isn't indexed all the places it needs to be
* Easier ways to diff URLs
