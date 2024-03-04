@props(['docFile'])
<a  style="margin-left: 20px;" href="data:application/pdf;base64,{{$docFile['docFile']}}" target="_SEJ" rel="noreferrer">{{$docFile['fields']['DocumentFileTitle']}}</a>