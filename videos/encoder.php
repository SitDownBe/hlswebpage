<?php

// Script for calling FFmpeg to convert a video.

$inputVideo = $_REQUEST["i"];
$videoName = $_REQUEST["n"];
//$inputVideo = "aerial.mov";
//$videoName = "aerial";

//$segments = "m3u8_files/" . $videoName . "%01d.ts";
$playlist = "m3u8_files/" . $videoName . ".m3u8";

// FFmpeg Transcoding settings.
$ffmpeg = "ffmpeg -i " . $inputVideo . " -c:v libx264 -preset veryfast -s 640x480 -r 30 -crf 21 -maxrate 600k -bufsize 600k -b:a 64k -ar 44100 -g 60 -keyint_min 60 -sc_threshold 0 -f HLS -hls_time 2 -hls_list_size 0 " . $playlist . " > /dev/null 2>&1 &";
// -hls_segment_filename " . $segments . "
// -hls_flags temp_file <--- can't get this to work.
// -hls_flags single_file
// -hls_segment_filename " . $segments . " 

echo "Starting transcoding\n\n";

echo $ffmpeg;	
shell_exec($ffmpeg);

// Hold the script until playlist is created.
do {
	if (file_exists($playlist))
	{
		echo "\n\nReady to start playback\n";
		break;
	}
	sleep(1);
} while (true);
?>
