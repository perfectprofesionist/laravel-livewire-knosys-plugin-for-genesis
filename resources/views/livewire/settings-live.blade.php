<div class="tab-inner">
	<div class="tab-search search-result search-outer-btn">
		<h3>Settings</h3>
		<button class="back-btn" onclick="openCity(event,'Results')">back</button>
	</div>
	<div class="result-content">
		<div class="summary-option">
			<button type="button" class="collapsible">Display</button>
			<div class="content" data-role="content" id="form-user-map-id">
			<form wire:submit.prevent="setenv('display')" method="POST" id="form-user-map" >
				@csrf
			<div class="labels accordian-table2">
				<div class="responsive-table">
				<table>
					@foreach(array_values($LoadedSetting['display']) as $label)
					<tr><td><label style="text-transform: capitalize;">{{$label['label']}}</label></td>
						<td><x-config-field :prop="$label" /></td>
					</tr>
					@endforeach
				</table>
				<x-form-edit :idForm="'form-user-map'" />
				</div>
			</div>
			<br>
			
			</form>
			<br><br>
			</div>

			<button type="button" class="collapsible">General</button>
			<div class="content" data-role="content" id="form-set-general-id">
				<form wire:submit.prevent="setenv('general')" method="POST" id="form-set-general">
					@csrf
				<div class="labels accordian-table2">
				<div class="responsive-table">
					<table>
					@foreach(array_values($LoadedSetting['general']) as $genSet)
						<tr><td><label style="text-transform: capitalize;"><span class="config-field-name">{{$genSet['label']}}:</span></label></td>
						<td><x-config-field :prop="$genSet" /></td>
						</tr>
					@endforeach
					</table>
					<x-form-edit :idForm="'form-set-general'" />
					</div>
					</div>
					<br>
					
					
				</form>
				<br>
					<br>
			</div>

			<button type="button" class="collapsible">Content Mapping</button>
			<div class="content" data-role="content" id="form-set-usermap-id">
				<form wire:submit.prevent="setenv('user')" method="POST" id="form-set-usermap">
					@csrf 
					<div class="labels rmv-hgt accordian-table2">
						<div class="responsive-table">
						<table>
							<tr>
							<td><label style="text-transform: capitalize;"><span class="config-field-name">Get customer context from:</span></label></td>
							<td>
								<select wire:model.defer="customer_context" id="edit-form" name="customer_context"
									class="border border-gray-400 p-2 w-full"
									style="width: 640px !important">
									<option class="non" value="{{$customer_context}}" selected disabled hidden>Please select</option>
									<option class="non" value="subject" size="80">Subject</option>
									<option class="non" value="product" size="80">Product</option>
									<option class="editable" value="custom_context" size="80">Custom Context</option>

								</select> <input
									wire:model.defer="customer_context" id="customer_context_input" class="editOption border border-gray-400 p-2 w-full"
									style="display: none; position: inherit; width: 640px;" placeholder="Enter custom value"></input><br>
							</td>
							<div>
								@error('customer_context') <span style="mfont-size: small; color: red">{{ $message }}</span> @enderror
							</div>
							</tr>
						</table>
						<x-form-edit :idForm="'form-set-usermap'" />
						</div>
						</div>
						
					
					
					

				</form>

			</div>

			<button type="button" class="collapsible">Knowledge IQ Connection</button>
			<div class="content" data-role="content" id="form-set-iq-id">
				<form wire:submit.prevent="setenv('kiq')" method="POST" id="form-set-iq">
					@csrf 
					<div class="labels accordian-table2">
						<div class="responsive-table">
					<table>
					@foreach(array_values($LoadedSetting['kiq']) as $kiSet)
						@if($kiSet['name'] == 'KNO_USER_TYPE')
							<tr><td><label style="text-transform: capitalize;"><span class="config-field-name">{{$kiSet['label']}}::</span></label></td>
							<td>
								<select wire:model.defer="{{$kiSet['name']}}" id="{{$kiSet['name']}}" name="{{$kiSet['name']}}"
								class="border border-gray-400 p-2 w-full"
								style="width: 640px">
									<option class="non" value="{{$kiSet['value']}}" selected disabled hidden>{{$kiSet['value']}}</option>
									<option class="non" value="public" size="80">Public</option>
									<option class="non" value="admin" size="80">Admin</option>	
								</select>	
							</td>
							</tr>
						@else	
							<tr>
								<td><label style="text-transform: capitalize;"><span class="config-field-name">{{$kiSet['label']}}::</span></label></td>					
								<td><x-config-field :prop="$kiSet" /></label></td>
							</tr>
						@endif
						
					@endforeach
					
					</table>
					<x-form-edit :idForm="'form-set-iq'" />
					</div>
					</div>
					
					
				</form>
<br>
					<br>
			</div>
		</div>
		<div wire:key="{{ rand() }}">
            @if(session()->has('success'))
            	<x-flash-alert :success="session('success')" />
            @elseif(session()->has('error'))
            	<x-flash-error :error="session('error')" />
            @endif
		</div>
	</div>


<script>
    document.addEventListener("DOMContentLoaded", () => {
    	   console.log("DOMContentLoaded");
    	Livewire.hook('element.updated', (el, component) => {
    		$('#form-user-map-id :input').each(function() { $(this).attr("disabled", true); });
			$('#form-set-general-id :input').each(function() { $(this).attr("disabled", true); });
			$('#form-set-usermap-id :input').each(function() { $(this).attr("disabled", true); });
			$('#form-set-iq-id :input').each(function() { $(this).attr("disabled", true); });
    	})
    });
</script>
</div>
