@props(['item'])
<script>
//setInterval(function(){

	function getImages(){
		
		
		var size = document.getElementById("data").getElementsByTagName("img").length;
		if(size <= 0 ){
		} else {
			
					var convId = document.getElementById("convId").value;
					for (i = 0; i < size; i++)
					{
						function textToImage()
						{
							var imageTag = document.getElementById("data").getElementsByTagName("img")[i];
							var id = imageTag.dataset.itemid;
							if(id !== undefined) {					
							$.ajax({
									type: "GET",
									url: "/images/"+id+"?conversationid="+convId,
									})
							.done(function(image) 
							{
								imageTag.src = "data:image/png;base64," + image.fields["ImageSrc"];
							});
							}
							
						}
						textToImage();
					}

					var size_a = document.getElementById("data").getElementsByTagName("a").length;
					for (i = 0; i < size_a; i++)
					{
						function textToLink()
						{
							var anchorTag = document.getElementById("data").getElementsByTagName("a")[i];
							var id = anchorTag.dataset.itemid;
							if(id !== undefined) {					
							$.ajax({
									type: "GET",
									url: "/links/"+id+"?conversationid="+convId,
									})
							.done(function(link) 
							{
								if(link.fields["URL"] == ""){
									$.ajax({
										type: "GET",
										url: "/documentfiles/"+id+"?conversationid="+convId,
										})
									.done(function(link) 
									{
										anchorTag.href = "/public/documentfiles/Customer Enquiry - Order follow-up V1.oft";
									});
								}else{
									anchorTag.href = link.fields["URL"];
									anchorTag.target = "_blank";
								}
								
							});
							}
						}
						textToLink();
					}
		}
	}	
//},1000);
	


	getImages();
	$('body').on('keyup',function(){
		getImages();
	});
	
	$('body').on('keydown',function(){
		getImages();
	});
	
	$('body').on('click',function(){
		getImages();
	});
	
	/*$('body').on('load','img',function(){
		getImages();
	});*/
	var $images = $('img');

	$images.on('error', function() {
		getImages();
	});

</script>