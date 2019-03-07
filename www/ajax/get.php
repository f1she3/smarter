<?php

require_once '../functions/init.php';
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	if(is_logged()){
		$query = mysqli_query($mysqli, "SELECT sender FROM chat WHERE isTyping = 1");
		while($result = mysqli_fetch_assoc($query)){
			if($result['sender'] != $_SESSION['username']){
				echo "<p>".$result['sender']." est en train d'écrire ...</p>";
			}
		}
		if(is_mute($_SESSION['username'])){
				echo "<span class=\"glyphicon glyphicon-volume-off\" style=\"color:red; font-size: 20px\"></span><br>";
		}
		$query = mysqli_query($mysqli, "SELECT sender, message, date FROM chat WHERE isTyping = 0 ORDER BY id DESC LIMIT 80");
		$e = 0;
		while($infos = mysqli_fetch_assoc($query)){
			$date = date_create($infos['date']);
			$date = date_format($date, 'G:i:s, \l\e j/m Y');
			$infos['message'] = bb_decode($infos['message']);
			if($infos['sender'] != $_SESSION['username']){
				echo "<span class=\"name\">
						<a href=\"".$_SESSION['host'].constant('BASE_URL')."private&user=".$infos['sender']."\" target=\"_blank\">
							".$infos['sender']."
						</a>
					</span>";	
			}else{ 
				echo "<span class=\"name\">
						<strong>
							".$infos['sender']."
						</strong>
					</span>";
			}
			echo "<span class=\"pull-right\">
					".$date."
				</span>
				<span> : 
					".$infos['message']."
				</span><hr>";
			$e++;
		}
		if($e == 0){
			echo "<h4 class=\"text-center\">Aucun message ...</h4>";
		}
	}else{
		redirect(2);
	}
}else{
	redirect(2);
}
