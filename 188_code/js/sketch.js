//webkitURL is deprecated but nevertheless
URL = window.URL || window.webkitURL;

var gumStream; 						//stream from getUserMedia()
var rec; 							//Recorder.js object
var input; 							//MediaStreamAudioSourceNode we'll be recording

// shim for AudioContext when it's not avb. 
var AudioContext = window.AudioContext || window.webkitAudioContext;
var audioContext //audio context to help us record

var recordButton = document.getElementById("recordButton");
var stopButton = document.getElementById("stopButton");
var pauseButton = document.getElementById("pauseButton");

//add events to those 2 buttons
recordButton.addEventListener("click", startRecording);
stopButton.addEventListener("click", stopRecording);
pauseButton.addEventListener("click", pauseRecording);

var sec = 0;

function pad ( val ) { return val > 9 ? val : "0" + val; }
var func;
function timerstart(){
	func = setInterval( function(){
		$("#seconds").html(pad(++sec%60));
		$("#minutes").html(pad(parseInt(sec/60,10)));
	}, 1000);
}

function myStopFunction() {
	clearInterval(func);
}

// function myClearFunction(){
// 	myStopFunction();
// 	$("#seconds").html(pad(00));
// 	$("#minutes").html(pad(00));
// 	sec = 0;
// }

function startRecording() {
	console.log("recordButton clicked");

	/*
		Simple constraints object, for more advanced audio features see
		https://addpipe.com/blog/audio-constraints-getusermedia/
	*/
    
    var constraints = { audio: true, video:false }

 	/*
    	Disable the record button until we get a success or fail from getUserMedia() 
	*/

	recordButton.disabled = true;
	stopButton.disabled = false;
	pauseButton.disabled = false

	/*
    	We're using the standard promise based getUserMedia() 
    	https://developer.mozilla.org/en-US/docs/Web/API/MediaDevices/getUserMedia
	*/

	navigator.mediaDevices.getUserMedia(constraints).then(function(stream) {
		console.log("getUserMedia() success, stream created, initializing Recorder.js ...");

		/*
			create an audio context after getUserMedia is called
			sampleRate might change after getUserMedia is called, like it does on macOS when recording through AirPods
			the sampleRate defaults to the one set in your OS for your playback device

		*/
		audioContext = new AudioContext();

		//update the format 
		//document.getElementById("formats").innerHTML="Format: 1 channel pcm @ "+audioContext.sampleRate/1000+"kHz"

		/*  assign to gumStream for later use  */
		gumStream = stream;
		
		/* use the stream */
		input = audioContext.createMediaStreamSource(stream);

		/* 
			Create the Recorder object and configure to record mono sound (1 channel)
			Recording 2 channels  will double the file size
		*/
		rec = new Recorder(input,{numChannels:1})

		//start the recording process
		rec.record()

		timerstart();

		console.log("Recording started");

	}).catch(function(err) {
	  	//enable the record button if getUserMedia() fails
    	recordButton.disabled = false;
    	stopButton.disabled = true;
    	pauseButton.disabled = true
	});
}

function pauseRecording(){
	console.log("pauseButton clicked rec.recording=",rec.recording );
	if (rec.recording){
		//pause
		rec.stop();
		pauseButton.innerHTML="Resume";
		myStopFunction();
	}else{
		//resume
		rec.record()
		pauseButton.innerHTML="Pause";
		timerstart();

	}
}

function stopRecording() {
	console.log("stopButton clicked");

	//disable the stop button, enable the record too allow for new recordings
	stopButton.disabled = true;
	recordButton.disabled = false;
	pauseButton.disabled = true;

	//reset button just in case the recording is stopped while paused
	pauseButton.innerHTML="Pause";
	
	//tell the recorder to stop the recording
	rec.stop();

	myStopFunction();
	//stop microphone access
	gumStream.getAudioTracks()[0].stop();

	//create the wav blob and pass it on to createDownloadLink
	rec.exportWAV(createDownloadLink);
}

function beforeSend() {
      document.getElementById("audioError").innerHTML = "<span style='color: green;'>Please wait...</span>";
}

function createDownloadLink(blob) {
	
	var url 	= URL.createObjectURL(blob);
	var au 		= document.createElement('audio');
	var li 		= document.createElement('li');
	var user_id = document.getElementById('UserID').value;
	//var link = document.createElement('a');

	//name of .wav file to use during upload and download (without extendion)
	var filename = new Date().toISOString()+'.wav';

	//add controls to the <audio> element
	au.controls = true;
	au.src = url;

	//save to disk link
	//link.href = url;
	//link.download = filename+".wav"; //download forces the browser to donwload the file using the  filename
	//link.innerHTML = "Save to disk";

	//add the new audio element to li
	li.appendChild(au);
	
	
	//add the save to disk link to li
	//li.appendChild(link);
	
	//upload link
	var upload = document.createElement('button');
	var att = document.createAttribute("type");
	var att1 = document.createAttribute("class");
	att.value = "button";
	att1.value = "pbtn";
	upload.setAttributeNode(att);
	upload.setAttributeNode(att1);
	upload.href="#";
	upload.innerHTML = "Upload";
	upload.addEventListener("click", function(event){
		  var xhr=new XMLHttpRequest();
		  xhr.onload=function(e) {
		      if(this.readyState === 4) {
		          var obj = JSON.parse(e.target.responseText);
		          if(obj.error == true){
		          	document.getElementById("audioError").innerHTML = "<span style='color: green;'>"+obj.message+"</span>";
		          }else{
		          	document.getElementById("audioError").innerHTML = "<span style='color: red;'>"+obj.message+"</span>";
		          }
		      }
		  };
		  var fd=new FormData();
		  fd.append("audio_data",blob, filename);
		  fd.append("user_id", user_id);
		  xhr.open("POST","upload_audio.php",true);
		  beforeSend();
		  xhr.send(fd);
	})
	li.appendChild(upload)//add the upload link to li

	//add the li element to the ol
	console.log(recordingsList);
}