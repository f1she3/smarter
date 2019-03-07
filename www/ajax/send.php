<?php

require_once '../functions/init.php';
if(isset($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest'){
	if(is_logged()){
		die('ok');
		if(!is_mute($_SESSION['username'])){
			if(isset($_POST['message']) && !empty($_POST['message']) && strlen($_POST['message']) <= 350){
				$message = $_POST['message'] = secure($_POST['message']);
				$req = mysqli_prepare($mysqli, 'DELETE FROM chat WHERE sender = ? and isTyping = 1');
				mysqli_stmt_bind_param($req, 's', $_SESSION['username']);
				mysqli_stmt_execute($req);
				$query = mysqli_prepare($mysqli, 'INSERT INTO chat (sender, message, date) VALUES (?, ?, NOW())');
				mysqli_stmt_bind_param($query, 'ss', $_SESSION['username'], $message);
				mysqli_stmt_execute($query);
				
			}else{
				if(isset($_POST['isTyping'])){
					if(isset($_POST['empty'])){
						$req = mysqli_prepare($mysqli, 'DELETE FROM chat WHERE sender = ? and isTyping = 1');
						mysqli_stmt_bind_param($req, 's', $_SESSION['username']);
						mysqli_stmt_execute($req);
						
					}else{
						$req = mysqli_prepare($mysqli, 'SELECT sender FROM chat WHERE sender = ? and isTyping = 1');
						mysqli_stmt_bind_param($req, 's', $_SESSION['username']);
						mysqli_stmt_execute($req);
						$result = mysqli_stmt_fetch($req);
						if(empty($result)){
							$one = 1;
							$query = mysqli_prepare($mysqli, 'INSERT INTO chat (sender, isTyping) VALUES (?, ?)');
							mysqli_stmt_bind_param($query, 'ss', $_SESSION['username'], $one);
							mysqli_stmt_execute($query);
						}else{
						}
					}
				}
			}
		}
	}else{
		redirect(2);
	}
}else{
	redirect(2);
}
