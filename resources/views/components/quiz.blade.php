@props(['quiz'])
<div>  
   <div id="quiz_pop" class="popup quiz quiz-pop-outer" >   
        <div class="top">
            <a class="popup_btn">
            <span id="boot-icon" class="bi bi-trophy-fill" style="font-size: 15px; color: rgb(255, 255, 255); -webkit-text-stroke-width: 0px; background-color: #3F6ECC;"><i></i></span>
                Quiz
                <span onClick ="closePop()" id= "pop-close" class=" bi bi-x float-right" style ="font-size: 22px; cursor:pointer;" ></span>
            </a>            
        </div>      
        <div class="inner">
            @if(isset($quiz))	
                <?php
                   // echo "<pre>"; print_r($quiz);
                ?>
                @foreach($quiz["values"] as $q)

                    <div class="quiz-item ">
                        <span class="title">
                        <a aria-label="{{$q['title']}}" href="{{$q['url']}}" target="_blank">{{$q['title']}}</a>
                        </span>
                        <span class="details">
                        <span class="status" data-toggle="tooltip" data-placement="right" title="{{($q['completed'] == false) ? 'Not Completed': 'Completed';}}" data-title="{{($q['completed'] == false) ? 'Not Completed': 'Completed';}}"></span>
                        <span class="section">&nbsp;</span>
                        <span class="type">{{($q['completed'] == false) ? 'Not Completed': 'Completed';}}</span>
                        <br>
                        <span class="date" style="">
                        </span>

                        <!-- <a aria-label="View" class="view" href="/Quiz/Detail/jMnEytGuEeyoRgANOuEG7A?activeTab=1" onclick="$('#quizzes_pop').hide(); km.loadContentPanel(this, 'quizzes'); return false;">View</a>  -->
                        </span>
                    </div>

                @endforeach
            @endif
        </div>  
    </div>
    

</div>
   

  