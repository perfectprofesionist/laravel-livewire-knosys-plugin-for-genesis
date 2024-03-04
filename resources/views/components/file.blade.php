@props(['file'])
<div >
	<a  href="data:application/vnd.ms-outlook;base64,{{$file['File']}}" target="_blank"  download="{{$file['fields']['FileName_OriginalFilename']}}">{!!$file["fields"]["FileTitle"]!!}</a>
</div>