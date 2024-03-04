<x-layout>

<link rel="stylesheet"
	href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.7.2/font/bootstrap-icons.css">
<div class="container">
	<div class="row justify-content-center">
		<div class="col-md-12">
			@if($results['size'] >= 0)
		    	@livewire('home', ['results'=>$results,'settings'=>$settings])
		    @endif
		</div>
	</div>
	<div wire:key="{{ rand() }}">
        @if(isset($success))
        	<x-flash-alert :success="$success" />
        @elseif(isset($error))
        	<x-flash-error :error="$error" />
        @endif
	</div>
</div>
</x-layout>

