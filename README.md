# xprof

## What is it?
This is a fork of xhprof, and a proof of concept that takes PHP traces of executions and displays the trace into a 3D world to allow us to see the execution from inside out.
This can help developers to identify bottlenecks and optimise the code.

## Features
- Callgraph is a SVG, which is much faster than a large image, it allows searching text on the page
- You can choose the rendering Engine and the Output format i.e. plain
- You can exclude PHP native functions
- Ajust the threshold to have more details or focus on the most expensive functions
- 3D viewer provides a virtual representation of the functions. You can rotate, pan, zoom the model or move a cube. It works great on tablets.
- If you have LeapMotion you can control the model using your hands in the air.

## Background
Bad code can lead to high load on servers and unexpected behaviour. We don’t do bad code intentionally but when using open source, its common to find yourself using a bad code that someone else wrote, or even some code that would work fine on its own, but when integrated with other modules, can have some unexpected behaviour.
One example of this, is a web page that is visible only for Admins but for some reason is executing code that should only be visible to end users. That means we are making the computer work for no reason.
I work with PHP and any time I want to find bottlenecks, I collect execution traces using Xhprof, which I can view how many times a given function has been called or how much time was spent on that function alone. 

xhprof
![](https://raw.githubusercontent.com/marcelovani/xhprof/master/xhprof_html/img/xhprof.png)

Callgraph SVG
![](https://raw.githubusercontent.com/marcelovani/xhprof/master/xhprof_html/img/callgraph.png)

## The Concept
The idea is to convert the traces like you see on xhprof into a 3D world that will contain objects that can be rotated and zoomed.
So we could represent functions by squares and position them using x, y, z coordinates. When a function calls another function, we would draw an arrow from one square to the other. The squares could have different sizes depending on how much time was spent, and different colours to represent hot many times that same function is called.
Objects and arrays could be represented as spheres inside that function, so when you double click the box, you would see the spheres, that would contain another small spheres that represent the properties and/or methods. The spheres could have different sizes to represent the size of the object/array. We should be able to double click on these spheres and go deeper inside it, i.e. when an array contains various objects.
We could have arrows to move step by step on the trace, like debuggers do. We could then see the 3D model changing as we move forwards or backwards.

## Is this a tool for PHP only?
As a proof of concept, I am using data from Xhprof traces. But that data could come from anywhere, we could import json strings that contain complex data structures, etc.

## Roadmap
- Add labels and arrows on 3D viewer.
- This could be implemented in Unity allowing users to view it on Google Cardboard, Oculus or Hololens.
- We could even control it with our hands using LeapMotion or Kinetics.
- This tool could be used as integration with development IDEs like PHPStorm, Eclipse, NetBeans, Visual Studio, Xcode, etc.

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

## For developers only

Its possible to switch the theme by passing the theme name in the url i.e. theme=demo-dot-progress
The theme name is the php filename in the themes folder.

