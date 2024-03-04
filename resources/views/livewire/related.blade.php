<div>
	@if($result != null && $result['size'] > 0 && (!empty($result['Links'] || !empty($result['Files']))))
	<div class="tab-inner">
		<div class="result-content">
			<div class="summary-option">
				<ul>
					@if(!empty($result['Links']))
						@foreach($result['Links'] as $link)
						<li><div id='{{$link["detail"]["id"]}}'><a target="_blank" href="{{$link['fields']['URL']}}">{{$link["fields"]["LinkTitle"]}}</a></div>
							<div class="related-btns">
								<button class="add-chat-btn" onClick="sendLink('{{$link['fields']['URL']}}')">Add to Chat</button>
								<button class="copy-url-btn" onClick="copyText('{{$link['detail']['id']}}','link')">Copy URL</button>
							</div>
						</li>
						@endforeach
					@endif
					@if(!empty($result['Files']))
						@foreach($result['Files'] as $file)
						<li>
							<x-file :file="$file"/>
							<div class="related-btns">
								<x-email-button :docid="$file['detail']['id']" :ext="$file['fields']['FileName_FileExtension']"/>
							</div>
						</li>
						@endforeach
					@endif
				</ul>
			</div>
		</div>
	</div>
	@endif		
	@if( $result['size'] == 0 || ($result['Links']==null && $result['Files']==null))
	    	<div class="tab-inner no-record2">
	    		<span style="margin-left: 20px;">{{$settings['display']['LBL_NO_RES']['value']}}</span>
	    	</div>
    	@endif 
	<div wire:key="{{ rand() }}">
        @if(session()->has('success'))
        	<x-flash-alert :success="session('success')" />
        @elseif(session()->has('error'))
        	<x-flash-error :error="session('error')" />
        @endif
	</div>
</div>
