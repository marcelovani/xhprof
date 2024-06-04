<?php
list($raw_data, $a) = $xhprof_runs_impl->get_run($run, $source, $description);
echo '<pre>';
print_r($raw_data);
