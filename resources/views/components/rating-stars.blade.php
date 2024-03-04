
@props(['value'])

<label>
    <input wire:model="stars" type="radio" name="stars" value="{{$value}}" />
    @foreach(range(1, $value) as $i)
    <span class="icon">★</span>
    @endforeach
</label>