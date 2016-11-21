# xprof

## Features
- Callgraph is a SVG, which is much faster than a large image, it allows searching text on the page
- You can choose the rendering Engine and the Output format i.e. plain
- You can exclude PHP native functions
- Ajust the threshold to have more details or focus on the most expensive functions
- 3D viewer provides a virtual representation of the functions. You can rotate, pan, zoom the model or move a cube. It works great on tablets.
- If you have LeapMotion you can control the model using your hands in the air.

xhprof
![](https://raw.githubusercontent.com/marcelovani/xhprof/master/xhprof_html/img/xhprof.png)

Callgraph SVG
![](https://raw.githubusercontent.com/marcelovani/xhprof/master/xhprof_html/img/callgraph.png)

## Roadmap
- Add labels and arrows on 3D viewer.
- VR support for Google Cardboard or similar.

## Requirements

- PHP 5.6
- Xhprof lib http://php.net/manual/en/book.xhprof.php
- Node https://nodejs.org/en/

## Installation

1. Go into xhprof_html folder
2. Run install
```cd xhprof_html; npm install```
3. Follow the installation steps from xhprof/INSTALL https://github.com/marcelovani/xhprof/blob/master/INSTALL
4. Create /config.php (copy of /config.sample.php), set your DB credentials
5. Run PHP to start serving, then open the address below in the browser
```cd xhprof_html; php -S 127.0.0.1:8000```
6. Add xhprof to your code. See xhprof_html/sample.php

## Dev Installation

1. Run install
```xhprof_html/$ npm install```

## For developers only

Its possible to switch the theme by passing the theme name in the url i.e. theme=demo-dot-progress
The theme name is the php filename in the themes folder.

