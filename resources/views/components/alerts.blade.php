@props(['alerts'])
<div>
    <div id="alerts_pop" class="popup alerts" >
        <div class="top">
            <a class="popup_btn">
            <span id="boot-icon" class="bi bi-exclamation-triangle-fill" style="font-size: 15px; color: rgb(255, 255, 255); -webkit-text-stroke-width: 0px; background-color: #3F6ECC;"><i></i></span>
                Alerts
                <span onClick ="closePop()" id= "pop-close" class=" bi bi-x float-right" style ="font-size: 22px; cursor:pointer;" ></span>   
            </a>    
        </div>
        <div class="inner">
            @if(isset($alerts))
           
                @foreach($alerts['values'] as $alert )
              
                    @if($alert['priority'] == 1)
                    <div class="alert-item  high">
                    @else
                    <div class="alert-item">
                    @endif    
                        <span class="title">
                        <a aria-label="{{$alert['title']}}">{{$alert['title']}}</a>
                        </span>
                        <span class="details">
                        @if($alert['priority'] == 1)
                        <span class="status  high" data-toggle="tooltip" data-placement="right" title=" high" data-title="Alert: Open" style="color: #{{$alert['iconColour']}}"></span>
                        @else
                        <span class="status" data-toggle="tooltip" data-placement="right" title="" data-title="Alert: Open" style="color: #{{$alert['iconColour']}}"></span>
                        @endif
                        <span class="section">{{$alert['sectionTitle']}}</span>
                        <span class="type">Alert</span>
                        <span class="type">{!! $alert['messageText'] !!}</span>
                        <span class="date"><time title="{{$alert['messageSentDate']}}">{{date('F j, Y, g:i a', strtotime($alert['messageSentDate']));}}</time></span>
                        <!--<a aria-label="View" class="view" href="/Alert/Detail/T6zDVQx3Ee2oSgANOuEG7A" onclick="$('#alerts_pop').hide(); km.loadContentPanel(this, 'alerts'); return false;">View</a> -->
                        </span>
                    </div>
                @endforeach
            @endif

          

            
        
        
    </div>
</div>    