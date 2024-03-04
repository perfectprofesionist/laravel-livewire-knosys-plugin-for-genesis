<div class="tabing-sec">
	@if(!$sessionExpired)
	<div class="tabingsec-right">
	@if($isAdmin)
	<button type="button" class="tablink btn-link float-right settings-button2" style="min-width: 58px; "
		onclick="openCity(event,'settings')">
		<span class="bi bi-gear"></span>
	</button>
	@endif
	<button id="user_alerts" class="btn-link float-right tablink"  wire:click="getAlerts()" style ="display: inline-block; min-width: 58px;" aria-label="Open User Alert Panel" tabindex="0">
        <span id="alerts-icon" class="bi bi-exclamation-triangle"></span>    
	</button> 
	<button id="user_quiz" class="btn-link float-right tablink"  wire:click="getQuiz()" style ="display: inline-block; min-width: 58px;" aria-label="Open User Quiz Panel" tabindex="0">
      	<span id="quiz-icon" class="bi bi-trophy"></span>   
	</button> 
	</div>
	<x-alerts :alerts="$alerts" />
	<x-quiz :quiz="$quiz" />
	
	@csrf
	<div class="tab-list">

		<button id="tabs-1" class="iframe-btn-left tablink custom-tab"
			onclick="openCity(event,'Results')">Results</button>
		<button id="tabs-2" class="tablink iframe-btn-left" onclick="openCity(event,'Process')">Process</button>
		<button id="tabs-3" class="tablink iframe-btn-left" onclick="openCity(event,'Related')">Related</button>
	</div>
	<input type="hidden" id="convId"
			value="{{Session::get('conversationid')}}" />
	@if($isAdmin)
	<section id="settingsPage">
		<div id="settings" class="city" style="display: none">
			<livewire:settings-live :convId="$results['conversationid']"/>
		</div>
	</section>
	@endif
    <div id="Results" class="city">
    	<livewire:list-results key={{now()}} :result="$results" :settings="$settings"/>    
	    <div class="nav-btns2">
			@if(isset($results['headers']['Previous']))
				<button class="prev-btn-res float-left" wire:click="getPageResults('{{$results['headers']['Previous'][0]}}')">Previous</button>
			@endif
			@if(isset($results['headers']['Next']))
				<button class="next-btn-res float-right" wire:click="getPageResults('{{$results['headers']['Next'][0]}}')">Next</button>
			@endif
		</div>	    
    </div>
    <div id="Process" class="city" style="display: none">
		<livewire:process-flow key={{now()}} :result="$results"/>
	</div>
	<div id="Related" class="city" style="display: none">
		<livewire:related key={{now()}} :result="$results"/>
	</div>
	@else
		<a href='{{$settings["general"]["WEBHOOK_URL"]["value"]}}/?conversationid={{$results["conversationid"]}}'>{{$settings["display"]["ERR_RESPONSE_TIMEOUT"]["value"]}}</a>
	@endif
	
</div>