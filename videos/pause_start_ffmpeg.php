<?php

$start = $_REQUEST["s"];
$pause = $_REQUEST["p"];

$threads = shell_exec("pgrep ffmpeg");
//echo $threads;

foreach ($threads as $thread) {
	echo $thread;
}

//if ($start == 1)
//{
//	shell_exec("kill -s SIGCONT " . $threads);
//}
//if ($pause == 1)
//{
//	shell_exec("kill -s SIGSTOP " . $threads);
//}

?>