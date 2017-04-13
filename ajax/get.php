<?php

	if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
		require_once '../functions/init.php';
		require_once '../functions/actions.php';
		if(is_logged()){
			$query = mysqli_query($mysqli, "SELECT sender FROM chat WHERE is_typing = 1");
			while($result = mysqli_fetch_assoc($query)){
				if($result['sender'] != $_SESSION['name']){
					echo "<p>".$result['sender']." est en train d'écrire ...</p>";
				}
			}
			if(is_mute($_SESSION['name'])){
					echo "<span class=\"glyphicon glyphicon-volume-off\" style=\"color:red; font-size: 20px\"></span><br>";
			}
			$query = mysqli_query($mysqli, "SELECT sender, message, date FROM chat WHERE is_typing = 0 ORDER BY id DESC LIMIT 80");
			$e = 0;
			while($m = mysqli_fetch_assoc($query)){
				$month = substr($m['date'], 5, 2);
				$day = substr($m['date'], 8, 2);
				$hour = substr($m['date'], 11, 8);
				$m['message'] = bb_decode($m['message']);
				if($m['sender'] != $_SESSION['name']){
					echo "<span class=\"name\">
							<a href=\"/private&user=".$m['sender']."\" target=\"_blank\">
								".$m['sender']."
							</a>
						</span>";	
					
				}else{ 
					echo "<span class=\"name\">
							<strong>
								".$m['sender']."
							</strong>
						</span>";
					}
				echo "<span class=\"pull-right\">
						à ".$hour." le ".$day."/".$month."
					</span>
					<span> : 
						".$m['message']."
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
