<div class="tabs-process">
	@if($result != null && $result['size'] > 0 && !empty($result['processflows']))
        <div class="tab-inner">
            <div class="tab-process"> 
                @foreach($result['processflows'] as $flow)        
                    <div class="search-result-content" id="result-content">
                        <div class="tab-left">  

                            <h5>{{$flow['name']}}</h5>
                        </div>
                        <div class="tab-right">
                            <a class="view-process-btn disable" wire:key="flow-{{ $flow['processFlowId'] }}" wire:click="viewProcess('{{$flow['processFlowId']}}')" style ="width:100px; display: flex; padding-left: 22px; ">Launch</a>
                        </div>
                    </div>
                @endforeach
            </div>
            @if(isset($processflow))
                @if(isset($item))
                    @if($item != null)
                        <div class="tab-process3">
                            @switch($item['detail']['itemType'])
                                @case("ProcessFlowExternal")
                                     <div class="tab-left newone-left">  
                                        <button id="bckbtn" class="back-btn">back</button>  
                                    </div>
                                    <livewire:flow-chart  wire:key="item-{{ $item['itemId'] }}"  :processflow="$item" :convId="$convId"/>
                                @break
                                @case("Document")
                                    <livewire:process-document  wire:key="item-{{ $item['itemId'] }}" :document="$item" :userId="$userId" :from="'-pcs'"/>
                                @break
                            @endswitch
                        </div>
                    @endif
                @else
                    <div class="tab-process2"> 
                        <div class="tab-left newone-left">  
                            <button id="bckbtn" class="back-btn">back</button>  
                        </div>
                        
                        <livewire:flow-chart wire:key="item-{{$processflow['itemId']}}" :processflow="$processflow" :convId="$convId"/>
                    </div>
                @endif
            @else
                @if(isset($item))
                    @if($item != null) 
                        <div class="tab-process3">  
                            @if(isset($item['detail']['itemType']))
                            @switch($item['detail']['itemType'])
                    			@case("ProcessFlowExternal")
                    				<livewire:flow-chart wire:key="item-{{ $item['itemId'] }}" :processflow="$item" :convId="$convId"/>
                    			@break
                    			@case("Document")
                    				<livewire:process-document  wire:key="item-{{ $item['itemId'] }}" :document="$item" :userId="$userId" :from="'-pcs'"/>
                    			@break
                    		@endswitch
                            @endif
                        </div>
                    @endif
                @endif
            @endif
        </div>
    @endif
	@if( $result['size'] == 0 || $result['processflows']==null)
	<div class="tab-inner no-record2">
		<span style="margin-left: 20px;">{{$settings['display']['LBL_NO_RES']['value']}}</span>
	</div>
	@endif 
</div>

<script>
    $(document).on("click","#bckbtn",function(){
        $(".tab-process3").addClass('process-hide');
    });
</script>