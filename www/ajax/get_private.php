<?php

require_once '../functions/init.php';
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	if(is_logged()){
		$contact = $_POST['contact'] = secure($_POST['contact']);
		$query = mysqli_prepare($mysqli, 'SELECT sender FROM friends WHERE (BINARY sender = ? OR BINARY sender = ?) AND
			(BINARY contact = ? OR BINARY contact = ?) AND validate = 1');
		mysqli_stmt_bind_param($query, 'ssss', $_SESSION['username'], $contact, $_SESSION['username'], $contact);
		mysqli_stmt_execute($query);
		$i = 0;
		while(mysqli_stmt_fetch($query)){
			$i++;
		}
		if($i > 0){
			$one = 1;	
			$req = mysqli_prepare($mysqli, 'SELECT sender, date FROM private WHERE contact = ? AND isTyping = ?');
			mysqli_stmt_bind_param($req, 'si', $_SESSION['username'], $one);
			mysqli_stmt_execute($req);
			mysqli_stmt_bind_result($req, $result['sender'], $result['date']);
			while(mysqli_stmt_fetch($req)){
				$now_year = date('Y');
				$now_month = date('m');
				$now_day = date('d');
				$now_hour = date('H');
				$now_minute = date('i');
				$now_second = date('s');
				$db_year = substr($result['date'], 0, 4);
				$db_month = substr($result['date'], 5, 2);
				$db_day = substr($result['date'], 8, 2);
				$db_hour = substr($result['date'], 11, 2);
				$db_minute = substr($result['date'], 14, 2);
				$db_second = substr($result['date'], 17, 2);
				// 1 minute counter
				if($now_year != $db_year){
					if($now_month  != 01 && $db_month != 12){
						rm_notification($result['sender'], $contact);
					
					}else{
						if($now_day != 01 || $db_day == 31 || $now_hour != 00 || $db_hour != 23 ||
							$now_minute != 00 || $db_minute != 59 || $now_second > $db_second){
							rm_notification($result['sender'], $contact);
							
						}
					}
				
				}else{
					if($now_month != $db_month){
						if($now_day != 01 || $db_day != 31 || $db_hour != 23 || $now_minute != 00 ||
							$db_minute != 59 || $now_second > $db_second){
							rm_notification($result['sender'], $contact);
						}
						
					}else{
						if($now_day != $db_day){
							if($now_hour != 00 && $db_hour != 23 || $now_minute != 00 || $db_minute != 59 ||
								$now_second > $db_second){
								rm_notification($result['sender'], $contact);
								
							}
						
						}else{
							if($now_hour != $db_hour){
								if($now_minute != 00 || $db_minute != 59 || $now_second > $db_second){
									rm_notification($result['sender'], $contact);
								}
							
							}else{
								if($now_minute == $db_minute + 1){
									if($now_second > $db_second){
										rm_notification($result['sender'], $contact);
									}
								
								}else{
									if($now_minute > $db_minute){
										rm_notification($result['sender'], $contact);
									}
								}
							}
						}
					}
				}
				if($result['sender'] != $_SESSION['username']){
					echo "<p>".$result['sender']." est en train d'écrire ...</p>";
				}
			}
			$query = mysqli_prepare($mysqli, 'SELECT sender, contact, message, date FROM private WHERE (BINARY sender = ? OR
				BINARY sender = ?) AND (BINARY contact = ? OR BINARY contact = ?) AND isTyping = 0 ORDER BY id DESC LIMIT 80');
			mysqli_stmt_bind_param($query, 'ssss', $_SESSION['username'], $contact, $_SESSION['username'], $contact);
			mysqli_stmt_execute($query);
			mysqli_stmt_bind_result($query, $sender, $contact, $message, $date);
			$i = 0;
			while(mysqli_stmt_fetch($query)){
				$month = substr($date, 5, 2);
				$day = substr($date, 8, 2);
				$hour = substr($date, 11, 8);
				$message = bb_decode($message);
				echo "<span class=\"name\">
						<strong>
							".$sender."
						</strong>
					</span> : 
					<div class=\"pull-right\">
						à ".$hour." le ".$day."/".$month."
					</div>
					<span>
						".$message."
					</span><hr>";
				$i++;
			}
			if($i > 0){
				$query = mysqli_prepare($mysqli, 'SELECT message FROM private WHERE BINARY sender = ? AND BINARY contact = ?');
				mysqli_stmt_bind_param($query, 'ss', $contact, $_SESSION['username']);
				mysqli_stmt_execute($query);
				mysqli_stmt_bind_result($query, $message);
				while(mysqli_stmt_fetch($query));
				if(!empty($message)){
					$query = mysqli_prepare($mysqli, 'SELECT sender FROM private WHERE BINARY sender = ? AND BINARY contact = ? AND viewed = 0');
					mysqli_stmt_bind_param($query, 'ss', $contact, $_SESSION['username']);
					mysqli_stmt_execute($query);
					mysqli_stmt_bind_result($query, $sender);
					while(mysqli_stmt_fetch($query));
					if(!empty($sender)){
						$query = mysqli_prepare($mysqli, 'UPDATE private SET viewed = 1 WHERE BINARY sender = ? AND BINARY contact = ?');
						mysqli_stmt_bind_param($query, 'ss', $contact, $_SESSION['username']);
						mysqli_stmt_execute($query);
					}
				}
			}else{
				echo "<h4 class=\"text-center\">Aucun message privé ...</h4>";
			}
		}else{
			echo "<h4 class=\"text-center\">
					<a href=\"/private&user=".$contact."\">
						votre contact n'est plus ami avec vous, veuillez rafraichir la page
					</a>
				</h4>";
		}
	}else{
		redirect(2);
	}
}else{
	redirect(2);
}
