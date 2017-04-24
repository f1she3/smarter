$(document).ready(function(){
	var host = $("input[type=hidden]").val();
	$("input[type=search]").bind("select", function(){
		input = $("input[type=search]").val();
		window.location.href = host + "moderator&user=" + input;	
	})
	$("button[name=submit_mute]").click(function(){
		if($("input[name=mute_min]").val() == ""){
			if($("input[name=mute_hour]").val() == ""){
				if($("input[name=mute_day]").val() == ""){
					$("input[name=mute_min]").focus();
					return false;	
				}
			}
		}
	})
	$("form[name=search_user]").submit(function(){
		input = $("input[type=search]").val();
		if(input != ""){
			window.location.href = host + "/moderator&user=" + input;	
		}

		return false;
	})
})
