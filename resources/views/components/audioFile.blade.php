@props(['audioFile'])
<div>
<audio controls>
  <source src="data:{{$audioFile['fields']['AudioFileName_MimeType']}};base64,{{$file['audioFile']}}" type="{{$audioFile['fields']['AudioFileName_MimeType']}}"> 
Your browser does not support the audio element. {{$audioFile['fields']['AudioFileName_OriginalFilename']}}
 </audio>
</div>