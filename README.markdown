XHProf GUI
==========

This is a fork of [https://github.com/preinheimer/xhprof](https://github.com/preinheimer/xhprof) with some improvements, see below:

- Call graph is built using SVG powered by [Graphviz](https://graphviz.org/), which runs a lot faster then PNG images, allows text search and quick zoom and pan.
- Text search on SVG
- Quick zoom and pan
- Allow expanding the graph by clicking the functions
- Improved algorithm to include all children of selected function
- Moved the code to generate the extension to a [separate repo](https://github.com/marcelovani/xhprof_extension), keeping only code for GUI in this repo.
- Moved third party code and using CDN versions instead.
- Using .env files for configuration instead of php files.
- Using Docker to run the GUI, reducing dependencies and making setup easier.
- Some code refactoring and UI improvements.
- Added Behat tests

Xhprof GUI
![](https://raw.githubusercontent.com/marcelovani/xhprof/graphviz/docs/Gui.png)

Calgraph SVG
![](https://raw.githubusercontent.com/marcelovani/xhprof/graphviz/docs/SVG.png)

Expanded/Filtered functions
![](https://raw.githubusercontent.com/marcelovani/xhprof/graphviz/docs/Expand.png)

Requirements
------------

[Docker desktop](https://www.docker.com/products/docker-desktop/)

Installation
-------------

- Create a copy of .env.example as .env i.e. `cp .env.example .env`
- Configure anything you want, make sure the Project port used is available in your system.
- Run `make build` This command will do the following:
  1. Start the Docker containers
  2. Install the [Xhprof extension](https://github.com/marcelovani/xhprof_extension) and dependencies
  3. Install the database
- To stop the containers, run `make stop`. To start again, run `make start`.
- To open the GUI on your browser, run `make open`

Viewing traces
--------------
There are 4 ways of populating and viewing traces:
1. Run 'make db-dummy-data' to create some dummy traces and test the UI. Run `make db-drop` to clear the database.
2. Open the [Sample url](http://127.0.0.1:8000/examples/sample.php)
3. Add an include for external/header.php and external/footer.php on your own PHP scripts
4. Download Xhprof traces to your local computer and upload to the folder `docker/traces`. The traces will not appear automatically on the GUI, but you can open the trace on your browser using this pattern `http://127.0.0.1:8000/graphviz/?url=/api/file/%3Frun=1234`, where 1234 refers to a file named `docker/traces/1234.xhprof`

Testing
-------
Run `make test`

Todo
----
- Rewrite the code to use OOP with reusable classes
- Allow the GUI to be added to any PHP project using Composer
- Add a new button on the UI to allow importing Dumps from .xhprof files
- Add a button on the UI to allow filtering by parent functions or child functions (current)
- Bug fixes
  Add colors to lines too
- When filtering by child, show connected parents for current function
- When filtering by parent, show connected children for current function. This will allow to continue clicking and moving backwards if needed.
- Check why these two filters result in same graph>
    - http://127.0.0.1:8000/graphviz/?url=/api/db/%3Frun=5824ff778a7c8%26links=1%26show_internal=%26func=template_preprocess_page
    - http://127.0.0.1:8000/graphviz/?url=/api/db/%3Frun=5824ff778a7c8%26links=1%26show_internal=%26func=menu_tree_page_data
