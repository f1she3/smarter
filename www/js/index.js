$(document).ready(function(){
	alertMessages();
	function alertMessages(){
		$.post("/ajax/get_index.php", function(data){
			$(".notif-center").html(data);
		});
	}
	setInterval(alertMessages, 15000);	
});
