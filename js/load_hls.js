
// Global variables.
var rootPath = "http://localhost/hlswebpage/";
var videoPath = rootPath + "videos/";
var targetPath = videoPath + "m3u8_files/";
var url = "";


// Check if the video can be played directly or has to be transcoded first.
function playVideo() 
{
	var v = document.getElementById("videoDropdown");
	var inputVideo = v.options[v.selectedIndex].value;
	var videoName = inputVideo.split(".");
	var filetype = videoName[videoName.length-1];
	videoName = videoName[0];

	// Need HLS to be supported by the browser.
	if (Hls.isSupported()) 
	{
	    if (filetype === "m3u8")
	    {
	    	console.log("Starting playback of: " + videoName + ".m3u8");
	    	url = targetPath + inputVideo;
			startPlayback();
		}
		else if (fileExists(inputVideo, videoName))
		{
	    	console.log("Starting playback of: " + videoName + ".m3u8");
			startPlayback();
		}
		else
		{	
			// Need to convert the file to HLS format.
			var xmlhttp = new XMLHttpRequest();

			xmlhttp.onreadystatechange = function() 
				{
		            if (this.readyState == 4 && this.status == 200)
		            {
		                console.log(this.responseText);
		            	// Start playback when the transcoding is complete.
		            	url = targetPath + videoName + ".m3u8";
		            	startPlayback();
	        		}
				};

			console.log("Requesting transcoding...");
			// Send request to server to convert the file.
			xmlhttp.open("GET", rootPath + "videos/encoder.php?i=" + inputVideo + "&n=" + videoName, true);
	    	xmlhttp.send();
		}
	}
}

// Start playback of video, requires 'controls' in the HTML tag to interact with.
function startPlayback() 
{
	var video = document.getElementById('video');

	var hls = new Hls();

	// Bind video element to HLS element.
    hls.attachMedia(video);

    // MEDIA_ATTACHED event is fired by hls object once MediaSource is ready
    hls.on(Hls.Events.MEDIA_ATTACHED, function()
    {
    	hls.loadSource(url);	
    	hls.on(Hls.Events.MANIFEST_PARSED, function(event, data)
    		{
    			//console.log(hls.media);
    			//console.log("Data: " + data);
    		});
    });

    // Catch and display errors.
  	hls.on(Hls.Events.ERROR, function (event, data)
  	{
	    var errorType = data.type;
	    var errorDetails = data.details;
	    var errorFatal = data.fatal;
	    console.error("Error type: " + errorType);
	    console.error("Error details: " + errorDetails);
	    console.error("Error fatal: " + errorFatal);
  	});
}
 
// Check if the input file has already been transcoded and stored.
function fileExists(inputVideo, videoName)
{
	console.log("Unsupported format of: " + inputVideo);
	var xmlhttp = new XMLHttpRequest();

	console.log("Checking if transcoded video exists...");
	xmlhttp.open("GET", targetPath + videoName + ".m3u8", false);
	xmlhttp.send();
	if (xmlhttp.readyState == 4 && xmlhttp.status == 200)
	{
		console.log("Transcoded video found in storage");
    	url = targetPath + videoName + ".m3u8";
		return true;
	}
	else
	{
		return false;
	}

}

// Remove all temporarily stored, transcoded video files.
function purgeVideos() 
{
	var xmlhttp = new XMLHttpRequest();

	xmlhttp.onreadystatechange = function() 
		{
            if (this.readyState == 4 && this.status == 200)
            {
                console.log("Removed all transcoded video files");
    		}
		};

	xmlhttp.open("GET", targetPath + "purge_videos.php", true);
	xmlhttp.send();
}

// Create a mock playlist to fool the videoplayer into thinking the video
// currently in play is as long as the original video.
function createMockPlaylist() 
{

	var v = document.getElementById("videoDropdown");
	var inputVideo = v.options[v.selectedIndex].value;
	var videoName = inputVideo.split(".");
	var filetype = videoName[videoName.length-1];
	videoName = videoName[0];

	var xmlhttp = new XMLHttpRequest();

	xmlhttp.onreadystatechange = function() 
		{
            if (this.readyState == 4 && this.status == 200)
            {
 				console.log("Mock playlist created");
    		}
		};

	xmlhttp.open("GET", rootPath + "videos/create_mock_playlist.php?i=" + inputVideo + "&n=" + videoName, true);
	xmlhttp.send();
}
