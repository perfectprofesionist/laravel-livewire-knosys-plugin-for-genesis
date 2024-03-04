@props(['rlink'])

<div style="margin-left: 20px;">
	@if($rlink['children']['Icon'] != null)
	<x-icon :icon="$rlink['children']['Icon']" />
	@endif
	@if($rlink['children']['DocumentFile'] != null)
	<x-document-file :docFile="$rlink['children']['DocumentFile']"/>
	@endif
</div>