@props(['result'])
<div id="Process" class="city" style="display: none">
	<div class="tabs-process">
	@if($result != null && $result['size'] > 0)
		@livewire('process-flow', ['processflows'=>$result['processflows'],'settings'=>$settings, 'convId'=>$result['conversationid']])
	@endif
	@if( $result['size'] == 0 || $result['processflows']==null)
	<div class="tab-inner">
		<span style="margin-left: 20px;">{{$settings['display']['LBL_NO_RES']['value']}}</span>
	</div>
	@endif 
	</div>
</div>
