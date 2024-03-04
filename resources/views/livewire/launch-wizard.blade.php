<div class="search-result-content search-result-contentINNER">
    <div class="tab-process">
    	<div class="tab-left"> 
            <h5>{{$wizardflow['fields']['WizardTitle']}}</h5>
        </div>
    	 <div class="tab-right">
            <a class="view-process-btn disable" wire:click="viewProcessWizard('{{$wizardflow['fields']['WizardGuid']}}','{{$itemid}}','{{$convId}}')" style ="width:100px">Launch Process Wizard</a>
        </div>
    </div>
    <input type="hidden" value="{{$convId}}" id="conId" />
   
        <div class="tab-process4 process-hide wizzard"> 
<?php   if(isset($processwizard)){ ?>
            <input type="hidden" value="{{$processwizard['responseId']}}" id="responseId" />
            <?php 
            if($processwizard['wizard']['completed'] ==1){
                echo  '<div class="panel-success wizard-finalsummary contentstyles">
                        <div class="panel-heading"><h5 class="panel-title">'.$processwizard['wizard']['finalSummary']['title'].'</h5></div>
                        <div class="panel-body">
                            <p class="waise"><span style="font-family: Arial, Helvetica, sans-serif;">'.$processwizard['wizard']['finalSummary']['summaryText'].'</span></p>
                        </div>
                    </div>'; ?>
                     <div class="panel-success wizard-summary">
                        <div class="panel-heading"><h5 class="panel-title">Summary</h5></div>
                        <table class="table processtable">
                            <tbody> 
                                <?php
                                foreach ($processwizard['wizard']['completedSteps'] as $key => $value) { 

                                   ?>
                                    <tr class="wizard-summary-row">
                                       <td class="wizard-summary-title">{{$value['questionTitle']}}<br/><span class="minimaltext">{{$value['notes']}}</span></td>
                                        <td class="wizard-summary-answer">{{$value['stepAnswer']}}</td>
                                        <!--td class="wizard-summary-actions">
                                            <div class="pull-right">
                                                <span class="btn btn-sm btn-default" tabindex="0" aria-label="Save" data-modal="true" data-modal-size="600" data-modal-url="/Process/PopupNotes" data-modal-title="Add Note" data-modal-buttons="Save" data-modal-ready="$('#modal_window .wizard_note_field').val($('#summary-0c9fae3d-2480-ed11-a84e-000d3ae08db5').val());" data-modal-confirm-url="saveNote('0c9fae3d-2480-ed11-a84e-000d3ae08db5');">Add Note</span>
                                                </div>

                                        </td-->
                                </tr>
                                   <?php
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
                <?php
            }else{
                if(count($processwizard['wizard']['completedSteps']) == 0){
               ?>

               <div class="panel-body">
                    <h5 class="panel-title">{{$processwizard['wizard']['currentStep']['questionTitle']}}</h5>
                    @if($processwizard['wizard']['stepAnswers'])
                        @foreach($processwizard['wizard']['stepAnswers'] as $answers)
                         <div class="radio">
                                <label>
                                    <input class="stepAnswers"  name="stepAnswers_{{$processwizard['wizard']['id']}}" type="radio" {{$answers['relationshipId'] == $processwizard['wizard']['stepAnswers'][0]['relationshipId'] ? 'checked':''}}  value="{{$answers['relationshipId']}}">
                                    {{$answers['answerText']}}
                                </label>
                            </div>
                            @endforeach 
                            
                            <div class="next-btns2">
                                <input type='button' value="Add note" id="addnote" class="btn btn-primary BtnAddNote" />
                                <div class="notesave d-none SaveNoteField" id="ntsv">
                                    <input type='text' value="" id="notes" class="form-control" />
                                    <input type='button' value="Save" id="savenote" data-vid="stepAnswers" class="btn btn-primary notesave" />
                                </div>
                                <input type="button" class="btn btn-success BtnNext" id="submitansbtn" value="Next" wire:click="submitAnswer('{{$convId}}','{{$processwizard['responseId']}}','{{$processwizard['wizard']['stepAnswers'][0]['relationshipId']}}','')" />
                            </div>
                    @endif
                   
                   
                </div>  
                 
   <?php        }else{
                    ?>
                 <?php
                    if($processwizard['wizard']['title'] == "Customer Enquiry" && $processwizard['wizard']['currentStep']['stepType']=="1"){
                    ?>
                        <div class="panel-body">
                            <h5 class="panel-title">{{$processwizard['wizard']['currentStep']['stepTitle']}}</h5>
                            @if($processwizard['wizard']['stepAnswers'])
                                @foreach($processwizard['wizard']['stepAnswers'] as $answers)
                                 <div class="radio">
                                        <label>
                                            <input class="stepAnswers"  name="stepAnswers_{{$processwizard['wizard']['id']}}" type="radio" {{$answers['relationshipId'] == $processwizard['wizard']['stepAnswers'][0]['relationshipId'] ? 'checked':''}}  value="{{$answers['relationshipId']}}">
                                            {{$answers['answerText']}}
                                        </label>
                                    </div>
                                    @endforeach 
                                    <div class="next-btns2">
                                <input type='button' value="Add note" id="addnote" class="btn btn-primary BtnAddNote" />
                                <div class="notesave d-none SaveNoteField" id="ntsv">
                                    <input type='text' value="" id="notes" class="form-control" />
                                    <input type='button' value="Save" id="savenote" data-vid="stepAnswers" class="btn btn-primary notesave" />
                                </div>
                                <input type="button" class="btn btn-success BtnNext" id="submitansbtn" value="Next" wire:click="submitAnswer('{{$convId}}','{{$processwizard['responseId']}}','{{$processwizard['wizard']['stepAnswers'][0]['relationshipId']}}','')" />
                            </div>
                            @endif 
                        </div>

                            <?php
                    }else{
                 ?>
                        <div class="panel-body">
                            <h5 class="panel-title">{{($processwizard['wizard']['title'] == "Customer Enquiry")  ? $processwizard['wizard']['currentStep']['questionTitle'] : $processwizard['wizard']['currentStep']['questionTitle']}}</h5>
                            @if($processwizard['wizard']['answerOptions'])
                                @if($processwizard['wizard']['title'] == "Customer Enquiry" || ($processwizard['wizard']['currentStep']['stepType'] == 2 && $processwizard['wizard']['title'] != "Customer Enquiry"))
                                    @foreach($processwizard['wizard']['answerOptions'] as $answers)
                                     <div class="radio">
                                            <label>
                                                <input class="stepAnswers1"  name="stepAnswers_{{$processwizard['wizard']['id']}}" type="checkbox" value="{{$answers['id']}}">
                                                {{$answers['optionText']}}
                                            </label>
                                        </div>
                                    @endforeach 
                                    <div class="next-btns2">
                                <input type='button' value="Add note" id="addnote" class="btn btn-primary BtnAddNote" />
                                <div class="notesave d-none SaveNoteField" id="ntsv">
                                    <input type='text' value="" id="notes" class="form-control" />
                                    <input type='button' value="Save" id="savenote2"  data-vid="stepAnswers1" class="btn btn-primary notesave" />
                                </div>
                                <input type="hidden" id="def-sel-val" value="{{$processwizard['wizard']['answerOptions'][0]['id']}}" />
                                <input type="button" class="btn btn-success BtnNext" id="submitansbtn" value="Next" wire:click="submitSecondStep('{{$convId}}','{{$processwizard['responseId']}}','{{$processwizard['wizard']['answerOptions'][0]['id']}}','')" />
                            </div>
                                @else
                                    <div class="EligibiltyAccordionOuter">
                                    @foreach($processwizard['wizard']['answerOptions'] as $answers)
                                        <img src="{{url('public/images/lastnode.gif')}}" /> <label>{{$answers['optionText']}}</label><br/>
                                            @if(isset($answers['children']))
                                                <ol class="child-li2">
                                                @foreach($answers['children'] as $child)
                                                    <li><img src="{{url('public/images/lastnode.gif')}}" /> {{$child['optionText']}} </li>
                                                    @if(isset($child['children']))
                                                        <ol class="child-li2">
                                                         @foreach($child['children'] as $subchild)
                                                            <li><img src="{{url('public/images/lastnode.gif')}}" /> <a href="javascript:void(0);" data-item="{{$subchild['optionText']}}" class="childlink" data-value="{{$subchild['relationshipId']}}">{{$subchild['optionText']}}</a></li>
                                                         @endforeach
                                                        </ol>
                                                    @endif
                                                @endforeach
                                                </ol>
                                            @endif
                                    @endforeach
                                    </div>
                                    <span class="d-none" id="selectedoption">Male selected. click Next to proceed</span><br/>
                                    <input type="hidden" value="" id="gselect" />
                                   
                                   <div class="next-btns2 next-btns2-New">
                                    <input type='button' value="Add note" id="addnote" class="btn btn-primary BtnAddNote" />
                                    <div class="notesave d-none SaveNoteField" id="ntsv">
                                        <input type='text' value="" id="notes" class="form-control" />
                                        <input type='button' value="Save" id="savenote1" data-vid="lastnode" class="btn btn-primary notesave" />
                                    </div>
                                    <input type="button" class="btn btn-success BtnNext d-none" id="submitTbtn" value="Next" wire:click="submitThirdStep('{{$convId}}','{{$processwizard['responseId']}}','','')" />

                                    </div>
                                @endif
                            @endif
                           
                           
                        </div>
        <?php       } ?>
                    <div class="panel-success wizard-summary">
                        <div class="panel-heading"><h5 class="panel-title">Summary</h5></div>
                        <table class="table processtable">
                            <tbody> 
                                <?php
                                foreach ($processwizard['wizard']['completedSteps'] as $key => $value) { 

                                   ?>
                                    <tr class="wizard-summary-row">
                                        <td class="wizard-summary-title">{{$value['questionTitle']}}<br/><span class="minimaltext">{{$value['notes']}}</span></td>
                                        <td class="wizard-summary-answer">{{$value['stepAnswer']}}</td>
                                </tr>
                                   <?php
                                }
                            ?>
                            </tbody>
                        </table>
                    </div>
    <?php       }
            } ?>
<?php   } ?>
        
        </div> 
        <script>   
        document.addEventListener("process-wizard", () => {
             console.log("process-wizard");
			$("#loaderimg").addClass("d-none");
    		$(".tab-process").addClass("d-none");
            $(".tab-process4").addClass("process-hide");
            $(".wizzard").removeClass("process-hide");
            $("#tabs-1").removeClass('custom-tab');
            $("#tabs-2").removeAttr('onclick');
           // $("#tabs-1").removeAttr('onclick');
            $("#tabs-2").addClass('custom-tab');
            $("#tabs-1").addClass('rdrctbck');
            $(".result-content").addClass('d-none');
        });
        </script>
</div>

<script>
    $("body").on("click","#addnote",function(){
        $("#addnote").addClass('d-none');
        $("#ntsv").removeClass('d-none'); 
    }); 

    $("body").on("click","#savenote",function(){
        $("#savenote").addClass('d-none'); 
        var convId = $("#conId").val();
        var responseId = $("#responseId").val();
        var stepclass = $(this).data('vid');
        var notes = $("#notes").val(); 

        $("#notes").attr('readonly',true); 
        if(stepclass == "stepAnswers"){
            var selectedRelationshipId = $(".stepAnswers:checked").val();
            $("#submitansbtn").attr("wire:click","submitAnswer('"+convId+"','"+responseId+"','"+selectedRelationshipId+"','"+notes+"')");
        }
    });

    $("body").on("click","#savenote1",function(){
        $("#savenote1").addClass('d-none'); 
        var convId = $("#conId").val();
        var responseId = $("#responseId").val();
        var notes = $("#notes").val(); 
        $("#notes").attr('readonly',true); 
        var selectedRelationshipId = $("#gselect").val();
        $("#submitTbtn").attr("wire:click","submitThirdStep('"+convId+"','"+responseId+"','"+selectedRelationshipId+"','"+notes+"')");  
    });

    $("body").on("click","#savenote2",function(){
        $("#savenote2").addClass('d-none'); 
        var convId = $("#conId").val();
        var responseId = $("#responseId").val();
        var notes = $("#notes").val(); 
        $("#notes").attr('readonly',true); 
        var selectedRelationshipId = [];
        $(".stepAnswers1:checked").each(function(i){
            selectedRelationshipId[i] = $(this).val(); 
        });
        if(selectedRelationshipId == ""){
            selectedRelationshipId = $("#def-sel-val").val();
        }
        $("#submitansbtn").attr("wire:click","submitSecondStep('"+convId+"','"+responseId+"','"+selectedRelationshipId+"','"+notes+"')");
    });

    $("body").on("change",".stepAnswers",function(){
        var convId = $("#conId").val();
        var responseId = $("#responseId").val();
        var notes = $("#notes").val(); 
        var selectedRelationshipId = $(this).val();
        $("#submitansbtn").attr("wire:click","submitAnswer('"+convId+"','"+responseId+"','"+selectedRelationshipId+"','"+notes+"')");
    });  

    $("body").on("click",".childlink",function(){
        var convId = $("#conId").val();
        var selectedval = $(this).data('item');
        var responseId = $("#responseId").val(); 
        var notes = $("#notes").val(); 
        var selectedRelationshipId = $(this).data('value');
        $("#selectedoption").html(selectedval+" selected. click Next to proceed");
        $("#gselect").val(selectedRelationshipId);
        $("#submitTbtn").removeClass("d-none");
        $("#selectedoption").removeClass("d-none");
        $("#submitTbtn").attr("wire:click","submitThirdStep('"+convId+"','"+responseId+"','"+selectedRelationshipId+"','"+notes+"')");
    });

$("body").on("change",".stepAnswers1",function(){
    var convId = $("#conId").val();
    var responseId = $("#responseId").val();
    var notes = $("#notes").val(); 
    var selectedRelationshipId = [];
    $(".stepAnswers1:checked").each(function(i){
        selectedRelationshipId[i] = $(this).val(); 
    });
    $("#submitansbtn").attr("wire:click","submitSecondStep('"+convId+"','"+responseId+"','"+selectedRelationshipId+"','"+notes+"')");
});

$(document).on("click","#submitansbtn", function(){
    $(this).attr("disabled",true);
});
$(document).on("click","#submitTbtn", function(){
    $(this).attr("disabled",true);
});
</script>  