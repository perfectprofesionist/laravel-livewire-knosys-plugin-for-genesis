<div>
    <div class="tab-search search-result back-btn3">
    
    	<div class="tab-left">
            &nbsp;
    		<!--button id="bckbtn" class="back-btn{{$from}}">back</button-->
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
        </p>
        @if($document["children"] != null)
        <x-doc-children :item="$document" />
        @endif
        <x-data-img/>
        <x-tags :tags="$document['meta']['tags']" />
    </div>
</div>