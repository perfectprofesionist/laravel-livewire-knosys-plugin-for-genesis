@props(['value','color'])
@if($value!=0)
    @foreach(range(1, ($value)) as $i)
        <span class="icon rating" style="color:{{$color}}; font-size:200%;">★</span>
    @endforeach
@endif