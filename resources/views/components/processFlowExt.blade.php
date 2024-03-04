@props(['flow'])

<div class="search-result-content">
	<div class="tab-left">                          
        <h5>{{$flow['processFlow']['name']}}</h5>
    </div>
	 <div class="tab-right">
        <a class="view-process-btn disable" wire:click="viewProcess('{{$flow['processFlow']['processFlowId']}}')" style ="width:300px">Launch Process Flow</a>
    </div>
</div>
