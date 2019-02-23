<?php

$inputVideo = $_REQUEST["i"];
$videoName = $_REQUEST["n"];

// First pass is just for scanning through the file.
//$pass1 = "ffmpeg -i " . $inputVideo . " -c:v libx264 -preset faster -s 640x480 -r 30 -crf 21 -maxrate 600k -bufsize 600k -b:a 64k -ar 44100 -g 60 -keyint_min 60 -sc_threshold 0 -f HLS -hls_time 2 -hls_list_size 0 -hls_flags single_file -strict -2 NUL && \\";

// FFmpeg Transcoding settings.
$pass2 = "ffmpeg -i " . $inputVideo . " -c:v libx264 -preset veryfast -s 640x480 -r 30 -crf 21 -maxrate 600k -bufsize 600k -b:a 64k -ar 44100 -g 60 -keyint_min 60 -sc_threshold 0 -f HLS -hls_time 2 -hls_list_size 0 -strict -2 /var/www/html/hlswebpage/videos/m3u8_files/" . $videoName . ".m3u8 > /dev/null 2>&1 &";

// -hls_flags single_file

echo "Starting transcoding\n\n";

echo $pass2;	
shell_exec($pass2);

// Hold the script until the first sign of a playlist.
do 
{
	if (file_exists("m3u8_files/" . $videoName . ".m3u8"))
	{
		echo "\n\nReady to start playback\n";
		break;
	}
	sleep(1);
} while (true);


?>