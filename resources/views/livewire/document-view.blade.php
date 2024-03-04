<div>
    <div class="tab-search search-result back-btn3">
    
    	<div class="tab-left">
    		<button id="bckbtn" class="back-btn{{$from}}">back</button>
    	</div>
    </div>
    <div id="data">
        <h3 style="margin-left: 20px;">{{$document["detail"]["title"]}}</h3>
        <p style="margin-left: 20px;">
        	<span style="color:#d4d4d4; font-size:100%;">Posted {{$document["detail"]["liveDate"]}}</span><br>
        	<!-- @if($document["feedback"]["count"] !=0)
        	<x-stars :value="$document['feedback']['average']" :color="'#FDCC0D'" />
        	@endif 
        	({{$document["feedback"]["count"]}} reviews ) -->
            <x-rating-create  :articleId="isset($document)?$document['detail']['id']:''" :conversationid="$conversationid" />
        </p>
        @if($document["children"] != null)
        <x-doc-children :item="$document" />
        @endif
        {{--<x-data-img/>--}}
        <x-tags :tags="$document['meta']['tags']" />
        <x-feedback-create
        	:articleId="isset($document)?$document['detail']['id']:''" :conversationid="$conversationid" :sections="$sections" />
        {{--<x-feedback-view :feedback="$document['feedback']" />--}}
        <div wire:key="{{ rand() }}">
            @if(session()->has('success'))
            	<x-flash-alert :success="session('success')" />
            @elseif(session()->has('error'))
            	<x-flash-error :error="session('error')" />
            @endif
        </div>
    </div>
</div>
<script>
    $(document).on("click",".rdrctbck",function(){
        $("#bckbtn").click(); 
        $("#tabs-1").attr("onclick","openCity(event,'Results');");
    });
   $(document).on("click","#bckbtn",function(){
        $("#tabs-1").attr("onclick","openCity(event,'Results');");
        $("#tabs-1").addClass('custom-tab');
        $("#tabs-2").removeClass('custom-tab');
   })
    </script>