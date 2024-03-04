<div>   
    <div class="tab-left">&nbsp;
    	<!--button style="margin:25px 0 0 0 !important" id="bckbtn" class="back-btn">back</button-->
	</div> 
    <div style="zoom: 0.7; padding-top: 50px;">{!!$processflow['Image']!!}</div>   
        <script>   
        document.addEventListener("flow-updated", () => {
            var nodes = <?php echo json_encode($processflow['nodeItems']) ?>;

            for(i=0; i<nodes.length; i++)
            {
                if(nodes[i].nodeId == null){
					continue;
                }
               console.log('nodes..'+nodes[i].nodeId);
                var c = document.getElementById(nodes[i].nodeId);                        
                var rect = c.getElementsByTagName("rect")[0]; 
                var path = c.getElementsByTagName("path")[0];
               // rect.classList.add('svg-link');
                 
				var url = nodes[i].itemType+"/"+nodes[i].itemId;
                if($('#link-'+nodes[i].nodeId).length > 0) {
                    continue;
                }
				wrapSvgLink(c, url, nodes[i].nodeId);
				var a = document.getElementById("link-"+nodes[i].nodeId);
				a.setAttribute('@click',"window.livewire.emit('process-click','"+url+"');this.disabled=true;");
                //console.log("looped");
                //console.log(a);
            }
            function wrapSvgLink(elem, url, nodeId) {

          	  var a = document.createElementNS(elem.namespaceURI, "a");

              //console.log(a);
          	  a.id = "link-"+nodeId;



              console.log(a);
          	  a.classList.add("svg-link");
              //console.log(a);
          	  elem.parentNode.insertBefore(a, elem);
              //console.log(elem);
              //console.log(elem.namespaceURI);
              //console.log(a);
          	  a.appendChild(elem);
              //console.log("wrapSvgLink");
              //console.log(a);
          	}

        });

        document.addEventListener("process-section", () => {
            $("#loaderimg").addClass("d-none");
            var nodes = <?php echo json_encode($processflow['nodeItems']) ?>;
            for(i=0; i<nodes.length; i++)
            {
                if(nodes[i].nodeId == null){
					continue;
                }
                console.log(nodes);
           
                var nodename = nodes[i].itemName;
               // console.log(nodename);
                var c = document.getElementById(nodes[i].nodeId);                        
                var rect = c.getElementsByTagName("rect")[0]; 
                var path = c.getElementsByTagName("path")[0];
                var desc = c.getElementsByTagName("desc")[0];
                
                var text = desc.innerHTML;
               
                // if(.indexOf("Return") != -1){
                //     console.log("I am here");
                // }
				var url = nodes[i].itemType+"/"+nodes[i].itemId;
                if(!$("#link-"+nodes[i].nodeId).hasClass('svg-link')){
                    if(text == "Return"){
                        // wrapSvgLink(c, url, nodes[i].nodeId);
                        // var a = document.getElementById("link-"+nodes[i].nodeId);
                        // a.setAttribute('class','rdrctbck');
                        c.setAttribute('class','rdrctbck');
                        c.setAttribute('style','text-decoration:underline;cursor:pointer;');
                    }else{
                        wrapSvgLink(c, url, nodes[i].nodeId);
                        var a = document.getElementById("link-"+nodes[i].nodeId);
                        a.setAttribute('@click',"window.livewire.emit('process-click-doc','"+url+"');this.disabled=true;"); 
                    }
                    
                }
				//wrapSvgLink(c, url, nodes[i].nodeId);
				
            }
            function wrapSvgLink(elem, url, nodeId) {
          	  var a = document.createElementNS(elem.namespaceURI, "a");
          	  a.id = "link-"+nodeId;
          	  a.classList.add("svg-link");
          	  elem.parentNode.insertBefore(a, elem);
          	  a.appendChild(elem);
          	}

            $(".back-btn").click(function() {
            	//$('#Process').find(".tab-process4").removeClass("process-show");
            	$('#Process').find(".tab-process").removeClass("process-hide");
            	//$('#Process').find(".tab-process4").addClass("process-hide");
            	$('#Process').find(".tab-process").addClass("process-show");
            });

        });
        </script>
        
        
  
</div>
