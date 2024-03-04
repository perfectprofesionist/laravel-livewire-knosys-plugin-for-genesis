@props(['success'])
@if (isset($success))
	<div x-data="{show: true }"
		x-init="setTimeout(()=>show = false, 5000)"
		x-show="show"
		class="flash-alert success">
		<h6>{{$success}}</h6>
		<a class="close">&times;</a>
	</div>
@endif
