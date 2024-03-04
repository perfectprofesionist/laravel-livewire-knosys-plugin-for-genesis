@props(['para'])
<div id="{{$para['detail']['id']}}" style="margin: 0px 20px 0px 20px;">
	<p >{!!$para["fields"]["Text"]!!}</p>
</div>
<div style="margin-left: 20px;">
    <button class="send-chat-btn" onClick="sendId('{{$para['detail']['id']}}')">Send to chat</button>
    <button class="copy-snipet-btn" onClick="copyToClipboard('{{$para['detail']['id']}}')">Copy	Snippet</button>
</div>
