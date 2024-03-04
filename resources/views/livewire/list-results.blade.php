<div>
	<div class="tabs-process">
		<div class="tab-inner">
			<div class="searrch-outer">				
				<x-search-field :convId="$result['conversationid']" :token="$result['search']"/>
			</div>
			@if($result != null && $result['size'] > 0)
				<div class="showing-result2">
				 <span>Showing: {{count($result['showResults'])}} Article(s), {{count($result['processflows'])}} Process, {{count($result['Links'])+count($result['Files'])}} Related of {{$result['headers']['Total']}} items.</span>
					<select id="result-sort" wire:change="resultSort($event.target.value)">
						<option value="lh-rel">Relevance (Low-High)</option>
						<option value="hl-rel">Relevance (High-Low)</option>
						<option value="date">By date posted</option>
						<option value="default" selected hidden>Order by:</option>
					</select>
				</div>
				<div id ="pinned-content">
					@if($result['showResults'] != null)		
						
						@foreach($result['showResults'] as $key=>$pin)
							@if($pin['isPinned'] == true)	
								@switch($pin['detail']['itemType'])
									@case("NewsArticle")
										<div class="search-result-content" id="result-content" style= "order:{{$key}}">
											<div class="tab-left ">
												<h5>{{$pin["fields"]["ArticleTitle"]}}</h5>
												<p>{{substr($pin["fields"]["Summary"],15)."..."}}</p>
											</div>
											<div class="tab-right">
												<button class="view-btn disable" onclick="showloaderv();" wire:click="viewItem('{{$pin['detail']['id']}}','articles')">View</button>																								
											</div>							
										</div>
									@break
									@case("Document")
										<div class="search-result-content" id="result-content" style= "order:{{$key}}">
											<div class="tab-left">
												<h5>{{$pin["fields"]["DocumentTitle"]}}</h5>
											</div>
											<div class="tab-right">
												<button class="view-btn disable" onclick="showloaderv();" wire:click="viewItem('{{$pin['detail']['id']}}','documents')">View</button>																								
											</div>										
										</div>
									@break
								@endswitch	
							@endif														
						@endforeach 
					@endif
				</div>
				<div id ="result-content-tab">															       
					@if($result['showResults'] != null)																		       
						@foreach($result['showResults'] as $key=>$showResult)
							@if($showResult['isPinned'] != true)
								@switch($showResult['detail']['itemType'])
									@case("NewsArticle")
										<div class="search-result-content" id="result-content" style= "order:{{$key}}">
											<div class="tab-left ">
												<h5>{{$showResult["fields"]["ArticleTitle"]}}</h5>
												<p>{{substr($showResult["fields"]["Summary"],15)."..."}}</p>
											</div>
											<div class="tab-right">
												<button class="view-btn disable" onclick="showloaderv();" wire:click="viewItem('{{$showResult['detail']['id']}}','articles')">View</button>																						
											</div>							
										</div>
									@break
									@case("Document")
										<div class="search-result-content" id="result-content" style= "order:{{$key}}">
											<div class="tab-left">
												<h5>{{$showResult["fields"]["DocumentTitle"]}}</h5>
											</div>
											<div class="tab-right">
												<button class="view-btn disable" onclick="showloaderv();" wire:click="viewItem('{{$showResult['detail']['id']}}','documents')"> View</button>																						
											</div>								
										</div>
									@break
								@endswitch	
							@endif													
						@endforeach 				               				
					@endif	
				</div>
			@endif
			@if($result['size'] == 0 || $result['showResults']==null)
			<span style="margin-left: 20px;">{{$settings['display']['LBL_NO_RES']['value']}}</span>
			@endif
			
		</div>
	</div>
		
	<div class="tab-process2">
		@if(isset($item))
			<div class="tab-inner">
				<div class="tab-content">
					@if(isset($item))
						@switch($item['detail']['itemType'])
							@case("NewsArticle")

								<livewire:article-view key={{now()}} :article="$item" :userId="$userId" :conversationid="$conversationid"/>
							@break
							@case("Document")
								<livewire:document-view key={{now()}} :document="$item" :userId="$userId" :from="''" :conversationid="$conversationid"/>
							@break
						@endswitch
					@endif
				</div>
			</div>
		@endif
	</div>
</div>