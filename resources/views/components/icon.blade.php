@props(['icon']) 
@if($icon["fields"]["IconFile"]!=null)
<img Itemid="{{$icon['fields']['IconFile']}}"
	src="data:image/png;base64,{{($icon['Icon'])}}"
	alt="{{$icon['fields']['AltText']}}"
	width="14px"
	height="auto" />
@endif
