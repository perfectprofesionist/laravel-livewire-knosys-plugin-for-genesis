@props(['item'])
@if($item["children"] != null)
    <?php $newArr = []; 
        $child1 = "";
    ?>
	{{--$item["detail"]["itemType"]}}>>{{count($item["children"])--}}<br>
	@foreach($item["children"] as $child)
        <?php 
        if($child["detail"]["itemType"] == "RelatedLinks"){
            $child1 = $child;
        }else{
            array_push($newArr,$child);
        }
    ?>
    @endforeach
    <?php if(!empty($child1)){
        array_push($newArr,$child1);
    } ?>
@endif
@if(!empty($newArr))
    <?php $itemid = isset($item['detail']['id']) ? $item['detail']['id'] : ""; ?>
    @foreach($newArr as $child)
    		@switch($child["detail"]["itemType"])
        		@case('DocumentItem')
                    @break
                @case('Paragraph')
                	<x-paragraph :para="$child"/>
                	@break
                @case('TableOfContents')
                	<x-toc :toc="$child"/>
                	@break
                @case('AccordionParagraph')
                	<x-accordion-para :accordion="$child" />
                	@break
                @case('Icon')
                	<x-icon :icon="$child" />
                	@break
                @case('Image')
                    <x-data-img :item="$child" />
                    @break
                @case('DocumentFile')
                	<x-document-file :docFile="$child"/>
                	@break
                @case('Document')
                <h3 style="margin-left: 20px;">{{$child["detail"]["title"]}}</h3>
                	<x-doc-children :item="$child" />
                	@break
                @case('SharedExpandedParagraph')
                	<x-shared-para :para="$child" />
                	@break	
                @case('OperatorPrompt')
                    <?php 
                    if($child['fields']['TypeName'] == "Prompt"){
                        $class="operatorprompt";
                    }
                    elseif($child['fields']['TypeName'] == "Alert"){
                        $class="operatorpromptalert";
                    }
                    elseif($child['fields']['TypeName'] == "Action"){
                        $class="operatorpromptaction";
                    }
                    else{
                        $class="operatorpromptdefault";
                    }
                   ?>
                    <div class="{{$class}}">{!!$child["fields"]["Text"]!!}</div>
                    @break  
    			@case('File')
    				<div style=" margin-left: 20px;">
    				<x-file :file="$child"/>
    				</div>            	
                	@break
    			@case('AudioFile')
                	<x-audioFile :audioFile="$child"/>
                	@break
    			@case('VideoFile')
                	<x-videoFile :videoFile="$child"/>
                	@break
            	@case('ProcessFlowExternal')
            		<livewire:launch-process :flow="$child"/>
            		@break
                @case('ProcessExternal')
                <script>
                        $(".flex").hide();
                    </script>
                <livewire:launch-wizard :itemid="$itemid" :wizardflow="$child"/>        
                    @break
            @endswitch
            {{--$child["detail"]["itemType"]}}>>{{count($child["children"])--}}
            @if($child["children"] != null)
                <div class="doc-child2">
            	   <x-doc-children :item="$child"/>
                </div>
            @endif
    @endforeach  
@endif
