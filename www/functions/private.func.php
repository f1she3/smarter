<?php

	// Checks if the $user and $contact are friends or not
	function is_friend($user, $contact){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'SELECT contact FROM friends WHERE (BINARY sender = ? OR BINARY sender = ?) AND 
			(BINARY contact = ? OR BINARY contact = ?) AND validate = 1');
		mysqli_stmt_bind_param($query, 'ssss', $user, $contact, $user, $contact);
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $r);
		$result = mysqli_stmt_fetch($query);
		if(!empty($result)){
			return true;
		
		}else{
			return false;
		}
	}
	function new_friend_req($sender, $contact, $message){
		$mysqli = get_link();
		$date = date('d/m Y');
		$query = mysqli_prepare($mysqli, 'INSERT INTO friends (sender, contact, message, date) VALUES (?, ?, ?, NOW())');
		mysqli_stmt_bind_param($query, 'sss', $sender, $contact, $message);
		mysqli_stmt_execute($query);
	}
	function is_asked($sender, $contact, $validate){
		$mysqli = get_link();
		if(is_null($validate)){
			$query = mysqli_prepare($mysqli, 'SELECT sender, contact, message FROM friends WHERE BINARY sender = ? AND BINARY contact = ?');
			mysqli_stmt_bind_param($query, 'ss', $sender, $contact);
		
		}else{
			$query = mysqli_prepare($mysqli, 'SELECT sender, contact, message FROM friends WHERE BINARY sender = ? AND 
				BINARY contact = ? AND validate = ?');
			mysqli_stmt_bind_param($query, 'ssi', $sender, $contact, $validate);
		}
		mysqli_stmt_execute($query);
		mysqli_stmt_bind_result($query, $db_sender, $db_contact, $db_message);
		$i = 0;
		while(mysqli_stmt_fetch($query)){
			$i++;	
		}
		if($i != 0){
			$result = array(
				'sender' 	=> $db_sender,
				'contact' 	=> $db_contact,
				'message' 	=> $db_message
			);
				
		}else{
			$result = false;
		}
		
		return $result;
	}
	function answer_friend_req($sender, $contact, $validate){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'UPDATE friends SET validate = ?, viewed = 2 WHERE (BINARY sender = ? OR BINARY sender = ?) AND (BINARY contact = ? OR BINARY contact = ?) 
			AND validate = 0');
		mysqli_stmt_bind_param($query, 'issss', $validate, $sender, $contact, $sender, $contact);
		mysqli_stmt_bind_param($query, 'issss', $validate, $sender, $contact, $sender, $contact);
		mysqli_stmt_execute($query);
		if($validate == 1){
			$mysqli = get_link();
			$query = mysqli_prepare($mysqli, 'DELETE FROM friends WHERE (BINARY sender = ? OR BINARY sender = ?) AND 
				(BINARY contact = ? OR BINARY contact = ?) AND validate != 1');
			mysqli_stmt_bind_param($query, 'ssss', $sender, $contact, $sender, $contact);
			mysqli_stmt_execute($query);
		}
	}
	// Views the friend request
	function view_req($sender, $contact){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'SELECT sender FROM friends WHERE (BINARY sender = ? OR BINARY sender = ?) AND (BINARY contact = ? OR BINARY contact = ?) AND viewed != 2');
		mysqli_stmt_bind_param($query, 'ssss', $sender, $contact, $sender, $contact);
		mysqli_stmt_execute($query);
		$i = 0;
		while(mysqli_stmt_fetch($query)){
			$i++;
		}
		if($i > 0){
			$query = mysqli_prepare($mysqli, 'SELECT sender FROM friends WHERE BINARY sender = ? AND BINARY contact = ? AND viewed = 0');
			mysqli_stmt_bind_param($query, 'ss', $contact, $sender);
			mysqli_stmt_execute($query);
			$i = 0;
			while(mysqli_stmt_fetch($query)){
				$i++;
			}
			if($i > 0){
				$query = mysqli_prepare($mysqli, 'UPDATE friends SET viewed = 1 WHERE BINARY sender = ? AND BINARY contact = ? AND viewed = 0');
				mysqli_stmt_bind_param($query, 'ss', $contact, $sender);
				mysqli_stmt_execute($query);
			
			}else{
				$query = mysqli_prepare($mysqli, 'SELECT sender FROM friends WHERE BINARY sender = ? AND BINARY contact = ? AND viewed = 1');
				mysqli_stmt_bind_param($query, 'ss', $sender, $contact);
				mysqli_stmt_execute($query);
				$i = 0;
				while(mysqli_stmt_fetch($query)){
					$i++;
				}
				if($i > 0){
					$query = mysqli_prepare($mysqli, 'UPDATE friends SET viewed = 2 WHERE BINARY sender = ? AND BINARY contact = ? AND viewed = 1');
					mysqli_stmt_bind_param($query, 'ss', $sender, $contact);
					mysqli_stmt_execute($query);
				}
			}
		}
	}
	function private_messages($user, $contact){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'SELECT name FROM users WHERE BINARY name = ?');
		mysqli_stmt_bind_param($query, 's', $contact);
		mysqli_stmt_execute($query);
		$i = 0;
		while(mysqli_stmt_fetch($query)){
			$i++;
		}
		if($i > 0){
			$query = mysqli_prepare($mysqli, 'SELECT sender FROM friends WHERE (BINARY sender = ? OR BINARY sender = ?) AND
				(BINARY contact = ? OR BINARY contact = ?) AND validate = 1');
			mysqli_stmt_bind_param($query, 'ssss', $_SESSION['name'], $contact, $_SESSION['name'], $contact);
			mysqli_stmt_execute($query);
			$i = 0;
			while(mysqli_stmt_fetch($query)){
				$i++;
			}
			if($i > 0){	
				$query = mysqli_prepare($mysqli, 'SELECT sender, contact, message, date FROM private WHERE (BINARY sender = ? OR BINARY sender = ?) AND
					(BINARY contact = ? OR BINARY contact = ?) AND is_typing = 0 ORDER BY id DESC LIMIT 80');
				mysqli_stmt_bind_param($query, 'ssss', $_SESSION['name'], $contact, $_SESSION['name'], $contact);
				mysqli_stmt_execute($query);
				mysqli_stmt_bind_result($query, $s, $c, $m, $d);
				$i = 0;
				while(mysqli_stmt_fetch($query)){
					$month = substr($d, 5, 2);
					$day = substr($d, 8, 2);
					$hour = substr($d, 11, 8);
					$m = bb_decode($m);
					echo "<span class=\"name\">
							<strong>
								".$s."
							</strong>
						</span> : 
						<div class=\"pull-right\">
							à ".$hour." le ".$day."/".$month."
						</div>
						<span>
							".$m."
						</span><hr>";
					$i++;
				}
				if($i > 0){
					$query = mysqli_prepare($mysqli, 'SELECT message FROM private WHERE BINARY sender = ? AND BINARY contact = ?');
					mysqli_stmt_bind_param($query, 'ss', $contact, $user);
					mysqli_stmt_execute($query);
					mysqli_stmt_bind_result($query, $m);
					while(mysqli_stmt_fetch($query));
					if(!empty($m)){
						$query = mysqli_prepare($mysqli, 'SELECT sender FROM private WHERE BINARY sender = ? AND BINARY contact = ? AND viewed = 0');
						mysqli_stmt_bind_param($query, 'ss', $contact, $user);
						mysqli_stmt_execute($query);
						mysqli_stmt_bind_result($query, $v);
						while(mysqli_stmt_fetch($query));
						if(!empty($v)){
							$query = mysqli_prepare($mysqli, 'UPDATE private SET viewed = 1 WHERE BINARY sender = ? AND BINARY contact = ?');
							mysqli_stmt_bind_param($query, 'ss', $contact, $user);
							mysqli_stmt_execute($query);
						}
					}
				
				}else{
					echo "<h4 class=\"text-center\">Aucun message privé ...</h4>";
				}

			}else{
				echo "<h4 class=\"text-center\">
						<a href=\"".$_SESSION['host'].constant('BASE_URL')."private&user=".$contact."\">
							votre contact n'est plus ami avec vous, veuillez rafraichir la page
						</a>
					</h4>";
			}

		}else{
			echo "<h4 class=\"text-center\">
					<a href=\"".$_SESSION['host'].constant('BASE_URL')."private&user=".$contact."\">
						votre contact n'est plus disponible, veuillez rafraichir la page
					</a>
				</h4>";
		}
	}
	function display_private_chat($sender, $contact){
		echo "<div class=\"page-header\">
				<h3 class=\"text-center\">Conversation privée avec ".$contact."</h3>
			</div>
			<input type=\"hidden\" id=\"contact_name\" value=".$contact.">
			<div class=\"messages\">";
		private_messages($_SESSION['name'], $contact);
		echo "</div>
			<form method=\"POST\" action=\"\">
				<div class=\"form-group col-md-6 col-md-offset-3\">
					<label>Votre message :</label><br>
					<textarea class=\"form-control\" name=\"message\" maxlength=\"500\" autofocus></textarea>
				</div>		
				<div class=\"col-md-6 col-md-offset-3\">
					<button name=\"submit\" class=\"btn btn-primary center-block\">
						Envoyer
					</button>
				</div>
			</form>";
	}
	function send_private_message($user, $contact, $message){
		$mysqli = get_link();
		$query = mysqli_prepare($mysqli, 'INSERT INTO private (sender, contact, message, date) VALUES (?, ?, ?, NOW())');
		mysqli_stmt_bind_param($query, 'sss', $_SESSION['name'], $contact, $message);
		mysqli_stmt_execute($query);
	}
