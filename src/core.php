<?php

require_once 'classes/Task.php';
require_once 'classes/TaskTable.php';

$task = new Task;
$allTasks = $task->getAllTasks();