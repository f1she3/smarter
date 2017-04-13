$(document).ready(function(){
	alertMessages();
	function alertMessages(){
		$.post("/ajax/get_index.php", function(data){
			$("#event").html(data);
			if($("input[name=hidden_input]").val() != ""){
				$("#event").addClass("background");
			
			}else{
				$("#event").removeClass("background");
			}
		})
	}
	
	setInterval(alertMessages, 15000);
})
