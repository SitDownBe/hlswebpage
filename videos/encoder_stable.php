
<?php

// Get variables sent from client.
$inputVideo = $_REQUEST["i"];
$videoName = $_REQUEST["n"];
//$inputVideo = "aerial.mov";
//$videoName = "aerial";

$ffmpegPath = "/home/workingpotato/ffmpeg-git-20190226-amd64-static/./ffmpeg";

// OBS: 'live stream' fails when using '-hls_flags temp_file'.
$ffmpeg = $ffmpegPath . " -i " . $inputVideo . " -c:v libx264 -preset veryfast -s 640x480 -r 30 -g 60 -keyint_min 60 -sc_threshold 0 -f HLS -hls_time 2 -hls_list_size 0 m3u8_files/" . $videoName . ".m3u8 > /dev/null 2>&1 &";

echo "Starting transcoding\n";
echo $ffmpeg;
shell_exec($ffmpeg);
echo "\n";

// Hold the script until a playlist has been created.
do
{
	if (file_exists("m3u8_files/" . $videoName . ".m3u8"))
	{
		break;
	}
	sleep(1);
} while (true);


?>