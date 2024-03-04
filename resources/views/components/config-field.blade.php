@props(['prop'])

<input class="border border-gray-400 p-2 w-full" type="text"
	wire:model.defer="{{$prop['name']}}" name="{{$prop['name']}}" id="{{$prop['name']}}" value="{{$prop['value']}}" size="80"
	required><br>
@error($prop['name']) <span style="mfont-size: small; color: red">{{ $message }}</span> @enderror