@props(['convId','token'])

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<div class="searrch-text">
					<p>Search</p>
				</div>
<div class="tab-search " >
	<form method="GET" action="/kno">
	<div class="container" >		

		
		<input class="search-box" type="text" id="gsearch" name="gsearch"
			placeholder="{{$token!=null?$token:'Search here'}}"
			onfocus="typedText()" list="messages" 
			value="{{$token!=null?$token:''}}"
			/>
		<button type="submit" id="searchbtn" class ="search-button search"><i class="fa fa-search" style="color:white;"></i></button>
		<input type="hidden" name="conversationid" id="search-convId" value="{{$convId}}" /> 			
			
</div>
</form>
</div>
<script>
	$("#gsearch").on("click",function(){
		$(this).val('');
		$(this).attr('placeholder','Search here');
	});
</script>