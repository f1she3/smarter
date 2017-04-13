$(document).ready(function(){
	var host = $("input[type=hidden]").val();
	$("input[type=search]").bind("select", function(){
		input = $("input[type=search]").val();
		window.location.href = host + "/admin&user=" + input;	
	})
	$("form[name=search_user]").submit(function(){
		input = $("input[type=search]").val();
		if(input != ""){
			window.location.href = host + "/admin&user=" + input;	
		}

		return false;
	})
})
