$(document).ready(function(){
	var i = 0;
	get_messages();
	$("textarea[name=message]").keydown(function(e){
		if(e.keyCode == 13){
			if($("textarea[name=message]").val() != ""){
				$("form").submit();
			}
			
			return false;
		}
	})
	$("textarea[name=message]").keyup(function(x){
		if($("textarea[name=message]").val() == ""){
			if(x.keyCode != 13 && i != 0){
				i = 0;
				$.post("/ajax/send.php", {is_typing:1, empty:1}, function(writing){
					$(".messages").html(writing);
				})	
				get_messages();
			}
		
		}else{
			if(i == 0){
				$.post("/ajax/send.php", {is_typing:1}, function(writing){
					$(".messages").html(writing);
				})	
				get_messages();
			}
			i++;
		}
	})
	$("form").submit(function(){
		if($("textarea[name=message]").val() != ""){
			var message = $("textarea[name=message]").val();
			$.post("/ajax/send.php",{message:message}, function(donnees){
				$(".messages").html(donnees);
			});
			$("textarea[name=message]").val("");	
		}
		$("textarea[name=message]").focus();
		get_messages();
		
		return false;
	})
	function get_messages(){
		$.post("/ajax/get.php", function(data){
			$(".messages").html(data);
		})
	}
	
	setInterval(get_messages, 1000);
})
