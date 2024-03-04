@props(['videoFile'])
<div>
    <video width="400" controls>
        <source src="data:{{$videoFile['fields']['VideoFileName_MimeType']}};base64,{{$file['videoFile']}}" type="{{$videoFile['fields']['VideoFileName_MimeType']}}"> 
        Your browser does not support HTML video. {{$videoFile['fields']['VideoFileName_OriginalFilename']}}
    </video>
</div>