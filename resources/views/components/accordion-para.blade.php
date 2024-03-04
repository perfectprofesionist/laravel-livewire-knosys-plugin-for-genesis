@props(['accordion'])
<div style="margin-left: 20px; margin-bottom: 5%; margin-right: 20px;">
  
    @for ($i = 0; $i < count($accordion["fields"]["AccordionList"]); $i++) 
    <button type="button" class="order-mod-button tabButton" onclick="openCityTab('{{$i}}')">{{$accordion["fields"]["AccordionList"][$i]}}</button> 
    @endfor   

    @php
        $cnt = count($accordion["fields"]["AccordionList"]);
    @endphp

    @for ($j = 0; $j < count($accordion["fields"]["AccordionList"]); $j++) 
    <div class="tabcontent" id="{{$j}}" style="@if($cnt == 1){{'display:block !important;'}} @endif">
        <br>
    {!!$accordion["fields"]["AccordionContent"][$j]!!}
         <br>
    </div>
    @endfor

   

   <br>
</div>