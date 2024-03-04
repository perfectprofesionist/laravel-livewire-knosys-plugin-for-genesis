@props(['error'])
@if (isset($error))
	<div x-data="{show: true }"
		x-init="setTimeout(()=>show = false, 5000)"
		x-show="show"
		class="flash-alert error">
		<h6>{{$error}}</h6>
		<a class="close">&times;</a>
	</div>
@endif
