@props(['image'])
<img style="margin-left: 20px;" Itemid="{{$image['fields']['ImageFile']}}"
	src="data:image/png;base64,{{($image['fields']['ImageSrc'])}}"
	alt="{{$image['fields']['AltText']}}"
	width="50px"
	height="auto" />
