@props(['tags'])
@if($tags != null)
<div style="margin: 10px 5%; display: block; ">
	<div id="view-tags" name="view-tags">
    	<p id="item-tags" style="display: inline; line-height: 2.5;">Tags :
    	@if($tags != null)
    		@foreach($tags as $tag)
    		<span class ="tagsStyle">
    			{{ucfirst($tag['value'])}}
    		</span>	
    		@endforeach
    	@endif
    	</p>
	</div>
</div>
@endif
