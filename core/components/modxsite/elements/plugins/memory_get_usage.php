<?php
if(!$modx->hasPermission('Debug'))return;

print '<br /><br /><hr />';

$memory = round(memory_get_usage()/1024/1024, 4).' Mb';

print "<div>Memory: {$memory}</div>";

$totalTime= (microtime(true) - $modx->startTime);
$queryTime= $modx->queryTime;
$queryTime= sprintf("%2.4f s", $queryTime);
$queries= isset ($modx->executedQueries) ? $modx->executedQueries : 0;
$totalTime= sprintf("%2.4f s", $totalTime);
$phpTime= $totalTime - $queryTime;
$phpTime= sprintf("%2.4f s", $phpTime);
print "<div>TotalTime: {$totalTime}</div>";
exit;