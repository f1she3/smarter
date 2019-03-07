$(document).ready(function(){
	show_contacts();
	host = $("input[type=hidden]").val();
	$("input[type=search]").bind("select", function(){
		input = $("input[type=search]").val();
		window.location.href = host + "private&user=" + input;	
	});	
	function show_contacts(){
		$.post("/ajax/get_contacts.php", function(data){
			$("#contacts").html(data);
		});
	}
	$("form").submit(function(){
		input = $("input[type=search]").val();
		if(input != ""){
			window.location.href = host + "private&user=" + input;	
		}

		return false;
	});
	setInterval(show_contacts, 3000);
});
