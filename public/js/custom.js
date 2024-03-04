
function zoom() {
   // document.body.style.zoom = "80%" 
}



function openCity(evt, cityName) {
	var i, x, tablinks;
	x = document.getElementsByClassName(newFunction());
	for (i = 0; i < x.length; i++) {
		x[i].style.display = "none";
	}
	tablinks = document.getElementsByClassName("tablink");
	for (i = 0; i < tablinks.length; i++) {
		tablinks[i].className = tablinks[i].className.replace(" custom-tab", "");
	}
	document.getElementById(cityName).style.display = "block";
	evt.currentTarget.className += " custom-tab";

	function newFunction() {
		return "city";
	}
}


var coll = document.getElementsByClassName("collapsible");
var i;

for (i = 0; i < coll.length; i++) {
  coll[i].addEventListener("click", function() {
    this.classList.toggle("active");
    var content = this.nextElementSibling;
    if (content.style.display === "block") {
      content.style.display = "none";
    } else {
      content.style.display = "block";
    }
  });
}


function submitAnswers(){
    alert("Please select an option to proceed.");
    return false;
}


function showloaderv(){
		$("#loaderimg").removeClass("d-none");
}

function myFunction() {
	var element = document.getElementById("content-show");
	element.classList.remove("content-show");
}
jQuery("a.svg-btn").click(function() {
	jQuery(".new-content").addClass("content-show");
});
jQuery("a.svg-btn").click(function() {
	jQuery(".invoice-sec").addClass("content-hide");
});
$(".next-btn").click(function() {
	$(".tab-process2").toggleClass("process-show");
});
window.addEventListener('item-updated', event => {
	// console.log("item-updated");
	$("#loaderimg").addClass("d-none");
	$('#Results').find(".tab-process2").toggleClass("process-show");
	$('#Results').find(".tabs-process").toggleClass("process-hide");
	$('#Results').find(".back-btn").click(function() {
		$('#Results').find(".tab-process2").removeClass("process-show");
	});
	$('#Results').find(".back-btn").click(function() {
		$('#Results').find(".tabs-process").removeClass("process-hide");
	});
	
	var i;
	var x = document.getElementsByClassName("tabcontent");
	//console.log("3 >>>>>" + x.length);
	for (i = 0; i < x.length; i++) {
	  x[i].style.display = "none";  
	}

	if(x.length == 1)
	{
		//console.log(x.length);
		x[0].style.display= "block";
	}

	var acc = document.getElementsByClassName("accordion");
	

	for (i = 0; i < acc.length; i++) {
		acc[i].addEventListener("click", function() {
			this.classList.toggle("active");
			var panel = this.nextElementSibling;
			if (panel.style.display === "block") {
				panel.style.display = "none";
			} else {
				panel.style.display = "block";
			}
		});
	}
});

window.addEventListener('flow-updated', event => {
	 //console.log("flow-updated-customjs");
	$("#loaderimg").addClass("d-none");
	$('#Process').find(".tab-process2").toggleClass("process-show");
	$('#Process').find(".tab-process").toggleClass("process-hide");
	$('#Process').find(".back-btn").click(function() {
		$('#Process').find(".tab-process2").removeClass("process-show");
	});
	$('#Process').find(".back-btn").click(function() {
		$('#Process').find(".tab-process").removeClass("process-hide");
	});
});

window.addEventListener('flow-item', event => {
	 //console.log("flow-item-customjs");
	$('#Process').find(".tab-process3").toggleClass("process-show");
	$('#Process').find(".tab-process2").toggleClass("process-hide");
	$('#Process').find(".tab-process").toggleClass("process-hide");
	$('#Process').find(".back-btn-pcs").click(function() {
		$('#Process').find(".tab-process3").removeClass("process-show");
		$('#Process').find(".tab-process2").removeClass("process-hide");
		
		$('#Process').find(".tab-process3").addClass("process-hide");
		$('#Process').find(".tab-process2").addClass("process-show");
	});
	$('#Process').find(".back-btn").click(function() {
		$('#Process').find(".tab-process2").removeClass("process-show");
	});
	$('#Process').find(".back-btn").click(function() {
		$('#Process').find(".tab-process").removeClass("process-hide");
	});
	/*$(".back-btn").click(function() {
		$(".tab-process2").removeClass("process-hide");
	});*/
});

window.addEventListener('flow-item-doc', event => {
	 //console.log("flow-item-doc");
	$(".tab-process5").toggleClass("process-show");
	//$(".tab-process4").toggleClass("process-hide");
	//$(".tab-process").toggleClass("process-hide");
	$(".back-btn-pcs").click(function() {
		$(".tab-process5").removeClass("process-show");
		$(".tab-process4").removeClass("process-hide");
		
		$(".tab-process5").addClass("process-hide");
		$(".tab-process4").addClass("process-show");
		$(".tab-process4").addClass("d-none");
	});
	$(".back-btn").click(function() {
		$(".tab-process2").removeClass("process-show");
	});
	$(".back-btn").click(function() {
		$(".tab-process").removeClass("process-hide");
	});
	/*$(".back-btn").click(function() {
		$(".tab-process2").removeClass("process-hide");
	});*/
		$(".wizzard").addClass("process-hide");
    $("#tabs-1").removeClass('custom-tab');
    $("#tabs-2").removeAttr('onclick');
   // $("#tabs-1").removeAttr('onclick');
    $("#tabs-2").addClass('custom-tab');
    $("#tabs-1").addClass('rdrctbck');
    $(".result-content").addClass('d-none');
    $(".tab-process").addClass("d-none");
});

$(document).ready(zoom());

$(".back-btn-pcs").click(function() {
	$(".tab-process3").removeClass("process-show");
	$(".tab-process2").removeClass("process-hide");
	
	$(".tab-process3").addClass("process-hide");
	$(".tab-process2").addClass("process-show");
});

$(document).ready(function() {
	$(".back-btn").click(function() {
		$(".tab-process2").removeClass("process-show");
	});
});
$(document).ready(function() {
	$(".back-btn").click(function() {
		$(".tabs-process").removeClass("process-hide");
	});
});

$(".next-btn").click(function() {
	$(".tabs-process").toggleClass("process-hide");
});


$(".complete-btn").click(function() {
	$(".tab-process2").toggleClass("process-hide");
});
$(document).ready(function() {
	$(".complete-btn").click(function() {
		$(".tab-process2").removeClass("process-show");
	});
});
$(".complete-btn").click(function() {
	$(".tab-process3").toggleClass("process-show");
});
$(document).ready(function() {
	$(".back-btn").click(function() {
		$(".tab-process3").removeClass("process-show");
	});
});
$(document).ready(function() {
	$(".back-btn").click(function() {
		$(".invoice-sec").removeClass("content-hide");
	});
});




function copyToClipboard(id) {
	try{
		var r = document.createRange();
		r.selectNode(document.getElementById(id));
		window.getSelection().removeAllRanges();
		window.getSelection().addRange(r);
		document.execCommand('copy');
		window.getSelection().removeAllRanges();
	} catch(err){
		window.livewire.emit('copy-data',err.message);
	}
	window.livewire.emit('copy-data',"success");
}

function sendLink(data){
	try{
		send(data);	
	} catch(err){
		window.livewire.emit('send-link-to-chat',err.message);
		//console.log(err.message);
	}
	window.livewire.emit('send-link-to-chat',"success");
}

function sendId(id){
	var res = $("#"+id).text();
	try{
		send(res);
	} catch(err){
		window.livewire.emit('send-to-chat',err.message);
		//console.log(err.message);
	}
	window.livewire.emit('send-to-chat',"success");
}

function send(data) {
	var xhr = new XMLHttpRequest();
	xhr.open("POST", "/post_chat", true);
	xhr.setRequestHeader('Content-Type', 'application/json');
	xhr.send(JSON.stringify({
		toMessage: data,
		conversationid: document.getElementById("convId").value
	}));
}

function email(documentid, conversationid){
	try{
		var xhr = new XMLHttpRequest();
		xhr.open("POST", "/send-email", true);
		xhr.setRequestHeader('Content-Type', 'application/json');
		xhr.send(JSON.stringify({
			documentid: documentid,
			conversationid: conversationid
		}));

		if(xhr.responseText=='' || xhr.responseText == null)
		{
			window.livewire.emit('send-email','error');
		}else{
			window.livewire.emit('send-email','success');
		}
		
	} catch(err){
		window.livewire.emit('send-email',err.message);
	}
}

function typedText() {
	text = document.getElementById("gsearch").value;
	convId = document.getElementById("convId").value;

	//document.getElementById("search-convId").value = convId;
	//console.log("setting convid:"+convId);
	/*$.get("autocomplete", { text: text, convId: convId}, function(data) {
		$.each(data, function(index, value) {
			var el = document.createElement("option");
			el.textContent = value;
			el.value = value;
			msgs.appendChild(el);
		});
	});*/
	
}

function getChallenge() {
	$.ajax({
		type: "POST",
		url: 'challenge',
		data: {
			auth_token: document.getElementById("KNO_AUTH_TOKEN").value,
			site_id: document.getElementById("KNO_SITE_ID").value,
			user_type: document.getElementById("KNO_USER_TYPE").value
		}
	}).done(function(challenge) {
		document.getElementById("KNO_CHALLENGE").value = challenge;
	});
}

$('#edit-form').change(function(){
var selected = $('option:selected', this).attr('class');
var optionText = $('.editable').text();

if(selected == "editable"){
  $('.editOption').show();

  
  $('.editOption').keyup(function(){
      var editText = $('.editOption').val();
      $('.editable').val(editText);
      
  });

}else{
  $('.editOption').hide();
}
});

$(':radio').change(function() {
	//console.log('New star rating: ' + this.value);
  });

submitForms = function(id) {
	$('#' + id).on('submit', function(e) {
		
		if(id.startsWith("form")) {
			//console.log(id);
			$('#form-user-map-id :input').each(function() { $(this).attr("disabled", true); });
			$('#form-set-general-id :input').each(function() { $(this).attr("disabled", true); });
			$('#form-set-usermap-id :input').each(function() { $(this).attr("disabled", true); });
			$('#form-set-iq-id :input').each(function() { $(this).attr("disabled", true); });

			document.getElementById(id + "-edit").style.display = "block";
			document.getElementById(id + "-cancel").style.display = "none";
			document.getElementById(id + "-submit").style.display = "none";
		} 
	});
    
}
 
$(".close").click(function() {
	$(this)
	  .parent(".flash-alert")
	  .fadeOut();
  });

  var edit;

  $(document).ready(function()
  { 
	edit=false;
	 $('#form-user-map-id :input').each(function() {$(this).attr("disabled",true);});
	 $('#form-set-general-id :input').each(function() {$(this).attr("disabled",true);});
	 $('#form-set-usermap-id :input').each(function() {$(this).attr("disabled",true);});
	 $('#form-set-iq-id :input').each(function() {$(this).attr("disabled",true);});
  });  
  
 function editButton(formId)
	   {
		   edit=true;
		   $('#'+formId+'-id :input').each(function()
		{
		$(this).attr('disabled',false);
		document.getElementById(formId+"-edit").style.display = "none";
		document.getElementById(formId+"-cancel").style.display = "block";
		document.getElementById(formId+"-submit").style.display = "block";
		});
		
		
	   }
   
function cancelButton(formId)
{
	edit=true;
	$('#'+formId+'-id :input').each(function()
	{
		$(this).attr('disabled',true);
		document.getElementById(formId).reset();
		document.getElementById(formId+"-edit").style.display = "block";
		document.getElementById(formId+"-cancel").style.display = "none";
		document.getElementById(formId+"-submit").style.display = "none";
		
		
	});
}

function copyText(id, type) {

	var copyText = document.getElementById(id);

	if (type == 'link') {
		var data = copyText.getElementsByTagName('a')[0].href;
	} else {
		var data = copyText.value;
	}
	try {
		const el = document.createElement('textarea');
		el.value = data;
		el.setAttribute('readonly', '');
		el.style.position = 'absolute';
		el.style.left = '-9999px';
		document.body.appendChild(el);
		el.select();
		document.execCommand('copy');
		document.body.removeChild(el);
	} catch(err){
		window.livewire.emit('copy-text',err.message);
	}
	window.livewire.emit('copy-text',"success");
	//console.log("Text copied..." + data);
}






function openCityTab(id) {
	var i;
	var x = document.getElementsByClassName("tabcontent")
  // console.log("Open City Tab>>>>>" + x.length);
  var content =  document.getElementById(id);
		if (content.style.display == "none") {	
			content.style.display = "block";	  
		  for (i = 0; i < x.length; i++) 
		  {
			if(id!=i)
			{
				x[i].style.display = "none";
			}  		
		  }
		} else {
		  content.style.display = "none";
		}
}

$(document).ready( function() {
	var i;
	var x = document.getElementsByClassName("tabcontent");
	//console.log("Document Ready >>>>>" + x.length);
	for (i = 0; i < x.length; i++) {
	  x[i].style.display = "none";  
	}
});

/*
window.onload = function (){
    var c = document.getElementById("svg1").contentDocument;
    var rect = c.getElementById("rect4928");
    rect.setAttribute("style", "fill: green;");
    var c2 = document.getElementById("svg2").contentDocument;
    var rect = c2.getElementById("rect4928");
    rect.setAttribute("style", "fill: green;");
}
*/

window.addEventListener('openAlerts', (e) => {
	document.getElementById("alerts_pop").style.display = "block";
		$("#loaderimg").addClass("d-none");
  });

window.addEventListener('openQuiz', (e) =>
{
	document.getElementById("quiz_pop").style.display = "block";
	$("#loaderimg").addClass("d-none");
  });

function closePop(){
	if(document.getElementById("alerts_pop").style.display == "block")
	{
		document.getElementById("alerts_pop").style.display = "none";
	}
	else if(document.getElementById("quiz_pop").style.display == "block")
	{
		document.getElementById("quiz_pop").style.display = "none";
	}
	
	
}
  window.onclick = function(event) {
    if (event.target != document.getElementById("alerts_pop") && event.target != document.getElementById("user_alerts") && event.target != document.getElementById("alerts-icon")) {
	
        document.getElementById("alerts_pop").style.display = "none";
    }
	if (event.target != document.getElementById("quiz_pop") && event.target != document.getElementById("user_quiz") && event.target != document.getElementById("quiz-icon")) {
	
        document.getElementById("quiz_pop").style.display = "none";
    }
}


$('body').on("click","#user_quiz,#user_alerts,#searchbtn",function(){
	$("#loaderimg").removeClass("d-none");
});
$('body').on("click",".view-process-btn",function(){
	$("#loaderimg").removeClass("d-none");
});


// $('body').on("change","#result-sort",function(){
// 	$("#loaderimg").removeClass("d-none");
// });

$("#customer_context_input").on("click",function(){
  $(this).val('');
  $(this).attr('placeholder','Enter custom value');
});
