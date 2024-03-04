<div>
<div class="tab-search search-result">

	<div class="tab-left">
		<button class="back-btn">back</button>
	</div>
</div>
<div style="margin-left: 20px;">
<h3>{{$article["detail"]["title"]}}</h3>
	<div style="float:left;">
        @if($article["fields"]["ImageItemGuid"]!=null) 
        	<x-image :image="$article['image']"/>		
        @endif
    </div>
    <div style="line-height: 100%;font-size: small;">
        <span style="color:#d4d4d4; font-size:100%;">Posted {{$article["fields"]["Date"]}}</span><br>
    	<!-- @if($article["feedback"]["count"] !=0)
    	<x-stars :value="$article['feedback']['average']" :color="'#FDCC0D'" />
    	@endif  -->
    	<!-- ({{$article["feedback"]["count"]}} reviews ) -->
		<x-rating-create  :articleId="$article['detail']['id']" :conversationid="$conversationid"/>
    </div>
</div>
<div class="accordion-box">
	<button class="accordion">Summary</button>
	<div class="panel">
		<p id="snippet">{{$article["fields"]["Summary"]}}</p>
		<button class="copy-snipet-btn" onClick="copyToClipboard('snippet')">Copy
			Snippet</button>
	</div>
</div>
<div class="accordion-box">
	<button class="accordion">Details</button>
	<div class="panel">
		<div id="data">
			<div id="{{$article['detail']['id']}}">
    			<p>{!!$article["fields"]["Text"]!!}</p>
			</div>
			<x-data-img/>
		</div>
		<div class="accordin-chat-btn">
			<button class="send-chat-btn" onClick="sendId('{{$article['detail']['id']}}')">Send to chat</button>
			<button class="copy-snip-blue-btn" onClick="copyToClipboard('{{$article['detail']['id']}}')">Copy
				Snippet</button>
		</div>
	</div>
</div>
<x-tags :tags="$article['meta']['tags']" />
<x-feedback-create  :articleId="$article['detail']['id']" :conversationid="$conversationid" :sections="$sections"/>

{{--<x-feedback-view :feedback="$article['feedback']" />--}}
<div wire:key="{{ rand() }}">
    @if(session()->has('success'))
    	<x-flash-alert :success="session('success')" />
    @elseif(session()->has('error'))
    	<x-flash-error :error="session('error')" />
    @endif
</div>
</div>