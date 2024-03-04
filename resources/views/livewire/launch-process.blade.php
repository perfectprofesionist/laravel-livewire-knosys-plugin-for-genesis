<div class="search-result-content search-result-contentINNER">
    <div class="tab-process">
    	<div class="tab-left"> 
            <h5>{{$flow['processFlow']['name']}}</h5>
        </div>
    	 <div class="tab-right">
            <a class="view-process-btn disable" wire:click="viewProcess('{{$flow['processFlow']['processFlowId']}}')" style ="width:100px">Launch</a>
        </div>
    </div>
	   @if(isset($processflow))
        <div>
            <?php $newcls = ''; ?>
            @if(isset($item))
                @if($item != null)
                    <?php $newcls = 'svg-nwcls'; ?>
                @endif 
            @endif
            <div class="tab-process4 {{$newcls}}">
                <livewire:flow-chart :processflow="$processflow" :convId="$convId"/>
            </div>

            @if(isset($item))
                @if($item != null)
                    <div class="tab-process5">
                        @switch($item['detail']['itemType'])
                            @case("ProcessFlowExternal")
                                <livewire:flow-chart wire:key="item-{{ $item['itemId'] }}" :processflow="$item" :convId="$convId"/>
                            @break
                            @case("Document")
                                <livewire:process-document wire:key="item-{{ $item['itemId'] }}"  :document="$item" :userId="$userId" :from="'-pcs'"/>
                            @break
                        @endswitch
                    </div>
                @endif
           
            @endif
            </div>
        @else
            @if(isset($item))
                @if($item != null)
                <div class="tab-process5">
                    @switch($item['detail']['itemType'])
                        @case("ProcessFlowExternal")
                            <livewire:flow-chart wire:key="item-{{ $item['itemId'] }}" :processflow="$item" :convId="$convId"/>
                        @break
                        @case("Document")
                            <livewire:process-document wire:key="item-{{ $item['itemId'] }}"  :document="$item" :userId="$userId" :from="'-pcs'"/>
                        @break
                    @endswitch
                </div>
                @endif
            @endif
        @endif
        
        <script>   
        document.addEventListener("process-section", () => {
             console.log("process-section");
			$("#loaderimg").addClass("d-none");
            $(".tab-process").addClass("d-none");
    		$('#Process').find(".tab-process4").addClass("process-show");
    		$('#Process').find(".tab-process").addClass("process-hide");
            $(".wizzard").addClass("process-hide");
            $("#tabs-1").removeClass('custom-tab');
            $("#tabs-2").removeAttr('onclick');
           // $("#tabs-1").removeAttr('onclick');
            $("#tabs-2").addClass('custom-tab');
            $("#tabs-1").addClass('rdrctbck');
            $(".result-content").addClass('d-none');
        });
        </script>
</div>