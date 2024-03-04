@props(['docid','ext'])
<input name="documentid" type="hidden" value="{{$docid.$ext}}">
<input name="conversationid" type="hidden" value="{{request('conversationid')}}">
<button class="email-btn" onClick="email('{{$docid.$ext}}','{{request('conversationid')}}')">Email</button>
