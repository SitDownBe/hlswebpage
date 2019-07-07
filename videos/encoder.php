<?php

// Script for calling FFmpeg to convert a video.

$inputVideo = $_REQUEST["i"];
$videoName = $_REQUEST["n"];
$startSegment = $_REQUEST["s"];
//$inputVideo = "aerial.mov";
//$videoName = "aerial";
//$startSegment = "0";

$segments = "m3u8_files/" . $videoName . "%01d.ts";
$playlist = "m3u8_files/NUL/" . $videoName . ".m3u8";
// Segment length must be 2 seconds.
$startTime = $startSegment * 2;
$ffmpegPath = "/home/workingpotato/ffmpeg-git-20190226-amd64-static/./ffmpeg";

// Call the system to start converting.
if (file_exists("m3u8_files/" . $videoName . $startSegment . ".ts") or file_exists("m3u8_files/" . $videoName . $startSegment . ".ts.tmp"))	
{
	echo "Segment is being converted, or exists but has not been loaded yet";
}
else
{
	// Hold the script until a playlist exists, which is created by another script.
	do
	{
		if (file_exists("m3u8_files/" . $videoName . ".m3u8"))
		{
			echo "Ready\n";
			break;
		}
		sleep(1);
	} while (true);

	// FFmpeg Transcoding settings.
	$ffmpeg = $ffmpegPath . " -i " . $inputVideo . " -ss " . $startTime . " -c:v libx264 -preset veryfast -s 640x480 -r 30 -g 60 -keyint_min 60 -sc_threshold 0 -f HLS -hls_time 2 -hls_list_size 0 -hls_flags temp_file -start_number " . $startSegment . " -hls_segment_filename " . $segments . " " . $playlist . " > /dev/null 2>&1 &";

	echo "Starting transcoding\n";
	echo $ffmpeg;
	//echo $ffmpeg2;
	shell_exec($ffmpeg);
	//shell_exec($ffmpeg2);
}

echo "\n";
?>
