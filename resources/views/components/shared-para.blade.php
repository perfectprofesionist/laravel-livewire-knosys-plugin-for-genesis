@props(['para'])
<div class="accordion-box">
	<button type="button" class="accordion">{{$para["fields"]["ParagraphTitle"]}}</button>
	<div class="panel" >
		<div id="data">
			<p>{!!$para["fields"]["VisibleText"]!!}</p>
			<div id="{{$para['detail']['id']}}" >
    			{!!$para["fields"]["HiddenText"]!!}
			</div>
			
			<x-data-img/>
		</div>
		<div class="accordin-chat-btn">
			<button class="send-chat-btn" onClick="sendId('{{$para['detail']['id']}}')">Send to chat</button>
			<button class="copy-snip-blue-btn" onClick="copyToClipboard('{{$para['detail']['id']}}')">Copy
				Snippet</button>
		</div>
	</div>
</div>