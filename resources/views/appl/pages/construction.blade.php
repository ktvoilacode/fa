@extends('layouts.app')

@section('content')
<div class="bg-white p-4">
    <img class="float-left p-2" src="{{ asset('images/general/effort.png')}}" style="width:5em;" />
    <h1 class="mt-3 mb-4"> Under Construction</h1>
</div>

<script src="https://cdn.webrtc-experiment.com/MediaStreamRecorder.js"> </script>
<script>
var mediaConstraints = {
    audio: true,
    video: true
};


navigator.getUserMedia(mediaConstraints, onMediaSuccess, onMediaError);

function onMediaSuccess(stream) {
    var mediaRecorder = new MediaStreamRecorder(stream);
    mediaRecorder.mimeType = 'video/webm';
    mediaRecorder.canvas = {
    width: 320,
    height: 240
};
    mediaRecorder.videoWidth  = 320;
	mediaRecorder.videoHeight = 240;
    
    mediaRecorder.ondataavailable = function (blob) {
        // POST/PUT "Blob" using FormData/XHR2
        //var blobURL = URL.createObjectURL(blob);
        mediaRecorder.save();
        //document.write('<a href="' + blobURL + '">' + blobURL + '</a>');
    };
    mediaRecorder.start(5000);
}

function onMediaError(e) {
    console.error('media error', e);
}
</script>


@endsection
