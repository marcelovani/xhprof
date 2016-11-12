<?php

function bar($x) {
  if ($x > 0) {
    bar($x - 1);
  }
}

function foo() {
  for ($idx = 0; $idx < 5; $idx++) {
    bar($idx);
    $x = strlen("abc");
    return $x;
  }
}

// Start profiling.
include '../includes/xhprof_start.php';

// run program
foo();

// End profiling.
include '../includes/xhprof_end.php';
